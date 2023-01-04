<?php

/**
 * File holding ClearExchangeTransient class
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.2.1
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest\Endpoints;

use MadeByDenis\WooSoloApi\Rest\BaseRoute;
use MadeByDenis\WooSoloApi\Rest\RestCallable;
use MadeByDenis\WooSoloApi\Utils\FetchExchangeRate;
use WP_REST_Request;

/**
 * Clear exchange transient endpoint
 *
 * This class holds the callback function for the REST endpoint
 * used to clear central bank exchange rates transient.
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.2.1
 */
class ClearExchangeTransient extends BaseRoute implements RestCallable
{
	public const ROUTE_NAME = '/solo-clear-currency-transient';

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
		return rest_ensure_response(delete_transient(FetchExchangeRate::TRANSIENT));
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
