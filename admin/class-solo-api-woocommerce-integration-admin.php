<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Solo_Api_Woocommerce_Integration\Admin
 */

namespace Solo_Api_Woocommerce_Integration\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and enqueue hooks.
 *
 * @package    Solo_Api_Woocommerce_Integration\Admin
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Solo_Api_Woocommerce_Integration_Admin {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since 1.0.0
   * @param string $plugin_name  The name of this plugin.
   * @param string $version      The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
   * Include file for displayig the plugin settings
   *
   * @since 1.0.0
   */
  public function options_page_render() {
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/solo-api-woocommerce-integration-admin-display.php';
  }

  /**
   * Filter pdf file types on media screen
   *
   * This filter is added so that the user can find their
   * recipes easier.
   *
   * @param array $post_mime_types Available post mime types.
   * @return array $post_mime_types Updated post mime types.
   */
  public function add_pdf_post_mime_type( $post_mime_types ) {
    $post_mime_types['application/pdf'] = array( esc_html__( 'PDFs', 'solo-api-woocommerce-integration' ), esc_html__( 'Manage PDFs', 'solo-api-woocommerce-integration' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' , 'solo-api-woocommerce-integration' ) );

    return $post_mime_types;
  }

  /**
   * Include additional WooCommerce checkout fields for shipping and billing
   *
   * Add personal identification number (PIN) in the checkout fields - OIB in Croatian.
   *
   * @param array $fields Billing and shipping fields.
   * @since 1.0.0
   */
  public function add_pin_field( $fields ) {
    $fields['shipping']['shipping_pin_number'] = array(
        'label'       => esc_html__( 'PIN number', 'solo-api-woocommerce-integration' ),
        'placeholder' => _x( '01234567891', 'placeholder', 'solo-api-woocommerce-integration' ),
        'required'    => false,
        'class'       => array( 'form-row-wide' ),
        'clear'       => true,
    );

    $fields['billing']['billing_pin_number'] = array(
        'label'       => esc_html__( 'PIN number', 'solo-api-woocommerce-integration' ),
        'placeholder' => _x( '01234567891', 'placeholder', 'solo-api-woocommerce-integration' ),
        'required'    => false,
        'class'       => array( 'form-row-wide' ),
        'clear'       => true,
    );

    return $fields;
  }

  /**
   * Include additional WooCommerce checkout fields for shipping and billing
   *
   * Add International Bank Account Number (IBAN) in the checkout fields.
   *
   * @param array $fields Billing and shipping fields.
   * @since 1.0.0
   */
  public function add_iban_field( $fields ) {
    $fields['shipping']['shipping_iban_number'] = array(
        'label'       => esc_html__( 'IBAN number', 'solo-api-woocommerce-integration' ),
        'placeholder' => _x( 'HR12345678901234567890', 'placeholder', 'solo-api-woocommerce-integration' ),
        'required'    => false,
        'class'       => array( 'form-row-wide' ),
        'clear'       => true,
    );

    $fields['billing']['billing_iban_number'] = array(
        'label'       => esc_html__( 'IBAN number', 'solo-api-woocommerce-integration' ),
        'placeholder' => _x( 'HR12345678901234567890', 'placeholder', 'solo-api-woocommerce-integration' ),
        'required'    => false,
        'class'       => array( 'form-row-wide' ),
        'clear'       => true,
    );

    return $fields;
  }

  /**
   * Add the display field on the order edit page
   *
   * @param object $order Order object.
   * @return void
   */
  public function checkout_field_display_admin_order_meta( $order ) {
    $shipping_pin  = get_post_meta( $order->get_id(), '_shipping_pin_number', true );
    $billing_pin   = get_post_meta( $order->get_id(), '_billing_pin_number', true );
    $shipping_iban = get_post_meta( $order->get_id(), '_shipping_iban_number', true );
    $billing_iban  = get_post_meta( $order->get_id(), '_billing_iban_number', true );

    if ( ! empty( $shipping_pin ) ) {
      echo '<p><strong> ' . esc_html__( 'Customer shipping PIN number', 'solo-api-woocommerce-integration' ) . ' :</strong> ' . esc_html( $shipping_pin ) . '</p>';
    }

    if ( ! empty( $billing_pin ) ) {
      echo '<p><strong> ' . esc_html__( 'Customer billing PIN number', 'solo-api-woocommerce-integration' ) . ' :</strong> ' . esc_html( $billing_pin ) . '</p>';
    }

    if ( ! empty( $shipping_iban ) ) {
      echo '<p><strong> ' . esc_html__( 'Customer shipping IBAN number', 'solo-api-woocommerce-integration' ) . ' :</strong> ' . esc_html( $shipping_iban ) . '</p>';
    }

    if ( ! empty( $billing_iban ) ) {
      echo '<p><strong> ' . esc_html__( 'Customer billing IBAN number', 'solo-api-woocommerce-integration' ) . ' :</strong> ' . esc_html( $billing_iban ) . '</p>';
    }
  }

  /**
   * Register plugin settings
   *
   * @since 1.0.0
   */
  public function register_plugin_settings() {
    register_setting( 'solo-api-settings-group', 'solo_api_token' );
    register_setting( 'solo-api-settings-group', 'solo_api_measure' );
    register_setting( 'solo-api-settings-group', 'solo_api_payment_type' );
    register_setting( 'solo-api-settings-group', 'solo_api_languages' );
    register_setting( 'solo-api-settings-group', 'solo_api_currency' );
    register_setting( 'solo-api-settings-group', 'solo_api_bill_offer' );
    register_setting( 'solo-api-settings-group', 'solo_api_service_type' );
    register_setting( 'solo-api-settings-group', 'solo_api_show_taxes' );
    register_setting( 'solo-api-settings-group', 'solo_api_tax_rate' );
    register_setting( 'solo-api-settings-group', 'solo_api_recipe_type' );
    register_setting( 'solo-api-settings-group', 'solo_api_mail_title' );
    register_setting( 'solo-api-settings-group', 'solo_api_message' );
    register_setting( 'solo-api-settings-group', 'solo_api_change_mail_from' );
    register_setting( 'solo-api-settings-group', 'solo_api_enable_pin' );
    register_setting( 'solo-api-settings-group', 'solo_api_enable_iban' );
    register_setting( 'solo-api-settings-group', 'solo_api_currency_rate' );
    register_setting( 'solo-api-settings-group', 'solo_api_fiscalization' );
    register_setting( 'solo-api-settings-group', 'solo_api_due_date' );
  }

  /**
   * Add plugin options page
   *
   * @since  1.0.0
   */
  public function add_plugin_options_page() {
    add_options_page(
      esc_html__( 'Solo API WooCommerce Integration Options', 'solo-api-woocommerce-integration' ),
      esc_html__( 'Solo API Options', 'solo-api-woocommerce-integration' ),
      'manage_options',
      'solo_api_options',
      array( $this, 'options_page_render' )
    );
  }

  /**
   * Add plugin settings link on plugins page
   *
   * @param array $links Plugin action links.
   */
  public function add_action_links( $links ) {
     $links[] = '<a href="' . admin_url( 'options-general.php?page=solo_api_options' ) . '">' . esc_html__( 'SOLO API Settings', 'solo-api-woocommerce-integration' ) . '</a>';
    return $links;
  }

  /**
   * Change mail from name that is send from WordPress
   *
   * @param  string $name Name that is shown.
   * @return string       Changed name.
   */
  function solo_api_mail_from_name( $name ) {
    $new_name = esc_attr( get_option( 'solo_api_change_mail_from' ) );
    if ( ! empty( $new_name ) ) {
      return $new_name;
    } else {
      return $name;
    }
  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/solo-api-woocommerce-integration-admin.css', array(), $this->version, 'all' );
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/solo-api-woocommerce-integration-admin.js', array( 'jquery' ), $this->version, false );
  }
}
