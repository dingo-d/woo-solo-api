<?php

/**
 * File holding Plugin settings class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Settings
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Settings;

use MadeByDenis\WooSoloApi\Core\Registrable;
use MadeByDenis\WooSoloApi\ECommerce\PaymentGateways;

/**
 * Plugin settings class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Settings
 */
class PluginSettings implements Registrable
{

	private $gateway;

	/**
	 * PluginSettings constructor.
	 * @param PaymentGateways $gateway
	 */
	public function __construct(PaymentGateways $gateway)
	{
		$this->gateway = $gateway;
	}

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('admin_init', [$this, 'registerPluginSettings']);
	}

	public function registerPluginSettings()
	{
		register_setting('solo-api-settings-group', 'solo_api_token');
		register_setting('solo-api-settings-group', 'solo_api_measure');
		register_setting('solo-api-settings-group', 'solo_api_payment_type');
		register_setting('solo-api-settings-group', 'solo_api_languages');
		register_setting('solo-api-settings-group', 'solo_api_currency');
		register_setting('solo-api-settings-group', 'solo_api_service_type');
		register_setting('solo-api-settings-group', 'solo_api_show_taxes');
		register_setting('solo-api-settings-group', 'solo_api_invoice_type');
		register_setting('solo-api-settings-group', 'solo_api_mail_title');
		register_setting('solo-api-settings-group', 'solo_api_message');
		register_setting('solo-api-settings-group', 'solo_api_change_mail_from');
		register_setting('solo-api-settings-group', 'solo_api_enable_pin');
		register_setting('solo-api-settings-group', 'solo_api_enable_iban');
		register_setting('solo-api-settings-group', 'solo_api_due_date');
		register_setting('solo-api-settings-group', 'solo_api_mail_gateway');
		register_setting('solo-api-settings-group', 'solo_api_send_pdf');
		register_setting('solo-api-settings-group', 'solo_api_send_control');

		$availableGateways = $this->gateway->getAvailablePaymentGateways();
		register_setting('solo-api-settings-group', 'solo_api_available_gateways');

		foreach ($availableGateways as $availableGateway) {
			$id = $availableGateway->id;

			register_setting('solo-api-settings-group', 'solo_api_bill_offer-' . esc_attr($id));
			register_setting('solo-api-settings-group', 'solo_api_fiscalization-' . esc_attr($id));
			register_setting('solo-api-settings-group', 'solo_api_payment_type-' . esc_attr($id));
		}
	}
}
