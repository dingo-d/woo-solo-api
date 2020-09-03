<?php

/**
 *  Base abstract class for admin submenus
 *
 * @package MadeByDenis\WooSoloApi\AdminMenus
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Admin\AdminMenus;

/**
 * Admin submenu abstract class
 *
 * This abstract class can be extended to add new admin submenus.
 *
 * @package MadeByDenis\WooSoloApi\AdminMenus
 * @since 2.0.0
 */
abstract class AdminSubmenu extends AdminMenu
{
	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('admin_menu', [$this, 'registerSubmenu']);
	}

	/**
	 * Admin submenu registration callback
	 *
	 * @param string $context Empty context.
	 */
	public function registerSubmenu(string $context): void
	{
		add_submenu_page(
			$this->getParentMenu(),
			$this->getTitle(),
			$this->getMenuTitle(),
			$this->getCapability(),
			$this->getMenuSlug(),
			[$this, 'processAdminSubmenu']
		);
	}

	/**
	 * Process the admin submenu attributes and prepare rendering.
	 *
	 * The echo doesn't need to be escaped since it's escaped
	 * in the render method.
	 *
	 * @param array|string $attr Attributes as passed to the admin menu.
	 *
	 * @return void The rendered content needs to be echoed.
	 */
	public function processAdminSubmenu($attr): void
	{
		$attr = $this->processAttributes($attr);
		$attr['adminMenuId'] = $this->getMenuSlug();
		$attr['nonceField'] = $this->renderNonce();

		echo $this->render((array)$attr); // phpcs:ignore
	}

	/**
	 * Get the slug of the parent menu.
	 *
	 * @return string The slug name for the parent menu (or the file name of a standard WordPress admin page.
	 */
	abstract protected function getParentMenu(): string;
}
