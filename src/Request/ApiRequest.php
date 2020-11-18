<?php

/**
 * File holding ApiRequest interface
 *
 * @package MadeByDenis\WooSoloApi\Request
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Request;

/**
 * ApiRequest interface
 *
 * @package MadeByDenis\WooSoloApi\Request
 * @since 2.0.0
 */
interface ApiRequest
{
	/**
	 * Execute API call
	 *
	 * @param mixed $order Order ID.
	 *
	 * @retrun void
	 */
	public function executeApiCall($order): void;
}
