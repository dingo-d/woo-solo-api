<?php

namespace Tests\Integration\ECommerce;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\ECommerce\WooCommerce\WooPaymentGateways;

class PaymentGatewayTest extends WPTestCase
{
	/**
	 * @var IntegrationTester
	 */
	protected $tester;

	public function setUp(): void
	{
		parent::setUp();
	}

	public function tearDown(): void
	{
		parent::tearDown();
	}

	public function testDefaultWooPaymentGateways()
	{
		$wooPaymentGateways = new WooPaymentGateways();

		$paymentGatewayList = $wooPaymentGateways->getPaymentGateways();
		$paymentGatewayIds = $wooPaymentGateways->getPaymentGatewayIds();
		$availablePaymentGateways = $wooPaymentGateways->getAvailablePaymentGateways();

		$this->assertInstanceOf('WC_Payment_Gateways', $paymentGatewayList);

		$this->assertTrue(isset(array_flip($paymentGatewayIds)['bacs']), 'BACS provider doesn\'t exist');
		$this->assertTrue(isset(array_flip($paymentGatewayIds)['cheque']), 'Checque provider doesn\'t exist');
		$this->assertTrue(isset(array_flip($paymentGatewayIds)['cod']), 'COD provider doesn\'t exist');

		// In the test we don't have any available PG.
		$this->assertEmpty($availablePaymentGateways);
	}
}
