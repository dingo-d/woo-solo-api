<?php

/**
 * Render options submenu page
 *
 * @package MadeByDenis\WooSoloApi\AdminMenus
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Admin\AdminMenus;

/**
 * Add an options submenu inside WooCommerce menu
 *
 * @package MadeByDenis\WooSoloApi\AdminMenus
 * @since 2.0.0
 */
final class OptionsSubmenu extends AdminSubmenu
{
	public const CAPABILITY = 'manage_options';

	public const MENU_SLUG = 'solo_api_options';

	public const PARENT_MENU = 'woocommerce';

	public const WOO_PAGE_ID = 'woocommerce_page_solo_api_options';

	public const VIEW_URI = 'views/options-page';

	/**
	 * @inheritDoc
	 */
	public function registerSubmenu(string $context): void
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
		return esc_html__('Woo Solo Api Options', 'woo-solo-api');
	}

	/**
	 * @inheritDoc
	 */
	protected function getMenuTitle(): string
	{
		return esc_html__('Solo API Options', 'woo-solo-api');
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
