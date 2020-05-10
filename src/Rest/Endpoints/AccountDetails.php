<?php

/**
 * File holding AccountDetails class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest\Endpoints;

use MadeByDenis\WooSoloApi\Rest\BaseRoute;
use MadeByDenis\WooSoloApi\Rest\RestCallable;
use WP_REST_Request;

/**
 * AccountDetails class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
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
			'methods'             => static::READABLE,
			'callback'            => [$this, 'restCallback'],
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
			$data = json_decode($response['body'], true);
		}

		return rest_ensure_response(wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	}

	/**
	 * Check if the current user has necessary privileges to access the endpoint
	 *
	 * @param WP_REST_Request $request
	 * @return bool
	 */
	public function restPermissionCheck(WP_REST_Request $request)
	{
		return current_user_can('manage_options');
	}
}
