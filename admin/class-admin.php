<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Woo_Solo_Api\Admin
 */

namespace Woo_Solo_Api\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and enqueue hooks.
 *
 * @package    Woo_Solo_Api\Admin
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Admin {

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
    $this->version     = $version;
  }

  /**
   * Include file for displayig the plugin settings
   *
   * @since 1.0.0
   */
  public function options_page_render() {
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-display.php';
  }

  /**
   * Filter pdf file types on media screen
   *
   * This filter is added so that the user can find their
   * invoices easier.
   *
   * @param array $post_mime_types Available post mime types.
   * @return array $post_mime_types Updated post mime types.
   */
  public function add_pdf_post_mime_type( $post_mime_types ) {
    $post_mime_types['application/pdf'] = array( esc_html__( 'PDFs', 'woo-solo-api' ), esc_html__( 'Manage PDFs', 'woo-solo-api' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>', 'woo-solo-api' ) );

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
        'label'       => esc_html__( 'PIN number', 'woo-solo-api' ),
        'placeholder' => _x( '01234567891', 'placeholder', 'woo-solo-api' ),
        'required'    => false,
        'class'       => array( 'form-row-wide' ),
        'clear'       => true,
    );

    $fields['billing']['billing_pin_number'] = array(
        'label'       => esc_html__( 'PIN number', 'woo-solo-api' ),
        'placeholder' => _x( '01234567891', 'placeholder', 'woo-solo-api' ),
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
        'label'       => esc_html__( 'IBAN number', 'woo-solo-api' ),
        'placeholder' => _x( 'HR12345678901234567890', 'placeholder', 'woo-solo-api' ),
        'required'    => false,
        'class'       => array( 'form-row-wide' ),
        'clear'       => true,
    );

    $fields['billing']['billing_iban_number'] = array(
        'label'       => esc_html__( 'IBAN number', 'woo-solo-api' ),
        'placeholder' => _x( 'HR12345678901234567890', 'placeholder', 'woo-solo-api' ),
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
      echo '<p><strong> ' . esc_html__( 'Customer shipping PIN number', 'woo-solo-api' ) . ' :</strong> ' . esc_html( $shipping_pin ) . '</p>';
    }

    if ( ! empty( $billing_pin ) ) {
      echo '<p><strong> ' . esc_html__( 'Customer billing PIN number', 'woo-solo-api' ) . ' :</strong> ' . esc_html( $billing_pin ) . '</p>';
    }

    if ( ! empty( $shipping_iban ) ) {
      echo '<p><strong> ' . esc_html__( 'Customer shipping IBAN number', 'woo-solo-api' ) . ' :</strong> ' . esc_html( $shipping_iban ) . '</p>';
    }

    if ( ! empty( $billing_iban ) ) {
      echo '<p><strong> ' . esc_html__( 'Customer billing IBAN number', 'woo-solo-api' ) . ' :</strong> ' . esc_html( $billing_iban ) . '</p>';
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
    register_setting( 'solo-api-settings-group', 'solo_api_service_type' );
    register_setting( 'solo-api-settings-group', 'solo_api_show_taxes' );
    register_setting( 'solo-api-settings-group', 'solo_api_invoice_type' );
    register_setting( 'solo-api-settings-group', 'solo_api_mail_title' );
    register_setting( 'solo-api-settings-group', 'solo_api_message' );
    register_setting( 'solo-api-settings-group', 'solo_api_change_mail_from' );
    register_setting( 'solo-api-settings-group', 'solo_api_enable_pin' );
    register_setting( 'solo-api-settings-group', 'solo_api_enable_iban' );
    register_setting( 'solo-api-settings-group', 'solo_api_currency_rate' );
    register_setting( 'solo-api-settings-group', 'solo_api_due_date' );
    register_setting( 'solo-api-settings-group', 'solo_api_mail_gateway' );
    register_setting( 'solo-api-settings-group', 'solo_api_send_pdf' );

    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
    foreach ( $available_gateways as $gateway_sett => $gateway_val ) {
      register_setting( 'solo-api-settings-group', 'solo_api_bill_offer-' . esc_attr( $gateway_val->id ) );
      register_setting( 'solo-api-settings-group', 'solo_api_fiscalization-' . esc_attr( $gateway_val->id ) );
    }
  }

  /**
   * Add plugin options page
   *
   * @since  1.0.0
   */
  public function add_plugin_options_page() {
    add_options_page(
      esc_html__( 'Woo Solo Api Options', 'woo-solo-api' ),
      esc_html__( 'Solo API Options', 'woo-solo-api' ),
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
     $links[] = '<a href="' . admin_url( 'options-general.php?page=solo_api_options' ) . '">' . esc_html__( 'SOLO API Settings', 'woo-solo-api' ) . '</a>';
    return $links;
  }

  /**
   * Change mail from name that is send from WordPress
   *
   * @param  string $name Name that is shown.
   * @return string       Changed name.
   */
  public function solo_api_mail_from_name( $name ) {
    $new_name = esc_attr( get_option( 'solo_api_change_mail_from' ) );
    if ( ! empty( $new_name ) && $new_name !== '' ) {
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
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-solo-api-admin.css', array(), $this->version, 'all' );
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-solo-api-admin.js', array( 'jquery' ), $this->version, false );
  }
}
