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
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * SendCustomerEmail class
 *
 * This class will hold the background logic that will be executed
 *
 * @package MadeByDenis\WooSoloApi\BackgroundJobs
 * @since 2.0.0
 */
class SendCustomerEmail extends ScheduleEvent
{

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
		$checkedGateways = get_option('solo_api_mail_gateway');

		if (!is_array($checkedGateways)) {
			$checkedGateways = unserialize($checkedGateways);
		}

		if (empty($checkedGateways)) {
			return false;
		}

		if (!in_array($paymentMethod, $checkedGateways, true)) {
			return false;
		}

		global $wp_filesystem;

		if (empty($wp_filesystem)) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
		}

		$checkoutUrl = \wc_get_checkout_url();
		$url = wp_nonce_url($checkoutUrl, '_wpnonce', '_wpnonce');
		$credentials = \request_filesystem_credentials($url, '', false, false, null);

		if ($credentials === false) {
			return; // stop processing here.
		}

		$responseBody = (array)$body;

		// Create pdf.
		$pdfLink = esc_url($responseBody[$billType]['pdf']);

		if ($billType === 'racun') {
			$pdfName = esc_html($responseBody[$billType]['broj_racuna']);
			/* translators: name of the invoice file, don't use diacritics! */
			$pdfName = esc_html__('invoice-', 'woo-solo-api') . $pdfName;
		} else {
			$pdfName = esc_html($responseBody[$billType]['broj_ponude']);
			/* translators: name of the offer file, don't use diacritics! */
			$pdfName = esc_html__('offer-', 'woo-solo-api') . $pdfName;
		}

		$pdfGet = wp_remote_get($pdfLink);

		if (is_wp_error($pdfGet)) {
			$error_code = wp_remote_retrieve_response_code($pdfGet);
			$error_message = wp_remote_retrieve_response_message($pdfGet);
			return new \WP_Error($error_code, $error_message);
		}

		$pdfContents = $pdfGet['body'];

		// Now we have some credentials, try to get the wp_filesystem running.
		if (!WP_Filesystem($credentials)) {
			// Our credentials were no good, ask the user for them again.
			\request_filesystem_credentials($url, '', true, false, null);
			return true;
		}

		$uploadDir = wp_upload_dir();

		$newDir = $uploadDir['basedir'] . '/ponude/' . date('Y') . '/' . date('m');

		if (!file_exists($newDir)) {
			wp_mkdir_p($newDir);
		}

		$attachment = $newDir . '/' . $pdfName . '.pdf';

		if (file_exists($attachment)) {
			$attachment = $newDir . '/' . $pdfName . '-' . wp_rand() . '.pdf';
		}

		WP_Filesystem($credentials);

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

		$attachment_id = wp_insert_attachment(
			$attachmentArray,
			$urlParse['path'],
			0
		); // Create attachment in the Media screen.

		// Wrapping in nl2br to see if HTML will parse correctly.
		$emailMessage = nl2br(get_option('solo_api_message'));
		$emailTitle = get_option('solo_api_mail_title');

		$billType = ($billType === 'racun') ? esc_html__('invoice', 'woo-solo-api') : esc_html__(
			'offer',
			'woo-solo-api'
		);

		/* translators: 1:Bill type */
		$defaultMessage = sprintf(esc_html__('Your %s is in the attachment.', 'woo-solo-api'), $billType);
		/* translators: 1:Bill type 2:Your site name */
		$defaultTitle = sprintf(esc_html__('Your %1$s from %2$s', 'woo-solo-api'), $billType, get_bloginfo('name'));

		$emailMessage = !empty($emailMessage) ? $emailMessage : apply_filters(
			'woo-solo-api-default-email-message',
			$defaultMessage
		);

		$emailTitle = !empty($emailTitle) ? $emailTitle : apply_filters(
			'woo-solo-api-default-email-title',
			$defaultTitle
		);

		// Send mail with the attachment.
		$headers = [
			'MIME-Version: 1.0',
			'Content-Type: text/html',
		];

		$headers = apply_filters(
			'woo-solo-api-email-headers',
			$headers
		);

		wp_mail($email, $emailTitle, $emailMessage, $headers, [$attachment]);

		// Now we delete the saved attachment because of GDPR :).
		$deleted = wp_delete_attachment($attachment_id, true);

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
		SoloOrdersTable::updateOrdersTable($orderId, true, true, true);
	}

	/**
	 * @inheritDoc
	 */
	protected function getJobName(): string
	{
		return self::JOB_NAME;
	}

	public function getArgumentsCount(): int
	{
		return 5;
	}
}
