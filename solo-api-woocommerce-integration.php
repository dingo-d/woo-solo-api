<?php
/**
 *
 * Plugin main file
 *
 * @link              https://madebydenis.com
 * @since             1.0.0
 * @package           Solo_Api_Woocommerce_Integration
 *
 * Plugin Name:       Solo API WooCommerce Integration
 * Plugin URI:        https://madebydenis/solo-api-woocommerce-integration
 * Description:       This plugin provides integration with the SOLO API service with WooCommerce.
 * Version:           1.0.0
 * Author:            Denis Žoljom
 * Author URI:        https://madebydenis.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       solo-api-woocommerce-integration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

define( 'PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-solo-api-woocommerce-integration-activator.php
 */
function activate_solo_api_woocommerce_integration() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-solo-api-woocommerce-integration-activator.php';
  Solo_Api_Woocommerce_Integration_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_solo_api_woocommerce_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-solo-api-woocommerce-integration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_solo_api_woocommerce_integration() {

  $plugin = new Solo_Api_Woocommerce_Integration();
  $plugin->run();

}
run_solo_api_woocommerce_integration();
