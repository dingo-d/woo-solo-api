<?php

/**
 * File holding RestCallable interface
 *
 * @package MadeByDenis\WooSoloApi\Rest
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest;

use WP_REST_Request;
use WP_REST_Response;

/**
 * RestCallable interface
 *
 * @package MadeByDenis\WooSoloApi\Rest
 * @since 2.0.0
 */
interface RestCallable
{
	/**
	 * Method that returns rest response callback
	 *
	 * @param WP_REST_Request $request Data gotten from endpoint url.
	 *
	 * @return WP_REST_Response|mixed  If response generated an error, WP_Error, if response
	 *                                 is already an instance, WP_HTTP_Response, otherwise
	 *                                 returns a new WP_REST_Response instance.
	 */
	public function restCallback(WP_REST_Request $request);
}
