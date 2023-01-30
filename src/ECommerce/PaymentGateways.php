<?php

/**
 * Payment gateway interface
 *
 * @package MadeByDenis\WooSoloApi\ECommerce;
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\ECommerce;

/**
 * Payment gateway interface
 *
 * Used for implementing various e-commerce payment gateways
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */
interface PaymentGateways
{
	/**
	 * Get all payment gateways
	 *
	 * @return object|array<mixed>
	 */
	public function getPaymentGateways();

	/**
	 * Get array of registered gateway ids
	 *
	 * @return array<mixed>
	 */
	public function getPaymentGatewayIds(): array;

	/**
	 * Get available gateways
	 *
	 * @return array<mixed>
	 */
	public function getAvailablePaymentGateways(): array;
}
