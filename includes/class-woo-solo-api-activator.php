<?php
/**
 * Fired during plugin activation
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Woo_Solo_Api\Includes
 */

namespace Woo_Solo_Api\Includes;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_Solo_Api\Includes
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Woo_Solo_Api_Activator {

  /**
   * Activation function
   *
   * @since    1.0.0
   */
  public static function activate() {
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
      include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    if ( ! current_user_can( 'activate_plugins' ) ) {
      // Deactivate the plugin.
      deactivate_plugins( plugin_basename( __FILE__ ) );

      $error_message = __( 'You do not have proper authorization to activate a plugin!', 'woo-solo-api' );
      die( esc_html( $error_message ) );
    }

    if ( ! class_exists( 'WooCommerce' ) ) {
      // Deactivate the plugin.
      deactivate_plugins( plugin_basename( __FILE__ ) );
      // Throw an error in the WordPress admin console.
      $error_message = __( 'This plugin requires ', 'woo-solo-api' ) . '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '">WooCommerce</a>' . __( ' plugin to be active!', 'woo-solo-api' );
      die( wp_kses_post( $error_message ) );
    }
  }

}
