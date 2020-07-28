<?php

/**
 * File holding PluginsPage class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Admin
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Admin;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * PluginsPage class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Admin
 */
class PluginsPage implements Registrable
{

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('plugin_action_links_woo-solo-api/woo-solo-api.php', [$this, 'addActionLink']);
	}

	/**
	 * Add plugin settings link on plugins page
	 *
	 * @param array $links Plugin action links.
	 * @return array Updated links that will be shown on the plugins install page.
	 */
	public function addActionLink(array $links): array
	{
		$links[] = '<a href="' . admin_url('admin.php?page=solo_api_options') . '">' . esc_html__('SOLO API Settings', 'woo-solo-api') . '</a>';

		return $links;
	}
}
