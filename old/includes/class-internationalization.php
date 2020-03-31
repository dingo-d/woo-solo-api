<?php
/**
 * Define the internationalization functionality
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Woo_Solo_Api\Includes
 */

namespace Woo_Solo_Api\Includes;

/**
 * Define the internationalization functionality class.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Solo_Api\Includes
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Internationalization {

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {
    load_plugin_textdomain(
      'woo-solo-api',
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );
  }
}
