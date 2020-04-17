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
		add_action('admin_init', [$this, 'registerPluginSettings'], 1);
	}

	public function registerPluginSettings()
	{
		$args = ['show_in_rest' => true];

		register_setting('solo-api-settings-group', 'solo_api_token', $args);
		register_setting('solo-api-settings-group', 'solo_api_measure', $args);
		register_setting('solo-api-settings-group', 'solo_api_payment_type', $args);
		register_setting('solo-api-settings-group', 'solo_api_languages', $args);
		register_setting('solo-api-settings-group', 'solo_api_currency', $args);
		register_setting('solo-api-settings-group', 'solo_api_service_type', $args);
		register_setting('solo-api-settings-group', 'solo_api_show_taxes', $args);
		register_setting('solo-api-settings-group', 'solo_api_invoice_type', $args);
		register_setting('solo-api-settings-group', 'solo_api_mail_title', $args);
		register_setting('solo-api-settings-group', 'solo_api_message', $args);
		register_setting('solo-api-settings-group', 'solo_api_change_mail_from', $args);
		register_setting('solo-api-settings-group', 'solo_api_enable_pin', $args);
		register_setting('solo-api-settings-group', 'solo_api_enable_iban', $args);
		register_setting('solo-api-settings-group', 'solo_api_due_date', $args);
		register_setting('solo-api-settings-group', 'solo_api_mail_gateway', $args);
		register_setting('solo-api-settings-group', 'solo_api_send_pdf', $args);
		register_setting('solo-api-settings-group', 'solo_api_send_control', $args);

		$availableGateways = $this->gateway->getAvailablePaymentGateways();
		register_setting('solo-api-settings-group', 'solo_api_available_gateways', $args);

		foreach ($availableGateways as $availableGateway) {
			$id = $availableGateway->id;

			register_setting('solo-api-settings-group', 'solo_api_bill_offer-' . esc_attr($id), $args);
			register_setting('solo-api-settings-group', 'solo_api_fiscalization-' . esc_attr($id), $args);
			register_setting('solo-api-settings-group', 'solo_api_payment_type-' . esc_attr($id), $args);
		}
	}
}
