<?php

/**
 * File holding Internationalization class
 *
 * @since
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\i18n;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * Internationalization class
 *
 * @since
 */
class Internationalization implements Registrable
{

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('plugins_loaded', [$this, 'loadPluginTextdomain']);
	}

	public function loadPluginTextdomain()
	{
		load_plugin_textdomain(
			'woo-solo-api',
			false,
			dirname(__FILE__, 3) . '/languages/'
		);
	}
}
