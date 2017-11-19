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
 * Plugin URI:        https://madebydenis.com/solo-api-woocommerce-integration
 * Description:       This plugin provides integration of the SOLO API service with WooCommerce.
 * Version:           1.0.0
 * Author:            Denis Žoljom
 * Author URI:        https://madebydenis.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       solo-api-woocommerce-integration
 * Domain Path:       /languages
 */

namespace Solo_Api_Woocommerce_Integration;
use Solo_Api_Woocommerce_Integration\Includes as Includes;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

define( 'SAWI_PLUGIN_VERSION', '1.0.0' );
define( 'SAWI_PLUGIN_NAME', 'solo-api-woocommerce-integration' );

// Include the autoloader so we can dynamically include the rest of the classes.
include_once( 'lib/autoloader.php' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-solo-api-woocommerce-integration-activator.php
 */
function activate_solo_api_woocommerce_integration() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-solo-api-woocommerce-integration-activator.php';
  Includes\Solo_Api_Woocommerce_Integration_Activator::activate();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_solo_api_woocommerce_integration' );

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

  $plugin = new Includes\Solo_Api_Woocommerce_Integration();
  $plugin->run();

}

run_solo_api_woocommerce_integration();
