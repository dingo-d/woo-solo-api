<?php

/**
 * Render options submenu page
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\AdminMenus
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\AdminMenus;

use MadeByDenis\WooSoloApi\ECommerce\PaymentGateways;

/**
 * Add an options submenu inside WooCommerce menu
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\AdminMenus
 */
final class OptionsSubmenu extends AdminSubmenu
{
	public const CAPABILITY = 'manage_options';

	public const MENU_SLUG = 'solo_api_options';

	public const PARENT_MENU = 'woocommerce';

	public const WOO_PAGE_ID = 'woocommerce_page_solo_api_options';

	public const VIEW_URI = 'views/options-page';

	private $gateway;

	public function __construct(PaymentGateways $gateway)
	{
		$this->gateway = $gateway;
	}

	public function registerSubmenu(string $context)
	{
		parent::registerSubmenu($context);

		// Add the WooCommerce navigation bar.
		wc_admin_connect_page([
			'id'        => self::MENU_SLUG,
			'screen_id' => self::WOO_PAGE_ID,
			'title'     => $this->getTitle(),
		]);
	}

	/**
	 * @inheritDoc
	 */
	protected function getTitle(): string
	{
		return __('Woo Solo Api Options', 'woo-solo-api');
	}

	/**
	 * @inheritDoc
	 */
	protected function getMenuTitle(): string
	{
		return __('Solo API Options', 'woo-solo-api');
	}

	/**
	 * @inheritDoc
	 */
	protected function getCapability(): string
	{
		return self::CAPABILITY;
	}

	/**
	 * @inheritDoc
	 */
	protected function getMenuSlug(): string
	{
		return self::MENU_SLUG;
	}

	/**
	 * @inheritDoc
	 */
	protected function getViewUri(): string
	{
		return self::VIEW_URI;
	}

	/**
	 * @inheritDoc
	 */
	protected function processAttributes($attr): array
	{
		$attr = (array) $attr;

		$attr['pageTitle'] = $this->getTitle();

		// List all the options here.
		$attr['token'] = get_option('solo_api_token') ?? '';
		$attr['measure'] = get_option('solo_api_measure') ?? '';
		$attr['languages'] = get_option('solo_api_languages') ?? '';
		$attr['currency'] = get_option('solo_api_currency') ?? '';
		$attr['serviceType'] = get_option('solo_api_service_type') ?? '';
		$attr['showTaxes'] = get_option('solo_api_show_taxes') ?? '';
		$attr['invoiceType'] = get_option('solo_api_invoice_type') ?? '';
		$attr['dueDate'] = get_option('solo_api_due_date') ?? '';
		$attr['sendPdf'] = get_option('solo_api_send_pdf') ?? '';
		$attr['sendControl'] = get_option('solo_api_send_control') ?? '';
		$attr['mailGateway'] = get_option('solo_api_mail_gateway') ?? '';
		$attr['message'] = get_option('solo_api_message') ?? '';
		$attr['mailTitle'] = get_option('solo_api_mail_title') ?? '';
		$attr['enablePin'] = get_option('solo_api_enable_pin') ?? '';
		$attr['enableIban'] = get_option('solo_api_enable_iban') ?? '';
		$attr['changeMailFrom'] = get_option('solo_api_change_mail_from') ?? '';

		$availableGateways = $this->gateway->getAvailablePaymentGateways();
		$attr['availableGateways'] = $availableGateways;

		foreach ($availableGateways as $availableGateway => $gatewayOptions) {
			$gatewayID = $gatewayOptions->id;

			$offer = 'solo_api_bill_offer-' . $gatewayID;
			$fiscalization = 'solo_api_fiscalization-' . $gatewayID;
			$paymentType = 'solo_api_payment_type-' . $gatewayID;

			$attr["billOffer{$gatewayID}"] = get_option($offer);
			$attr["fiscalization{$gatewayID}"] = get_option($fiscalization);
			$attr["paymentType{$gatewayID}"] = get_option($paymentType);
		}

		return $attr;
	}

	/**
	 * @inheritDoc
	 */
	protected function getParentMenu(): string
	{
		return self::PARENT_MENU;
	}
}
