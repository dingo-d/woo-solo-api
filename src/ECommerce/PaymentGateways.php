<?php

/**
 * Payment gateway interface
 *
 * @since 2.0.0.
 * @package MadeByDenis\WooSoloApi\ECommerce;
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\ECommerce;

/**
 * Payment gateway interface
 *
 * Used for implementing various e-commerce payment gateways
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 */
interface PaymentGateways
{
	/**
	 * Get all payment gateways
	 *
	 * @return array
	 */
	public function getPaymentGateways(): array;

	/**
	 * Get array of registered gateway ids
	 *
	 * @return array
	 */
	public function getPaymentGatewayIds(): array;

	/**
	 * Get available gateways
	 *
	 * @return array
	 */
	public function getAvailablePaymentGateways(): array;
}
