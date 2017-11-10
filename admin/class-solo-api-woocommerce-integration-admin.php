<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Solo_Api_Woocommerce_Integration
 * @subpackage Solo_Api_Woocommerce_Integration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Solo_Api_Woocommerce_Integration
 * @subpackage Solo_Api_Woocommerce_Integration/admin
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
      'clear'       => true
    );

    $fields['billing']['billing_pin_number'] = array(
      'label'       => esc_html__( 'PIN number', 'solo-api-woocommerce-integration' ),
      'placeholder' => _x( '01234567891', 'placeholder', 'solo-api-woocommerce-integration' ),
      'required'    => false,
      'class'       => array( 'form-row-wide' ),
      'clear'       => true
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
    $shipping_pin = get_post_meta( $order->get_id(), '_shipping_pin_number', true );
    $billing_pin  = get_post_meta( $order->get_id(), '_billing_pin_number', true );
    
    if ( ! empty( $shipping_pin ) ) {
      echo '<p><strong> ' . esc_html__( 'Customer shipping PIN number', 'solo-api-woocommerce-integration' ) . ' :</strong> ' . esc_html( $shipping_pin ) . '</p>';    
    }

    if ( ! empty( $billing_pin ) ) {
      echo '<p><strong> ' . esc_html__( 'Customer billing PIN number', 'solo-api-woocommerce-integration' ) . ' :</strong> ' . esc_html( $billing_pin ) . '</p>';    
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
    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Solo_Api_Woocommerce_Integration_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Solo_Api_Woocommerce_Integration_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/solo-api-woocommerce-integration-admin.css', array(), $this->version, 'all' );
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Plugin_Name_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Plugin_Name_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/solo-api-woocommerce-integration-admin.js', array( 'jquery' ), $this->version, false );
  }

  /**
   * Solo API create an invoice on successful WooCommerce order
   *
   * @param int $order_id ID of the order.
   * @since 1.0.0
   */
  public function solo_api_send_api_request( $order_id ) {

    $method_executed = get_transient( 'method_executed' );
    if ( $method_executed === false ) {
      // It wasn't there, so regenerate the data and save the transient.
      $method_executed = true;
      set_transient( 'method_executed', $method_executed, 10 );
    } else {
      return;
    }

    $order = wc_get_order( $order_id );
    $order_data = $order->get_data(); // The Order data.

    // Options.
    $solo_api_token        = get_option( 'solo_api_token' );
    $solo_api_measure      = get_option( 'solo_api_measure' );
    $solo_api_payment_type = get_option( 'solo_api_payment_type' );
    $solo_api_languages    = get_option( 'solo_api_languages' );
    $solo_api_currency     = get_option( 'solo_api_currency' );
    $solo_api_bill_offer   = get_option( 'solo_api_bill_offer' );
    $solo_api_service_type = get_option( 'solo_api_service_type' );
    $solo_api_show_taxes   = get_option( 'solo_api_show_taxes' );
    $solo_api_tax_rate     = get_option( 'solo_api_tax_rate' );
    $solo_api_recipe_type  = get_option( 'solo_api_recipe_type' );
    $solo_api_mail_title   = get_option( 'solo_api_mail_title' );
    $solo_api_message      = get_option( 'solo_api_message' );

    $url = 'https://api.solo.com.hr/' . $solo_api_bill_offer;

    // OVISNO O RAČUNU ILI PONUDI MAKNUTI NEKE STVARI IZ API CALLA!

    // Check if billing or shipping.
    if ( ( isset( $order_data['billing']['first_name'] ) && $order_data['billing']['first_name'] !== '' ) ) {
      $field = 'billing';
    } else {
      $field = 'shipping';
    }

    $first_name = ( isset( $order_data[ $field ]['first_name'] ) && $order_data[ $field ]['first_name'] !== '' ) ? $order_data[ $field ]['first_name'] : '';
    $last_name  = ( isset( $order_data[ $field ]['last_name'] ) && $order_data[ $field ]['last_name'] !== '' ) ? $order_data[ $field ]['last_name'] : '';
    $address_1  = ( isset( $order_data[ $field ]['address_1'] ) && $order_data[ $field ]['address_1'] !== '' ) ? $order_data[ $field ]['address_1'] : '';
    $address_2  = ( isset( $order_data[ $field ]['address_2'] ) && $order_data[ $field ]['address_2'] !== '' ) ? $order_data[ $field ]['address_2'] : '';
    $city       = ( isset( $order_data[ $field ]['city'] ) && $order_data[ $field ]['city'] !== '' ) ? $order_data[ $field ]['city'] : '';
    $state      = ( isset( $order_data[ $field ]['state'] ) && $order_data[ $field ]['state'] !== '' ) ? $order_data[ $field ]['state'] : '';
    $country    = ( isset( $order_data[ $field ]['country'] ) && $order_data[ $field ]['country'] !== '' ) ? $order_data[ $field ]['country'] : '';
    $postcode   = ( isset( $order_data[ $field ]['postcode'] ) && $order_data[ $field ]['postcode'] !== '' ) ? $order_data[ $field ]['postcode'] : '';
    $email      = ( isset( $order_data[ $field ]['email'] ) && $order_data[ $field ]['email'] !== '' ) ? $order_data[ $field ]['email'] : '';
    $phone      = ( isset( $order_data[ $field ]['phone'] ) && $order_data[ $field ]['phone'] !== '' ) ? $order_data[ $field ]['phone'] : '';
    $company    = ( isset( $order_data[ $field ]['company'] ) && $order_data[ $field ]['company'] !== '' ) ? $order_data[ $field ]['company'] : '';

    $order_buyer = $first_name . ' ' . $last_name;

    if ( '' !== $address_2 ) {
      if ( '' !== $state ) {
        $order_address = $address_1 . ',' . $address_2 . ', ' . $city . ' ' . $state . ' ' . $postcode . ', ' . $country;
      } else {
        $order_address = $address_1 . ',' . $address_2 . ', ' . $city . ' ' . $postcode . ', ' . $country;
      }
    } else {
      if ( '' !== $state ) {
        $order_address = $address_1 . ',' . $city . ' ' . $state . ' ' . $postcode . ', ' . $country;
      } else {
        $order_address = $address_1 . ',' . $city . ' ' . $postcode . ', ' . $country;
      }
    }

    $post_url = $url . '?token=' . $solo_api_token . '&tip_usluge=' . $solo_api_service_type . '&prikazi_porez=' . $solo_api_show_taxes . '&tip_racuna=' . $solo_api_recipe_type . '&kupac_naziv=' . $order_buyer . '&kupac_adresa=' . $order_address;

    // Individual order.
    $item_no = 1;

    foreach ( array_unique( $order->get_items() ) as $item_key => $item_values ) {
      $item_name = $item_values->get_name(); // Name of the product.
      $item_data = $item_values->get_data();

      $product_name = $item_data['name'];
      $quantity     = (double) ( $item_data['quantity'] !== 0 ) ? $item_data['quantity'] : 1;
      $single_price = $item_data['total'] / $quantity;
      $line_total   = number_format( $single_price, 2, ',', '.' );

      $post_url .= '&usluga=' . $item_no . '&opis_usluge_' . $item_no . '=' . $product_name . '&jed_mjera_' . $item_no . '=' . $solo_api_measure . '&cijena_' . $item_no . '=' . $line_total . '&kolicina_' . $item_no . '=' . $quantity . '&popust_' . $item_no . '=0&porez_stopa_' . $item_no . '=' . $solo_api_tax_rate;

      $item_no++;
    }

    // Shipping.
    if ( (int) $order->get_total_shipping() > 0 ) {
      $shipping_price = number_format( (int) $order->get_total_shipping(), 2, ',', '.' );
      $post_url .= '&usluga=' . $item_no . '&opis_usluge_' . $item_no . '=' . esc_html__( 'Shipping fee', 'solo-api-woocommerce-integration' ) . '&jed_mjera_' . $item_no . '=' . $solo_api_measure . '&cijena_' . $item_no . '=' . $shipping_price . '&kolicina_' . $item_no . '=1&popust_' . $item_no . '=0&porez_stopa_' . $item_no . '=' . $solo_api_tax_rate;
    }

    $customer_note = ( isset( $order->data['customer_note'] ) && '' !== $order->data['customer_note'] ) ? $order->data['customer_note'] : '';

    $due_date = date( 'Y-m-d', strtotime( '+1 week' ) );

    $post_url .= '&nacin_placanja=' . $solo_api_payment_type . '&rok_placanja=' . $due_date . '&napomene=' . $customer_note . '&fiskalizacija=0';
    // FISKALIZACIJA OPCIJA.

    error_log( print_r( 'URL', true) );
    error_log( print_r( $post_url, true) );

    $method_executed = false;

    /**
     * For more info go to: https://solo.com.hr/api-dokumentacija/izrada-racuna
     */
    $response = wp_remote_post( $post_url );

    if ( is_wp_error( $response ) ) {
      $error_code = wp_remote_retrieve_response_code( $response );
      $error_message = wp_remote_retrieve_response_message( $response );
      return new WP_Error( $error_code, $error_message );
    }

    if ( $order_data['payment_method'] === 'bacs' ) { // OVO TREBA DODATI KAO OPCIJU U SETTINGSIMA!

      $body = json_decode( $response['body'] );

      if ( $body->status !== 0 ) {
        $error_code = $body->status;
        $error_message = $body->message;
        return new WP_Error( $error_code, $error_message );
      }

      // Create pdf.
      $pdf_link = esc_url( $body->ponuda->pdf );
      $pdf_name = esc_html( $body->ponuda->broj_ponude );

      $pdf_get = wp_remote_get( $pdf_link );

      if ( is_wp_error( $pdf_get ) ) {
        $error_code = wp_remote_retrieve_response_code( $pdf_get );
        $error_message = wp_remote_retrieve_response_message( $pdf_get );
        return new WP_Error( $error_code, $error_message );
      }

      $pdf_contents = $pdf_get['body'];

      $pdf_name = 'ponuda-' . $pdf_name . '.pdf';

      $upload_dir = wp_upload_dir();

      $new_dir = $upload_dir['basedir'] . '/ponude/' . date( 'Y' ) . '/' . date( 'm' );

      if ( ! file_exists( $new_dir ) ) {
        wp_mkdir_p( $new_dir );
      }

      $attachment = $new_dir . '/' . $pdf_name;

      global $wp_filesystem;
      if ( empty( $wp_filesystem ) ) {
        require_once( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();
      }

      $wp_filesystem->put_contents(
        $attachment,
        $pdf_contents,
        FS_CHMOD_FILE // predefined mode settings for WP files.
      );

      // Send mail with the attachment.
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

      $message = $solo_api_message;

      wp_mail( $email, $solo_api_mail_title, $message, $headers, array( $attachment ) );
    }

  }

}
