<?php

namespace Tests\Integration\Settings;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\ECommerce\PaymentGateways;
use MadeByDenis\WooSoloApi\Settings\PluginSettings;

class PluginSettingsTest extends WPTestCase
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

	public function testSettingsAreRegistered()
	{
		global $wp_registered_settings;
		$gateway = new class implements PaymentGateways {

			public function getPaymentGateways(): array
			{
				return ['paypal', 'bacs', 'cod', 'cheque'];
			}

			public function getPaymentGatewayIds(): array
			{
				return [
					'1'  => 'paypal',
					'2'  => 'bacs',
					'22' => 'cod',
					'34' => 'cheque'
				];
			}

			public function getAvailablePaymentGateways(): array
			{
				return [
					'paypal' => (object) ['title' => 'paypal'],
					'bacs' => (object) ['title' => 'bacs'],
				];
			}
		};

		$settings = new PluginSettings($gateway);

		$settings->register();
		$settings->registerPluginSettings();

		$availableGateways = array_map(function ($paymentGateway) {
			return $paymentGateway->title;
		}, $gateway->getAvailablePaymentGateways());

		/**
		 * Not super happy about this. On one hand, it's duplication, and management could
		 * be a pain, if new settings are added later on. But having these as a sort of class constants
		 * seem a bit too much. And adding this as a class method that returns a name based on a key,
		 * again, seems like an overkill.
		 */
		$settingList = [
			'solo_api_token',
			'solo_api_measure',
			'solo_api_languages',
			'solo_api_currency',
			'solo_api_service_type',
			'solo_api_show_taxes',
			'solo_api_invoice_type',
			'solo_api_mail_title',
			'solo_api_message',
			'solo_api_change_mail_from',
			'solo_api_enable_pin',
			'solo_api_enable_iban',
			'solo_api_due_date',
			'solo_api_mail_gateway',
			'solo_api_send_pdf',
			'solo_api_send_control',
			'solo_api_available_gateways',
		];

		foreach ($availableGateways as $gatewayID => $gatewayName) {
			$settingList[] = 'solo_api_bill_offer-' . $gatewayID;
			$settingList[] = 'solo_api_fiscalization-' . $gatewayID;
			$settingList[] = 'solo_api_payment_type-' . $gatewayID;
		}

		foreach ($settingList as $setting) {
			$this->assertArrayHasKey($setting, $wp_registered_settings);
		}
	}
}
