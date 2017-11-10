<?php
/**
 * Fired during plugin activation
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Solo_Api_Woocommerce_Integration
 * @subpackage Solo_Api_Woocommerce_Integration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Solo_Api_Woocommerce_Integration
 * @subpackage Solo_Api_Woocommerce_Integration/includes
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Solo_Api_Woocommerce_Integration_Activator {

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
      
      $error_message = esc_html__( 'You do not have proper authorization to activate a plugin!', 'solo-api-woocommerce-integration' );
      die( $error_message );
    }
     
    if ( ! class_exists( 'WooCommerce' ) ) {
      // Deactivate the plugin.
      deactivate_plugins( plugin_basename( __FILE__ ) );
      // Throw an error in the WordPress admin console.
      $error_message = esc_html__( 'This plugin requires ', 'solo-api-woocommerce-integration' ) . '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '">WooCommerce</a>' . esc_html__( ' plugin to be active!', 'solo-api-woocommerce-integration' );
      die( $error_message );
    }
  }

}
