<?php

/**
 * File holding AccountDetails class
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest\Endpoints;

use MadeByDenis\WooSoloApi\Rest\BaseRoute;
use MadeByDenis\WooSoloApi\Rest\RestCallable;
use WP_Error;
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
class AccountDetails extends BaseRoute implements RestCallable
{
	use IsUserLoggedIn;

	public const ROUTE_NAME = '/solo-account-details';

	/**
	 * @inheritDoc
	 */
	protected function getCallbackArguments(): array
	{
		return [
			'methods' => static::READABLE,
			'callback' => [$this, 'restCallback'],
			'permission_callback' => [$this, 'canUserAccessEndpoint'],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function restCallback(WP_REST_Request $request)
	{
		$soloApiToken = get_option('solo_api_token');

		if (!\is_string($soloApiToken)) {
			return new WP_Error(esc_html__('Solo API token must be a string', 'woo-solo-api'));
		}

		$response = wp_remote_get("https://api.solo.com.hr/racun?token={$soloApiToken}");

		if (is_wp_error($response)) {
			$errorCode = wp_remote_retrieve_response_code($response);
			$errorMessage = wp_remote_retrieve_response_message($response);

			$data = $errorCode . ': ' . $errorMessage;
		} else {
			$data = wp_remote_retrieve_body($response);
		}

		$dataArray = json_decode($data, true);

		$status = (int) $dataArray['status'];

		// No invoices. Try to fetch offers
		if ($status === 123) {
			$response = wp_remote_get("https://api.solo.com.hr/ponuda?token={$soloApiToken}");

			if (is_wp_error($response)) {
				$errorCode = wp_remote_retrieve_response_code($response);
				$errorMessage = wp_remote_retrieve_response_message($response);

				$data = $errorCode . ': ' . $errorMessage;
			} else {
				$data = wp_remote_retrieve_body($response);
			}
		}

		return rest_ensure_response($data);
	}
}
