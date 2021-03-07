<?php

/**
 * File holding PluginActivationCheck class
 *
 * @package MadeByDenis\WooSoloApi\DB
 * @since 2.1.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Database;

/**
 * Checks if the WooCommerce plugin is in the active plugin list.
 *
 * We need to do a raw SQL because all the methods available to us won't be available to us until
 * admin_init hook, and we are using `plugins_loaded` hook to run all the classes. That means that our code, which
 * depends on WooCommerce to run, will throw fatal errors if WooCommerce is deactivated before this plugin.
 *
 * @package MadeByDenis\WooSoloApi\DB
 * @since 2.1.0
 */
class PluginActivationCheck
{
	/**
	 * Helper method that checks if the WooCommerce plugin is present in the
	 * active_plugins list in the options table
	 *
	 * @return bool True if plugin is in the list, false if it's not.
	 */
	public static function isWooCommerceActive(): bool
	{
		global $wpdb;

		$activePlugins = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT option_value
				FROM $wpdb->options
				WHERE option_name = %s",
				'active_plugins'
			)
		);

		$pluginsArray = array_flip(unserialize($activePlugins));

		if (isset($pluginsArray['woocommerce/woocommerce.php'])) {
			return true;
		}

		return false;
	}

	/**
	 * Helper method that will remove a plugin from the active_plugins list in the options table
	 *
	 * @return void
	 */
	public static function deactivatePlugin()
	{
		global $wpdb;

		$activePluginsList = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT option_value
				FROM $wpdb->options
				WHERE option_name = %s",
				'active_plugins'
			)
		);

		$pluginsArray = array_flip(unserialize($activePluginsList));
		unset($pluginsArray['woo-solo-api/woo-solo-api.php']);

		$updatedPluginsArray = serialize(array_values(array_flip($pluginsArray)));

		$wpdb->update(
			$wpdb->options,
			['option_value' => $updatedPluginsArray],
			['option_name' => 'active_plugins']
		);
	}
}
