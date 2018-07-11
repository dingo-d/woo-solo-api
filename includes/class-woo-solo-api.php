<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://madebydenis.com
 * @since      1.8.1 Removed unnecessary methods, added contstants.
 * @since      1.0.0
 *
 * @package    Woo_Solo_Api\Includes
 */

namespace Woo_Solo_Api\Includes;

use Woo_Solo_Api\Admin as Admin;

/**
 * The core plugin class.
 *
 * @since      1.8.1 Removed unnecessary methods, added contstants.
 * @since      1.0.0
 * @package    Woo_Solo_Api\Includes
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Woo_Solo_Api {
  /**
   * Plugin name
   *
   * @since 1.8.1
   */
  const PLUGIN_NAME = 'woo-solo-api';

  /**
   * Plugin version
   *
   * @since 1.8.1
   */
  const PLUGIN_VERSION = '1.9.2';

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {
    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
  }
  /**
   * Load the required dependencies for this plugin.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {
    $this->loader = new Loader();
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Internationalization class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {
    $plugin_i18n = new Internationalization();

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.9.0 Added a check if the email order hook was called. Added languages
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {
    $api_helpers  = new Admin\Helpers( self::PLUGIN_NAME );
    $api_request  = new Admin\Request( $api_helpers );
    $plugin_admin = new Admin\Admin( self::PLUGIN_NAME, self::PLUGIN_VERSION, $api_helpers );

    $this->loader->add_action( 'woocommerce_email_order_details', $api_request, 'solo_api_send_api_request', 15, 4 );

    $this->loader->add_action( 'init', $api_helpers, 'get_exchange_rates' );

    if ( ! is_admin() ) {
      $this->loader->add_filter( 'locale', $plugin_admin, 'set_my_locale' );
    }

    $this->loader->add_filter( 'post_mime_types', $plugin_admin, 'add_pdf_post_mime_type' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    $this->loader->add_action( 'admin_init', $plugin_admin, 'register_plugin_settings' );
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_options_page', 99 );
    $this->loader->add_action( 'plugin_action_links_woo-solo-api/woo-solo-api.php', $plugin_admin, 'add_action_links' );
    $this->loader->add_action( 'wp_mail_from_name', $plugin_admin, 'solo_api_mail_from_name' );

    if ( get_option( 'solo_api_enable_pin' ) ) {
      $this->loader->add_action( 'woocommerce_checkout_fields', $plugin_admin, 'add_pin_field' );
    }

    if ( get_option( 'solo_api_enable_iban' ) ) {
      $this->loader->add_action( 'woocommerce_checkout_fields', $plugin_admin, 'add_iban_field' );
    }

    if ( get_option( 'solo_api_enable_pin' ) || get_option( 'solo_api_enable_iban' ) ) {
      $this->loader->add_action( 'woocommerce_admin_order_data_after_shipping_address', $plugin_admin, 'checkout_field_display_admin_order_meta' );
    }

    $this->loader->add_action( 'wp_ajax_get_solo_data', $plugin_admin, 'get_solo_data' );
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }
}
