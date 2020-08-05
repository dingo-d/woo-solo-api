<?php

/**
 * File holding CheckoutFields class
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\ECommerce\WooCommerce;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * CheckoutFields class
 *
 * Adds custom WooCommerce checkout fields based on the settings.
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */
class CheckoutFields implements Registrable
{

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('woocommerce_checkout_fields', [$this, 'addPinField']);
		add_action('woocommerce_checkout_fields', [$this, 'addIbanField']);
	}

	/**
	 * Include additional WooCommerce checkout fields for shipping and billing
	 *
	 * Add personal identification number (PIN) in the checkout fields - OIB in Croatian.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Billing and shipping fields.
	 *
	 * @return array Modified checkout fields.
	 */
	public function addPinField(array $fields): array
	{
		if (!get_option('solo_api_enable_pin')) {
			return $fields;
		}

		$fields['shipping']['shipping_pin_number'] = array(
			'label'       => esc_html__('PIN number', 'woo-solo-api'),
			'placeholder' => _x('01234567891', 'placeholder', 'woo-solo-api'),
			'required'    => false,
			'class'       => array('form-row-wide'),
			'clear'       => true,
		);

		$fields['billing']['billing_pin_number'] = array(
			'label'       => esc_html__('PIN number', 'woo-solo-api'),
			'placeholder' => _x('01234567891', 'placeholder', 'woo-solo-api'),
			'required'    => false,
			'class'       => array('form-row-wide'),
			'clear'       => true,
		);

		return $fields;
	}

	/**
	 * Include additional WooCommerce checkout fields for shipping and billing
	 *
	 * Add International Bank Account Number (IBAN) in the checkout fields.
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields Billing and shipping fields.
	 *
	 * @return array Modified checkout fields.
	 */
	public function addIbanField(array $fields): array
	{
		if (!get_option('solo_api_enable_iban')) {
			return $fields;
		}

		$fields['shipping']['shipping_iban_number'] = [
			'label'       => esc_html__('IBAN number', 'woo-solo-api'),
			'placeholder' => _x('HR12345678901234567890', 'placeholder', 'woo-solo-api'),
			'required'    => false,
			'class'       => ['form-row-wide'],
			'clear'       => true,
		];

		$fields['billing']['billing_iban_number'] = [
			'label'       => esc_html__('IBAN number', 'woo-solo-api'),
			'placeholder' => _x('HR12345678901234567890', 'placeholder', 'woo-solo-api'),
			'required'    => false,
			'class'       => ['form-row-wide'],
			'clear'       => true,
		];

		return $fields;
	}
}
