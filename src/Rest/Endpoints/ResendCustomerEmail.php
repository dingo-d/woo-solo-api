<?php

/**
 * File holding ResendCustomerEmail class
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.3.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest\Endpoints;

use MadeByDenis\WooSoloApi\BackgroundJobs\SendCustomerEmail;
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\Rest\BaseRoute;
use MadeByDenis\WooSoloApi\Rest\RestCallable;
use WP_REST_Request;

use function get_option;

/**
 * Account details endpoint
 *
 * This class holds the callback function for the REST endpoint used to check the details from SOLO API.
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.0.0
 */
class ResendCustomerEmail extends BaseRoute implements RestCallable
{
	public const ROUTE_NAME = '/resend-customer-email';

	/**
	 * @inheritDoc
	 */
	protected function getCallbackArguments(): array
	{
		return [
			'methods' => static::CREATABLE,
			'callback' => [$this, 'restCallback'],
			'permission_callback' => [$this, 'restPermissionCheck'],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function restCallback(WP_REST_Request $request)
	{
		// Get the information about the account from the details.

		// Call the method that will resend the PDF email.

		/**
		 * Clear the previous errors
		 *
		 * If curl error happened, for whatever reason, and you try to resend the order manually
		 * the error should be removed if the API call was successful.
		 */
		SoloOrdersTable::addApiResponseError($orderId, '');

		// Send mail to the customer with the PDF of the invoice.
		$sendPdf = get_option('solo_api_send_pdf');

		if ($sendPdf === '1') {
			// Register a background job - in 30 sec ping the API to get the PDF.
			wp_schedule_single_event(
				time() + 15,
				SendCustomerEmail::JOB_NAME,
				[
					'orderId' => $orderId,
					'responseDetails' => $responseDetails,
					'email' => $email,
					'billType' => $billType,
					'paymentMethod' => $paymentMethod,
				]
			);
		}

		return rest_ensure_response($data);
	}

	/**
	 * Check if the current user has necessary privileges to access the endpoint
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function restPermissionCheck(WP_REST_Request $request)
	{
		return is_user_logged_in() && current_user_can('manage_options');
	}
}
