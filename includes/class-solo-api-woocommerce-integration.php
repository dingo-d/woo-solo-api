<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Solo_Api_Woocommerce_Integration\Includes
 */

namespace Solo_Api_Woocommerce_Integration\Includes;
use Solo_Api_Woocommerce_Integration\Admin as Admin;

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Solo_Api_Woocommerce_Integration\Includes
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Solo_Api_Woocommerce_Integration {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Solo_Api_Woocommerce_Integration_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

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
    if ( defined( 'SAWI_PLUGIN_VERSION' ) ) {
      $this->version = SAWI_PLUGIN_VERSION;
    } else {
      $this->version = '1.0.0';
    }

    if ( defined( 'SAWI_PLUGIN_NAME' ) ) {
      $this->plugin_name = SAWI_PLUGIN_NAME;
    } else {
      $this->plugin_name = 'solo-api-woocommerce-integration';
    }

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
    $this->loader = new Solo_Api_Woocommerce_Integration_Loader();
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Solo_Api_Woocommerce_Integration_I18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {
    $plugin_i18n = new Solo_Api_Woocommerce_Integration_I18n();

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {
    $plugin_admin = new Admin\Solo_Api_Woocommerce_Integration_Admin( $this->get_plugin_name(), $this->get_version() );
    $plugin_solo_api_request = new Admin\Solo_Api_Woocommerce_Integration_Request( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'woocommerce_email_order_details', $plugin_solo_api_request, 'solo_api_send_api_request' );
    $this->loader->add_filter( 'post_mime_types', $plugin_admin, 'add_pdf_post_mime_type' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    $this->loader->add_action( 'admin_init', $plugin_admin, 'register_plugin_settings' );
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_options_page' );
    $this->loader->add_action( 'plugin_action_links_solo-api-woocommerce-integration/solo-api-woocommerce-integration.php', $plugin_admin, 'add_action_links' );
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
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Solo_Api_Woocommerce_Integration_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

}
