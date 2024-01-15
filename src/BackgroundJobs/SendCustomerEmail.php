<?php

/**
 * File holding SendCustomerEmail class
 *
 * @package MadeByDenis\WooSoloApi\BackgroundJobs
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\BackgroundJobs;

use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\Request\SoloApiRequest;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use WP_Error;

use function esc_html;
use function esc_html__;
use function get_option;
use function request_filesystem_credentials;
use function wp_delete_attachment;
use function WP_Filesystem;
use function wp_mail;

/**
 * Holds logic for sending customer email with SOLO API order/invoice PDF
 *
 * This class holds logic for executing background job that sends customer email,
 * received from SOLO service.
 *
 * @package MadeByDenis\WooSoloApi\BackgroundJobs
 * @since 2.0.0
 */
class SendCustomerEmail extends ScheduleEvent
{

	/**
	 * @var string Name of the custom job.
	 */
	public const JOB_NAME = 'sendCustomerEmail';

	/**
	 * @inheritDoc
	 */
	public function registerProcess(...$args)
	{
		$orderId = $args[0];
		$body = $args[1];
		$email = $args[2];
		$billType = $args[3];
		$paymentMethod = $args[4];

		/**
		 * In older versions this should be a serialized string,
		 * but in the new version we manually serialize this in JS.
		 * This is why we should check the type and unserialize if necessary to get an array.
		 */
		$checkedGateways = get_option('solo_api_mail_gateway', 'a:0:{}');

		if (is_string($checkedGateways)) {
			$checkedGateways = unserialize($checkedGateways);
		}

		if (empty($checkedGateways)) {
			SoloOrdersTable::addApiResponseError($orderId, esc_html__('Selected payment gateways are empty', 'woo-solo-api'));
			return;
		}

		if (!is_array($checkedGateways)) {
			SoloOrdersTable::addApiResponseError($orderId, esc_html__('Selected payment gateways are not array', 'woo-solo-api'));
			return;
		}

		if (!in_array($paymentMethod, $checkedGateways, true)) {
			SoloOrdersTable::addApiResponseError($orderId, esc_html__('Selected payment gateways is not in the list of payment gateways', 'woo-solo-api'));
			return;
		}


		global $wp_filesystem;

		if (empty($wp_filesystem)) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
		}

		$responseBody = (array)$body;

		// Create pdf.
		$pdfLink = (!empty($responseBody[$billType]) && is_array($responseBody[$billType])) ? esc_url($responseBody[$billType]['pdf']) : '';

		if (empty($pdfLink)) {
			SoloOrdersTable::addApiResponseError($orderId, esc_html__('PDF invoice/order is missing', 'woo-solo-api'));
			return;
		}

		if ($billType === SoloApiRequest::INVOICE) {
			$soloOrderId = (!empty($responseBody[$billType]) && is_array($responseBody[$billType])) ? $responseBody[$billType]['broj_racuna'] : '';
			$pdfName = esc_html($soloOrderId);
			/* translators: name of the invoice file, don't use diacritics! */
			$pdfName = esc_html__('invoice-', 'woo-solo-api') . $pdfName;
		} else {
			$soloOrderId = (!empty($responseBody[$billType]) && is_array($responseBody[$billType])) ? $responseBody[$billType]['broj_ponude'] : '';
			$pdfName = esc_html($soloOrderId);
			/* translators: name of the offer file, don't use diacritics! */
			$pdfName = esc_html__('offer-', 'woo-solo-api') . $pdfName;
		}

		$pdfGet = wp_remote_get($pdfLink, ['timeout' => 30]);

		if (is_wp_error($pdfGet)) {
			$errorCode = $pdfGet->get_error_code();
			$errorMessage = $pdfGet->get_error_message();

			SoloOrdersTable::addApiResponseError($orderId, "$errorCode: $errorMessage");

			return new WP_Error($errorCode, $errorMessage); // @phpstan-ignore-line
		}

		$pdfContents = $pdfGet['body'];

		// Try to get the wp_filesystem running.
		if (!WP_Filesystem()) {
			// Our credentials were no good, ask the user for them again.
			request_filesystem_credentials('', '', true, '', null, false);
			SoloOrdersTable::addApiResponseError($orderId, esc_html__('Access to WP_Filesystem is denied', 'woo-solo-api'));

			return;
		}

		$uploadDir = wp_upload_dir();

		$newDir = $uploadDir['basedir'] . '/ponude/' . date('Y') . '/' . date('m');

		if (!file_exists($newDir)) {
			wp_mkdir_p($newDir);
		}

		/**
		 * Remove any slashes that might affect the storage of the file.
		 */
		$pdfName = \wp_unslash($pdfName);

		$attachment = $newDir . '/' . $pdfName . '.pdf';

		if (file_exists($attachment)) {
			$attachment = $newDir . '/' . $pdfName . '-' . wp_rand() . '.pdf';
		}

		$wp_filesystem->put_contents(
			$attachment,
			$pdfContents,
			FS_CHMOD_FILE // predefined mode settings for WP files.
		);

		// Store the pdf as an attachment.
		$filetype = wp_check_filetype($pdfName, null);

		$attachmentArray = [
			'guid' => $attachment,
			'post_mime_type' => $filetype['type'],
			'post_title' => $pdfName,
			'post_content' => '',
			'post_status' => 'inherit',
		];
		$urlParse = wp_parse_url($attachment);
		if ($urlParse === false) {
			// phpcs:ignore
			SoloOrdersTable::addApiResponseError($orderId, esc_html__('Error in sending customer email. Attachment URL cannot be parsed.', 'woo-solo-api'));
			return;
		}

		$attachmentId = wp_insert_attachment(
			$attachmentArray,
			$urlParse['path'],
			0
		); // Create attachment in the Media screen.

		if (is_wp_error($attachmentId)) { // @phpstan-ignore-line
			$errorCode = $attachmentId->get_error_code();
			$errorMessage = $attachmentId->get_error_message();

			SoloOrdersTable::addApiResponseError($orderId, "$errorCode: $errorMessage");

			return new WP_Error($errorCode, $errorMessage); // @phpstan-ignore-line
		}

		// Wrapping in nl2br to see if HTML will parse correctly.
		$message = get_option('solo_api_message', '');

		if (!is_string($message)) {
			// phpcs:ignore
			SoloOrdersTable::addApiResponseError($orderId, esc_html__('Error in sending customer email. Email message must be of string type.', 'woo-solo-api'));
			return;
		}

		$emailMessage = nl2br($message);
		$emailTitle = get_option('solo_api_mail_title');

		$billType = ($billType === SoloApiRequest::INVOICE) ?
			esc_html__('invoice', 'woo-solo-api') :
			esc_html__('offer', 'woo-solo-api');

		/* translators: 1:Bill type */
		$defaultMessage = sprintf(esc_html__('Your %s is in the attachment.', 'woo-solo-api'), $billType);
		/* translators: 1:Bill type 2:Your site name */
		$defaultTitle = sprintf(esc_html__('Your %1$s from %2$s', 'woo-solo-api'), $billType, get_bloginfo('name'));

		$emailMessage = !empty($emailMessage) ?
			/**
			 * Modify the email message from the options
			 *
			 * Email message can be set in the options and will be outputted here.
			 * If, for whatever reason we want to modify it some more, we can do that here.
			 *
			 * Usage:
			 *
			 * add_filter('woo_solo_api_modify_options_email_message', 'my_message_filter', 10, 3);
			 *
			 * function my_message_filter($emailMessage, $orderId, $email) {
			 *   // (maybe) modify $emailMessage.
			 *   return $emailMessage;
			 * }
			 *
			 * @since 2.1.0 Added order ID and email as a parameters for the filter.
			 * @since 2.0.0
			 *
			 * @param string $emailMessage Email message from options to filter.
			 * @param int    $orderId Order ID.
			 * @param string $email Email address of the person for whom this message needs to be send to.
			 */
			apply_filters('woo_solo_api_modify_options_email_message', $emailMessage, $orderId, $email) :

			/**
			 * Modify the default email message
			 *
			 * If you don't set the message in the options, you can still filter the default one.
			 *
			 * Usage:
			 *
			 * add_filter('woo_solo_api_modify_default_email_message', 'my_message_filter', 10, 3);
			 *
			 * function my_message_filter($defaultMessage, $orderId, $email) {
			 *   // (maybe) modify $defaultMessage.
			 *   return $defaultMessage;
			 * }
			 *
			 * @since 2.1.0 Added order ID and email as a parameters for the filter.
			 * @since 2.0.0
			 *
			 * @param string $defaultMessage Email message to filter.
			 * @param int    $orderId Order ID.
			 * @param string $email Email address of the person for whom this message needs to be send to.
			 */
			apply_filters('woo_solo_api_modify_default_email_message', $defaultMessage, $orderId, $email);

		$emailTitle = !empty($emailTitle) ?
			/**
			 * Modify the email title from the options
			 *
			 * Email title for the customer can be set in the options,
			 * but you can modify it further with this filter.
			 *
			 * Usage:
			 *
			 * add_filter('woo_solo_api_modify_options_email_title', 'my_title_filter', 10, 2);
			 *
			 * function my_title_filter($emailTitle, $orderId) {
			 *   // (maybe) modify $emailTitle.
			 *   return $emailTitle;
			 * }
			 *
			 * @since 2.1.0 Added order ID as a parameter for the filter.
			 * @since 2.0.0
			 *
			 * @param string $emailTitle Email title.
			 * @param int    $orderId Order ID.
			 */
			apply_filters('woo_solo_api_modify_options_email_title', $emailTitle, $orderId) :

			/**
			 * Modify the default email title from the options
			 *
			 * If you don't use the title from the options, you can use default one.
			 * And modify it.
			 *
			 * Usage:
			 *
			 * add_filter('woo_solo_api_modify_default_email_title', 'my_title_filter', 10, 2);
			 *
			 * function my_title_filter($defaultTitle, $orderId) {
			 *   // (maybe) modify $defaultTitle.
			 *   return $defaultTitle;
			 * }
			 *
			 * @since 2.1.0 Added order ID as a parameter for the filter.
			 * @since 2.0.0
			 *
			 * @param string $emailTitle Email title.
			 * @param int    $orderId Order ID.
			 */
			apply_filters('woo_solo_api_modify_default_email_title', $defaultTitle, $orderId);

		// Send mail with the attachment.
		$headers = [
			'MIME-Version: 1.0',
			'Content-Type: text/html',
		];

		/**
		 * Filter email headers
		 *
		 * When email to customer is sent, maybe you want to add something more. In that
		 * case you'll probably need to modify the headers sent with the email.
		 * Default ones are
		 *
		 *  [
				'MIME-Version: 1.0',
				'Content-Type: text/html',
			];
		 *
		 * You can add to that list.
		 *
		 * @since 2.0.0
		 *
		 * @param array $headers Email headers to pass to wp_mail.
		 */
		$headers = apply_filters('woo_solo_api_email_headers', $headers);

		wp_mail($email, $emailTitle, $emailMessage, $headers, [$attachment]);

		// Now we delete the saved attachment because of GDPR :).
		$deleted = wp_delete_attachment($attachmentId, true);
		// If for some reason WP won't delete it, try to force deletion.
		if ($deleted === false || $deleted === null) {
			if (!file_exists($attachment)) {
				return;
			}

			unlink($attachment);
		}

		// Remove directory as well.
		$dir = $uploadDir['basedir'] . '/ponude/';
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

		foreach ($files as $file) {
			if ($file->isDir()) {
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}

		rmdir($dir);

		/**
		 * Update the database status:
		 *
		 * Order sent to API - YES;
		 * Email sent to user - YES;
		 * Update - YES;
		 */
		SoloOrdersTable::updateOrdersTable($orderId, $soloOrderId, true, true, true);

		return;
	}

	/**
	 * @inheritDoc
	 */
	protected function getJobName(): string
	{
		return self::JOB_NAME;
	}

	/**
	 * @inheritDoc
	 */
	protected function getArgumentsCount(): int
	{
		return 5;
	}
}
