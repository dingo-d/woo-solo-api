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
use MadeByDenis\WooSoloApi\Utils\Sanitizers;

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
		add_action('init', [$this, 'registerPluginSettings'], 1);
	}

	/**
	 * Callback to register plugin settings
	 */
	public function registerPluginSettings()
	{
		register_setting(
			'solo-api-settings-group',
			'solo_api_token',
			$this->setSettingsArguments(
				'string',
				esc_html__('Solo API token', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_measure',
			$this->setSettingsArguments(
				'string',
				esc_html__('Unit measure of the shop (e.g. piece, hour, m^3, etc.)', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				'1'
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_languages',
			$this->setSettingsArguments(
				'string',
				esc_html__('Language of the bill or the invoice', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_currency',
			$this->setSettingsArguments(
				'string',
				esc_html__('Currency of the bill or the invoice', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_service_type',
			$this->setSettingsArguments(
				'integer',
				esc_html__('Service type. A unique ID of the service (must be a number)', 'woo-solo-api'),
				'intval',
				true,
				'0'
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_show_taxes',
			$this->setSettingsArguments(
				'boolean',
				esc_html__('Show taxes option', 'woo-solo-api'),
				'rest_sanitize_boolean',
				true,
				false
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_invoice_type',
			$this->setSettingsArguments(
				'string',
				esc_html__('The type of invoice (R, R1, R2, No label or In advance)', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_mail_title',
			$this->setSettingsArguments(
				'string',
				esc_html__('The title of the invoice email', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_message',
			$this->setSettingsArguments(
				'string',
				esc_html__('The message of the invoice email', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_change_mail_from',
			$this->setSettingsArguments(
				'string',
				esc_html__('Change the \'from\' name that shows when WordPress sends the mail', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_enable_pin',
			$this->setSettingsArguments(
				'boolean',
				esc_html__('Enable PIN field in the WooCommerce checkout', 'woo-solo-api'),
				'rest_sanitize_boolean',
				true,
				false
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_enable_iban',
			$this->setSettingsArguments(
				'boolean',
				esc_html__('Enable IBAN field in the WooCommerce checkout', 'woo-solo-api'),
				'rest_sanitize_boolean',
				true,
				false
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_due_date',
			$this->setSettingsArguments(
				'string',
				esc_html__('Specify the due date on the bill or the invoice', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_mail_gateway',
			$this->setSettingsArguments(
				'string',
				esc_html__('Array of gateways that the email with the invoice should be sent', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_send_pdf',
			$this->setSettingsArguments(
				'boolean',
				esc_html__('Send the email to the client with the PDF of the bill or the invoice', 'woo-solo-api'),
				'rest_sanitize_boolean',
				true,
				false
			)
		);

		register_setting(
			'solo-api-settings-group',
			'solo_api_send_control',
			$this->setSettingsArguments(
				'string',
				esc_html__('When to send the PDF - on checkout or order confirmation', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		$availableGateways = $this->gateway->getAvailablePaymentGateways();

		register_setting(
			'solo-api-settings-group',
			'solo_api_available_gateways',
			$this->setSettingsArguments(
				'string',
				esc_html__('Available payment gateways', 'woo-solo-api'),
				'sanitize_text_field',
				true,
				''
			)
		);

		foreach ($availableGateways as $availableGateway) {
			$id = $availableGateway->id;

			register_setting(
				'solo-api-settings-group',
				'solo_api_bill_offer-' . esc_attr($id),
				$this->setSettingsArguments(
					'string',
					esc_html__('Type of payment document', 'woo-solo-api'),
					'sanitize_text_field',
					true,
					'ponuda'
				)
			);

			register_setting(
				'solo-api-settings-group',
				'solo_api_fiscalization-' . esc_attr($id),
				$this->setSettingsArguments(
					'boolean',
					esc_html__('Should the invoice be fiscalized or not', 'woo-solo-api'),
					'rest_sanitize_boolean',
					true,
					false
				)
			);

			register_setting(
				'solo-api-settings-group',
				'solo_api_payment_type-' . esc_attr($id),
				$this->setSettingsArguments(
					'string',
					esc_html__('Type of payment on api gateway (transactional account, cash, cards, etc.)', 'woo-solo-api'),
					'sanitize_text_field',
					true,
					''
				)
			);
		}
	}

	/**
	 * Helper to abstract the arguments array generation
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_setting/
	 *
	 * @param string $type The type of data associated with this setting.
	 *                     Valid values are 'string', 'boolean', 'integer', 'number', 'array', and 'object'.
	 * @param string $description A description of the data attached to this setting.
	 * @param callable $sanitizeCallback A callback function that sanitizes the option's value.
	 * @param bool|array $showInRest Whether data associated with this setting should be included in the REST API.
	 *                         When registering complex settings, this argument may optionally be an array
	 *                         with a 'schema' key.
	 * @param mixed $default Default value when calling get_option().
	 * @return array
	 */
	private function setSettingsArguments(string $type, string $description, callable $sanitizeCallback, $showInRest, $default)
	{
		return [
			'type' => $type,
			'description' => $description,
			'sanitize_callback' => $sanitizeCallback,
			'show_in_rest' => $showInRest,
			'default' => $default,
		];
	}
}
