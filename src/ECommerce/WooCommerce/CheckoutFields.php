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

use function add_filter;
use function esc_html__;
use function get_option;

/**
 * Additional fields on the checkout screen
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
		add_filter('woocommerce_checkout_fields', [$this, 'addPinField']);
		add_filter('woocommerce_checkout_fields', [$this, 'addIbanField']);
		add_filter('woocommerce_checkout_fields', [$this, 'modifyCompanyField']);
	}

	/**
	 * Include additional WooCommerce checkout fields for shipping and billing
	 *
	 * Add personal identification number (PIN) in the checkout fields - OIB in Croatian.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $fields Billing and shipping fields.
	 *
	 * @return array<string, mixed> Modified checkout fields.
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
	 * @param array<string, mixed> $fields Billing and shipping fields.
	 *
	 * @return array<string, mixed> Modified checkout fields.
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

	/**
	 * Include additional WooCommerce checkout fields for shipping and billing
	 *
	 * If the selected invoice type is R1, make the company name field mandatory.
	 *
	 * @since 2.0.0
	 *
	 * @param array<string, mixed> $fields Billing and shipping fields.
	 *
	 * @return array<string, mixed> Modified checkout fields.
	 */
	public function modifyCompanyField(array $fields): array
	{
		if (get_option('solo_api_invoice_type') !== '2') {
			return $fields;
		}

		$fields['shipping']['shipping_company'] = [
			'label'       => esc_html__('Company name', 'woo-solo-api'),
			'placeholder' => _x('Cool company inc.', 'placeholder', 'woo-solo-api'),
			'required'    => true,
			'class'       => ['form-row-wide'],
			'clear'       => true,
		];

		$fields['billing']['billing_company'] = [
			'label'       => esc_html__('Company name', 'woo-solo-api'),
			'placeholder' => _x('Cool company inc.', 'placeholder', 'woo-solo-api'),
			'required'    => true,
			'class'       => ['form-row-wide'],
			'clear'       => true,
		];

		return $fields;
	}
}
