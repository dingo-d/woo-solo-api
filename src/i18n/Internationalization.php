<?php

/**
 * File holding Internationalization class
 *
 * @package MadeByDenis\WooSoloApi\i18n
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\i18n;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * Internationalization class
 *
 * @package MadeByDenis\WooSoloApi\i18n
 * @since 2.0.0
 */
class Internationalization implements Registrable
{

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('plugins_loaded', [$this, 'loadPluginTextDomain']);
	}

	/**
	 * Callback that will load plugin text domain
	 */
	public function loadPluginTextDomain()
	{
		load_plugin_textdomain(
			'woo-solo-api',
			false,
			dirname(__FILE__, 3) . '/languages/'
		);
	}
}
