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

use function add_action;

/**
 * Internationalization
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
		add_action('plugins_loaded', [$this, 'loadPluginTextDomain'], 20);
	}

	/**
	 * Callback that will load plugin text domain
	 */
	public function loadPluginTextDomain(): void
	{
		load_plugin_textdomain(
			'woo-solo-api',
			false,
			dirname(__DIR__, 2) . '/languages'
		);
	}
}
