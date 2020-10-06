<?php

/**
 * File holding PluginsPage class
 *
 * @package MadeByDenis\WooSoloApi\Admin
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Admin;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * Adds action link to the plugin in the plugin screen
 *
 * @package MadeByDenis\WooSoloApi\Admin
 * @since 2.0.0
 */
class PluginsPage implements Registrable
{

	public const PLUGIN_LINK_HOOK = 'plugin_action_links_woo-solo-api/woo-solo-api.php';

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action(self::PLUGIN_LINK_HOOK, [$this, 'addActionLink']);
	}

	/**
	 * Add plugin settings link on plugins page
	 *
	 * @param array $links Plugin action links.
	 *
	 * @return array Updated links that will be shown on the plugins install page.
	 */
	public function addActionLink(array $links): array
	{
		$links[] = '<a href="' . admin_url('admin.php?page=solo_api_options') . '">' . esc_html__('SOLO API Settings', 'woo-solo-api') . '</a>';

		return $links;
	}
}
