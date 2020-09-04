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

	public function testWooPaymentGateways()
	{
		$wooPaymentGateways = new WooPaymentGateways();

		$paymentGatewayList = $wooPaymentGateways->getPaymentGateways();
		$paymentGatewayIds = $wooPaymentGateways->getPaymentGatewayIds();
		$availablePaymentGateways = $wooPaymentGateways->getAvailablePaymentGateways();

		$this->assertTrue(isset($paymentGatewayList['bacs']));
		$this->assertTrue(isset($paymentGatewayList['cheque']));
		$this->assertTrue(isset($paymentGatewayList['cod']));
		$this->assertTrue(isset($paymentGatewayList['paypal']));

		$this->assertTrue(isset(array_flip($paymentGatewayIds)['bacs']));
		$this->assertTrue(isset(array_flip($paymentGatewayIds)['cheque']));
		$this->assertTrue(isset(array_flip($paymentGatewayIds)['cod']));
		$this->assertTrue(isset(array_flip($paymentGatewayIds)['paypal']));

		// In the test we don't have any available PG.

		$this->assertEmpty($availablePaymentGateways);
	}
}
