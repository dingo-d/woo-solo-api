<?php

/**
 * WooCommerce Payment gateway implementation
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\ECommerce\WooCommerce;

use MadeByDenis\WooSoloApi\ECommerce\PaymentGateways;
use WC_Payment_Gateways;

use function WC;

/**
 * Woo payment gateways
 *
 * This is the implementation of WC_Payment_Gateways class from WooCommerce.
 * We are abstracting the implementation, in order to have a more testable code -
 * avoiding direct instantiations.
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */
class WooPaymentGateways implements PaymentGateways
{
	/**
	 * @inheritDoc
	 */
	public function getPaymentGateways(): WC_Payment_Gateways
	{
		return WC()->payment_gateways();
	}

	/**
	 * @inheritDoc
	 */
	public function getPaymentGatewayIds(): array
	{
		return $this->getPaymentGateways()->get_payment_gateway_ids();
	}

	/**
	 * @inheritDoc
	 */
	public function getAvailablePaymentGateways(): array
	{
		return $this->getPaymentGateways()->get_available_payment_gateways();
	}
}
