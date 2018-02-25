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
 * Main api request class
 *
 * @package    Woo_Solo_Api\Admin
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 *
 * @since      1.2  Added bill type check in the request method
 * @since      1.0.0
 */
class Request {

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
   * Solo API create an invoice on successful WooCommerce order
   *
   * The main function of the pluging. It handles the request from
   * the order once the oreder is sent.
   *
   * TO DO: Razbiti možda na više metoda.
   *
   * @param int $order_id ID of the order.
   * @since 1.0.0
   */
  public function solo_api_send_api_request( $order_id ) {

    $method_executed = get_transient( 'solo_api_method_executed' );
    if ( $method_executed === false ) {
      // It wasn't there, so regenerate the data and save the transient.
      $method_executed = true;
      set_transient( 'solo_api_method_executed', $method_executed, 5 );
    } else {
      return;
    }

    $order      = wc_get_order( $order_id );
    $order_data = $order->get_data(); // The Order data.

    // Options.
    $solo_api_token         = get_option( 'solo_api_token' );
    $solo_api_measure       = get_option( 'solo_api_measure' );
    $solo_api_payment_type  = get_option( 'solo_api_payment_type' );
    $solo_api_languages     = get_option( 'solo_api_languages' );
    $solo_api_currency      = get_option( 'solo_api_currency' );
    $solo_api_service_type  = get_option( 'solo_api_service_type' );
    $solo_api_show_taxes    = get_option( 'solo_api_show_taxes' );
    $solo_api_invoice_type  = get_option( 'solo_api_invoice_type' );
    $solo_api_currency_rate = get_option( 'solo_api_currency_rate' );
    $solo_api_due_date      = get_option( 'solo_api_due_date' );

    // Check if billing or shipping.
    $field = 'shipping';

    if ( ( isset( $order_data['billing']['first_name'] ) && $order_data['billing']['first_name'] !== '' ) ) {
      $field = 'billing';
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

    $meta_data = $order_data['meta_data'];

    foreach ( $meta_data as $data => $meta_value ) {
      $data = $meta_value->get_data();

      if ( $data['key'] === '_shipping_pin_number' ) {
        $pin_number = ( isset( $data['value'] ) && $data['value'] !== '' ) ? $data['value'] : '';
      }

      if ( $data['key'] === '_shipping_iban_number' ) {
        $iban_number = ( isset( $data['value'] ) && $data['value'] !== '' ) ? $data['value'] : '';
      }

      if ( $field === 'billing' ) {
        if ( $data['key'] === '_billing_pin_number' ) {
          $pin_number = ( isset( $data['value'] ) && $data['value'] !== '' ) ? $data['value'] : '';
        }

        if ( $data['key'] === '_billing_iban_number' ) {
          $iban_number = ( isset( $data['value'] ) && $data['value'] !== '' ) ? $data['value'] : '';
        }
      }
    }

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

    $solo_api_bill_type     = get_option( 'solo_api_bill_offer-' . esc_attr( $order_data['payment_method'] ) );
    $solo_api_fiscalization = get_option( 'solo_api_fiscalization-' . esc_attr( $order_data['payment_method'] ) );

    $url = 'https://api.solo.com.hr/' . $solo_api_bill_type;

    $post_url = $url . '?token=' . $solo_api_token . '&tip_usluge=' . $solo_api_service_type . '&prikazi_porez=' . $solo_api_show_taxes . '&kupac_naziv=' . esc_attr( $order_buyer ) . '&kupac_adresa=' . esc_attr( $order_address );

    if ( $solo_api_bill_type === 'racun' ) {
      $post_url .= '&tip_racuna=' . $solo_api_invoice_type;
    }

    if ( ! empty( $pin_number ) ) {
      $post_url .= '&kupac_oib=' . esc_attr( $pin_number );
    }

    // Individual order.
    $item_no = 1;

    foreach ( array_unique( $order->get_items() ) as $item_key => $item_values ) {
      $item_name = $item_values->get_name(); // Name of the product.
      $item_data = $item_values->get_data(); // Product data.

      $product_name = $item_data['name'];
      $quantity     = (double) ( $item_data['quantity'] !== 0 ) ? $item_data['quantity'] : 1;
      $single_price = (double) $item_data['total'] / $quantity;
      $single_tax   = (double) $item_data['total_tax'] / $quantity;

      $tax_rate = (int) ( ( $single_tax / $single_price ) * 100 );

      // If the tax rate is not 5%, 13% or 25% then the set tax will be 0.
      if ( ! in_array( $tax_rate, array( 5, 13, 25 ), true ) ) {
        $tax_rate = 0;
      }

      $line_total = rawurlencode( number_format( $single_price, 2, ',', '.' ) );

      $post_url .= '&usluga=' . $item_no . '&opis_usluge_' . $item_no . '=' . $product_name . '&jed_mjera_' . $item_no . '=' . $solo_api_measure . '&cijena_' . $item_no . '=' . $line_total . '&kolicina_' . $item_no . '=' . $quantity . '&popust_' . $item_no . '=0&porez_stopa_' . $item_no . '=' . $tax_rate;

      $item_no++;
    }

    // Shipping.
    if ( (int) $order->get_total_shipping() > 0 ) {
      $shipping_price = (double) number_format( $order->get_total_shipping(), 2, ',', '.' );

      $tax_rate = (int) ( ( $order->get_shipping_tax() / $order->get_total_shipping() ) * 100 );

      // If the tax rate is not 5%, 13% or 25% then the set tax will be 0.
      if ( ! in_array( $tax_rate, array( 5, 13, 25 ), true ) ) {
        $tax_rate = 0;
      }

      $post_url .= '&usluga=' . $item_no . '&opis_usluge_' . $item_no . '=' . esc_html__( 'Shipping fee', 'woo-solo-api' ) . '&jed_mjera_' . $item_no . '=' . $solo_api_measure . '&cijena_' . $item_no . '=' . $shipping_price . '&kolicina_' . $item_no . '=1&popust_' . $item_no . '=0&porez_stopa_' . $item_no . '=' . $tax_rate;
    }

    $customer_note = ( isset( $order->data['customer_note'] ) && '' !== $order->data['customer_note'] ) ? $order->data['customer_note'] : '';

    switch ( $solo_api_due_date ) {
      case '2':
            $due_date = date( 'Y-m-d', strtotime( '+2 weeks' ) );
            break;
      case '3':
            $due_date = date( 'Y-m-d', strtotime( '+3 weeks' ) );
            break;
      default:
        $due_date = date( 'Y-m-d', strtotime( '+1 week' ) );
    }

    $post_url .= '&nacin_placanja=' . $solo_api_payment_type . '&rok_placanja=' . $due_date . '&napomene=' . wp_kses_post( $customer_note );

    if ( ! empty( $iban_number ) ) {
      $post_url .= '&iban=' . esc_attr( $iban_number );
    }

    if ( $solo_api_bill_type === 'ponuda' ) {
      $post_url .= '&jezik_ponude=' . $solo_api_languages . '&valuta_ponude=' . $solo_api_currency;
    } else {
      $post_url .= '&jezik_racuna=' . $solo_api_languages;
    }

    if ( ! empty( $solo_api_currency_rate ) ) {
      $num       = (float) str_replace( ',', '.', $solo_api_currency_rate );
      $post_url .= '&tecaj=' . str_replace( '.', ',', round( $num, 6 ) );
    }

    if ( $solo_api_bill_type === 'racun' ) {
      if ( ! empty( $solo_api_fiscalization ) ) {
        $post_url .= '&fiskalizacija=1';
      } else {
        $post_url .= '&fiskalizacija=0';
      }
    }

    $method_executed = false;

    $regular_url = str_replace( ' ', '%20', $post_url );

    if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
      // phpcs:disable WordPress.PHP.DevelopmentFunctions
      error_log( print_r( $regular_url, true ) );
      // phpcs:enable
    }

    /**
     * For more info go to: https://solo.com.hr/api-dokumentacija/izrada-racuna
     */
    $response = wp_remote_post(
      $regular_url, array(
          'method' => 'POST',
      )
    );

    if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
      // phpcs:disable WordPress.PHP.DevelopmentFunctions
      error_log( print_r( $response, true ) );
      // phpcs:enable
    }

    if ( is_wp_error( $response ) ) {
      $error_code    = wp_remote_retrieve_response_code( $response );
      $error_message = wp_remote_retrieve_response_message( $response );
      return new \WP_Error( $error_code, $error_message );
    }

    $body = json_decode( $response['body'] );

    if ( $body->status !== 0 ) {
      return new \WP_Error( $body->status, $body->message );
    }

    $this->solo_api_send_mail( $body, sanitize_email( $email ), $order_data['payment_method'], $solo_api_bill_type );
  }

  /**
   * Send mail method
   *
   * A method that will send a mail with created invoice or order pdf.
   *
   * @param  object $body           The body of response.
   * @param  string $email          Customer email.
   * @param  string $payment_method Payment method type.
   * @param  string $bill_type      Bill type. Important for sending the email.
   *
   * @since  1.2  Added bill type check
   * @since  1.0.0
   */
  public function solo_api_send_mail( $body, $email, $payment_method, $bill_type ) {

    $solo_api_send_pdf  = get_option( 'solo_api_send_pdf' );
    $checked_gateways   = get_option( 'solo_api_mail_gateway' );
    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

    $in_gateway = false;

    if ( ! empty( $checked_gateways ) ) {
      foreach ( $checked_gateways as $key => $gateway ) {
        if ( array_key_exists( $gateway, $available_gateways ) ) {
          $in_gateway = true;
          break;
        }
      }
    }

    global $wp_filesystem;
    if ( empty( $wp_filesystem ) ) {
      require_once ABSPATH . '/wp-admin/includes/file.php';
    }

    $checkout_url = \wc_get_checkout_url();
    $url          = wp_nonce_url( $checkout_url, '_wpnonce', '_wpnonce' );
    $creds        = \request_filesystem_credentials( $url, '', false, false, null );

    if ( $creds === false ) {
      return; // stop processing here.
    }

    $response_body = (array) $body;

    // Create pdf.
    $pdf_link = esc_url( $response_body[ $bill_type ]->pdf );
    if ( $bill_type === 'racun' ) {
      $pdf_name = esc_html( $response_body[ $bill_type ]->broj_racuna );
    } else {
      $pdf_name = esc_html( $response_body[ $bill_type ]->broj_ponude );
    }

    $pdf_get = wp_remote_get( $pdf_link );

    if ( is_wp_error( $pdf_get ) ) {
      $error_code    = wp_remote_retrieve_response_code( $pdf_get );
      $error_message = wp_remote_retrieve_response_message( $pdf_get );
      return new \WP_Error( $error_code, $error_message );
    }

    $pdf_contents = $pdf_get['body'];

    $pdf_name = 'ponuda-' . $pdf_name;

    // Now we have some credentials, try to get the wp_filesystem running.
    if ( ! WP_Filesystem( $creds ) ) {
      // Our credentials were no good, ask the user for them again.
      \request_filesystem_credentials( $url, '', true, false, null );
      return true;
    }

    $upload_dir = wp_upload_dir();

    $new_dir = $upload_dir['basedir'] . '/ponude/' . date( 'Y' ) . '/' . date( 'm' );

    if ( ! file_exists( $new_dir ) ) {
      wp_mkdir_p( $new_dir );
    }

    $attachment = $new_dir . '/' . $pdf_name . '.pdf';

    if ( file_exists( $attachment ) ) {
      $attachment = $new_dir . '/' . $pdf_name . '-' . mt_rand() . '.pdf';
    }

    WP_Filesystem( $creds );

    $wp_filesystem->put_contents(
      $attachment,
      $pdf_contents,
      FS_CHMOD_FILE // predefined mode settings for WP files.
    );

    // Store the pdf as an attachment.
    $filetype = wp_check_filetype( $pdf_name, null );

    $attachment_array = array(
        'guid'           => $attachment,
        'post_mime_type' => $filetype['type'],
        'post_title'     => $pdf_name,
        'post_content'   => '',
        'post_status'    => 'inherit',
    );

    $url_parse = wp_parse_url( $attachment );

    $attachment_id = wp_insert_attachment( $attachment_array, $url_parse['path'], 0 ); // Create attachment in the Media screen.

    if ( $solo_api_send_pdf === '1' ) {
      if ( $in_gateway ) {

        $solo_api_message    = get_option( 'solo_api_message' );
        $solo_api_mail_title = get_option( 'solo_api_mail_title' );

        // Send mail with the attachment.
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

        wp_mail( $email, $solo_api_mail_title, $solo_api_message, $headers, array( $attachment ) );
      }
    }
  }
}
