<?php

/**
 * Plugin main file starting point
 *
 * @package MadeByDenis\WooSoloApi
 * @link    https://madebydenis.com
 * @since   1.0.0
 *
 * Plugin Name:          Woo Solo Api
 * Plugin URI:           https://madebydenis.com/woo-solo-api
 * Description:          This plugin provides integration of the SOLO API service with WooCommerce.
 * Version:              3.3.0
 * Author:               Denis Žoljom
 * Author URI:           https://madebydenis.com
 * License:              MIT
 * License URI:          https://opensource.org/licenses/MIT
 * Text Domain:          woo-solo-api
 * Domain Path:          /languages
 * WC requires at least: 7.0.0
 * WC tested up to:      8.4.0
 * Requires PHP:         7.4
 */

namespace MadeByDenis\WooSoloApi;

use MadeByDenis\WooSoloApi\Core\PluginFactory;
use MadeByDenis\WooSoloApi\Exception\PluginActivationFailure;

/**
 * Include the autoloader, so we can dynamically include the rest of the classes.
 *
 * @since 2.0.0
 */
$loader = require __DIR__ . '/vendor/autoload.php';

/**
 * Make sure this file is only run from within WordPress.
 *
 * @since 2.0.0
 */
if (!defined('ABSPATH')) {
	$errorMessage = \esc_html__('You cannot access this file outside WordPress.', 'woo-solo-api');

	throw PluginActivationFailure::activationMessage($errorMessage);
}

/**
 * The code that runs during plugin activation.
 *
 * @since 2.0.0
 */
\register_activation_hook(
	__FILE__,
	function () use ($loader) {
		PluginFactory::create($loader->getPrefixesPsr4(), __NAMESPACE__)->activate();
	}
);

/**
 * The code that runs during plugin deactivation.
 *
 * @since 2.0.0
 */
\register_deactivation_hook(
	__FILE__,
	function () use ($loader) {
		PluginFactory::create($loader->getPrefixesPsr4(), __NAMESPACE__)->deactivate();
	}
);

/**
 * Begin plugin execution.
 *
 * @since 2.0.0
 */
PluginFactory::create($loader->getPrefixesPsr4(), __NAMESPACE__)->register();
