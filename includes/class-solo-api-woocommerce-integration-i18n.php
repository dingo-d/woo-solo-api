<?php
/**
 * Define the internationalization functionality
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Solo_Api_Woocommerce_Integration\Includes
 */

namespace Solo_Api_Woocommerce_Integration\Includes;

/**
 * Define the internationalization functionality class.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Solo_Api_Woocommerce_Integration\Includes
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Solo_Api_Woocommerce_Integration_i18n {

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {

    load_plugin_textdomain(
      'solo-api-woocommerce-integration',
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );

  }



}
