<?php

/**
 * File holding IsUserLoggedIn trait
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 3.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest\Endpoints;

use WP_REST_Request;

/**
 * Check if user has correct permissions to access protected REST endpoints
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 3.0.0
 */
trait IsUserLoggedIn
{
	/**
	 * Check if the current user has necessary privileges to access the endpoint
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function canUserAccessEndpoint(WP_REST_Request $request): bool
	{
		return is_user_logged_in() && current_user_can('manage_options');
	}
}
