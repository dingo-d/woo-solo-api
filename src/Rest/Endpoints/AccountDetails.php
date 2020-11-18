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
	public const ROUTE_NAME = '/solo-account-details';

	/**
	 * @inheritDoc
	 */
	protected function getCallbackArguments(): array
	{
		return [
			'methods' => static::READABLE,
			'callback' => [$this, 'restCallback'],
			'permission_callback' => [$this, 'restPermissionCheck'],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function restCallback(WP_REST_Request $request)
	{
		$soloApiToken = get_option('solo_api_token');

		$response = wp_remote_get("https://api.solo.com.hr/racun?token={$soloApiToken}");

		if (is_wp_error($response)) {
			$errorCode = wp_remote_retrieve_response_code($response);
			$errorMessage = wp_remote_retrieve_response_message($response);

			$data = $errorCode . ': ' . $errorMessage;
		} else {
			$data = wp_remote_retrieve_body($response);
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
