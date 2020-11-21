<?php

/**
 * File holding AdminOrder class
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\ECommerce\WooCommerce;

use Automattic\WooCommerce\Admin\Overrides\Order;
use MadeByDenis\WooSoloApi\Core\Registrable;

use function add_action;
use function esc_html;
use function esc_html__;

/**
 * Modification to the WooCommerce admin order page
 *
 * Adds additional data to the admin order page, if enabled in the plugin settings.
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */
class AdminOrder implements Registrable
{

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('woocommerce_admin_order_data_after_shipping_address', [$this, 'checkoutFieldDisplayAdminOrderMeta']);
	}

	/**
	 * Add additional fields on the order edit page
	 *
	 * This callback will show additional fields in the Edit order screen in the admin.
	 *
	 * @param Order $order WooCommerce order object.
	 *
	 * @return void
	 */
	public function checkoutFieldDisplayAdminOrderMeta(Order $order): void
	{
		$shippingPin = get_post_meta($order->get_id(), '_shipping_pin_number', true);
		$billingPin = get_post_meta($order->get_id(), '_billing_pin_number', true);
		$shippingIban = get_post_meta($order->get_id(), '_shipping_iban_number', true);
		$billingIban = get_post_meta($order->get_id(), '_billing_iban_number', true);

		if (!empty($shippingPin)) {
			echo '<p><strong> ' . esc_html__('Customer shipping PIN number', 'woo-solo-api') . ' :</strong> ' . esc_html($shippingPin) . '</p>';
		}

		if (!empty($billingPin)) {
			echo '<p><strong> ' . esc_html__('Customer billing PIN number', 'woo-solo-api') . ' :</strong> ' . esc_html($billingPin) . '</p>';
		}

		if (!empty($shippingIban)) {
			echo '<p><strong> ' . esc_html__('Customer shipping IBAN number', 'woo-solo-api') . ' :</strong> ' . esc_html($shippingIban) . '</p>';
		}

		if (!empty($billingIban)) {
			echo '<p><strong> ' . esc_html__('Customer billing IBAN number', 'woo-solo-api') . ' :</strong> ' . esc_html($billingIban) . '</p>';
		}
	}
}
