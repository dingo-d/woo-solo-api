<?php

/**
 * Base abstract class for admin menu implementations
 *
 * @package MadeByDenis\WooSoloApi\AdminMenus
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\AdminMenus;

use Exception;
use MadeByDenis\WooSoloApi\Core\Registrable;
use MadeByDenis\WooSoloApi\Core\Renderable;
use MadeByDenis\WooSoloApi\View\EscapedView;
use MadeByDenis\WooSoloApi\View\TemplatedView;

/**
 * Class AdminMenu
 *
 * This abstract class can be extended to add new admin menus.
 * This is the template method design pattern, used to avoid code duplication.
 *
 * @package MadeByDenis\WooSoloApi\AdminMenus
 * @since 2.0.0
 */
abstract class AdminMenu implements Registrable, Renderable
{
	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('admin_menu', [$this, 'registerAdminMenu']);
	}

	/**
	 * Admin menu registration callback
	 *
	 * @param string $context Empty context
	 */
	public function registerAdminMenu(string $context): void
	{
		add_menu_page(
			$this->getTitle(),
			$this->getMenuTitle(),
			$this->getCapability(),
			$this->getMenuSlug(),
			[$this, 'processAdminMenu'],
			$this->getIcon(),
			$this->getPosition()
		);
	}

	/**
	 * Process the admin menu attributes and prepare rendering.
	 *
	 * The echo doesn't need to be escaped since it's escaped
	 * in the render method.
	 *
	 * @param array|string $attr Attributes as passed to the admin menu.
	 *
	 * @return void The rendered content needs to be echoed.
	 */
	public function processAdminMenu($attr): void
	{
		$attr = $this->processAttributes($attr);
		$attr['adminMenuId'] = $this->getMenuSlug();
		$attr['nonceField'] = $this->renderNonce();

		echo $this->render((array)$attr); // phpcs:ignore
	}

	/**
	 * Render method to display the admin menu view
	 *
	 * @param array $context Passed attributes to the view.
	 *
	 * @return string Rendered view or error message if path is missing.
	 */
	public function render(array $context = []): string
	{
		try {
			$view = new EscapedView(
				new TemplatedView($this->getViewUri())
			);

			return $view->render($context);
		} catch (Exception $exception) {
			// Don't let exceptions bubble up. Just render the exception message into the admin menu.
			return sprintf(
				'<pre>%s</pre>',
				$exception->getMessage()
			);
		}
	}

	/**
	 * Get the title to use for the admin page.
	 *
	 * @return string The text to be displayed in the title tags of the page when the menu is selected.
	 */
	abstract protected function getTitle(): string;

	/**
	 * Get the menu title to use for the admin menu.
	 *
	 * @return string The text to be used for the menu.
	 */
	abstract protected function getMenuTitle(): string;

	/**
	 * Get the capability required for this menu to be displayed.
	 *
	 * @return string The capability required for this menu to be displayed to the user.
	 */
	abstract protected function getCapability(): string;

	/**
	 * Get the menu slug.
	 *
	 * @return string The slug name to refer to this menu by.
	 *                Should be unique for this menu page and only include
	 *                lowercase alphanumeric, dashes, and underscores characters
	 *                to be compatible with sanitize_key().
	 */
	abstract protected function getMenuSlug(): string;

	/**
	 * Get the View URI to use for rendering the admin menu.
	 *
	 * @return string View URI.
	 */
	abstract protected function getViewUri(): string;

	/**
	 * Process the admin menu attributes.
	 *
	 * @param array|string $attr Raw admin menu attributes passed into the
	 *                           admin menu function.
	 *
	 * @return array Processed admin menu attributes.
	 */
	abstract protected function processAttributes($attr): array;

	/**
	 * Render the nonce.
	 *
	 * @return string Hidden field with a nonce.
	 */
	protected function renderNonce(): string
	{
		return wp_nonce_field($this->getNonceAction(), $this->getNonceName(), true, false);
	}

	/**
	 * Get the action of the nonce to use.
	 *
	 * @return string Action of the nonce.
	 */
	protected function getNonceAction(): string
	{
		return "{$this->getMenuSlug()}_action";
	}

	/**
	 * Get the name of the nonce to use.
	 *
	 * @return string Name of the nonce.
	 */
	protected function getNonceName(): string
	{
		return "{$this->getMenuSlug()}_nonce";
	}

	/**
	 * Get the URL to the icon to be used for this menu
	 *
	 * @return string The URL to the icon to be used for this menu.
	 *                * Pass a base64-encoded SVG using a data URI, which will be colored to match
	 *                  the color scheme. This should begin with 'data:image/svg+xml;base64,'.
	 *                * Pass the name of a Dashicons helper class to use a font icon,
	 *                  e.g. 'dashicons-chart-pie'.
	 *                * Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
	 */
	protected function getIcon(): string
	{
		return 'none';
	}

	/**
	 * Get the position of the menu.
	 *
	 * 5   - below Posts
	 * 10  - below Media
	 * 15  - below Links
	 * 20  - below Pages
	 * 25  - below Comments
	 * 60  - below first separator
	 * 65  - below Plugins
	 * 70  - below Users
	 * 75  - below Tools
	 * 80  - below Settings
	 * 100 - below second separator
	 *
	 * @return int Number that indicates the position of the menu.
	 */
	protected function getPosition(): int
	{
		return 100;
	}
}
