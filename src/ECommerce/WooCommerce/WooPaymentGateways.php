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

/**
 * Woo payment gateways class
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
	 * WooCommerce payment gateways.
	 *
	 * @var WC_Payment_Gateways
	 */
	private $wcPaymentGateway;

	public function __construct()
	{
		$this->wcPaymentGateway = new WC_Payment_Gateways();
	}

	/**
	 * @inheritDoc
	 */
	public function getPaymentGateways(): array
	{
		return $this->wcPaymentGateway->payment_gateways();
	}

	/**
	 * @inheritDoc
	 */
	public function getPaymentGatewayIds(): array
	{
		return $this->wcPaymentGateway->get_payment_gateway_ids();
	}

	/**
	 * @inheritDoc
	 */
	public function getAvailablePaymentGateways(): array
	{
		return $this->wcPaymentGateway->get_available_payment_gateways();
	}
}
