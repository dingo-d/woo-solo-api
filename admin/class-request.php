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
 * @since      1.9.3  Modified methods
 * @since      1.9.0  Added a dependancy on the helper class.
 * @since      1.8.1  Added check for no taxes.
 * @since      1.4.0  Added additional methods.
 * @since      1.2.0  Added bill type check in the request method
 * @since      1.0.0
 */
class Request {

  /**
   * Helpers object property.
   *
   * @since    1.4.0
   * @access   private
   * @var      string    $helper    Helper object instance.
   */
  private $helper;

  /**
   * Initialize the class and set its properties.
   *
   * @since 1.9.0 Added a dependancy on the helper class.
   * @since 1.6.0 Fixed the currency on invoices.
   * @since 1.0.0
   *
   * @param Helpers $helper Helpers class instance.
   */
  public function __construct( Helpers $helper ) {
    $this->helper = $helper;
  }

  /**
   * Solo API create an invoice on successful WooCommerce order
   *
   * The main function of the pluging. It handles the request from
   * the order once the oreder is sent.
   *
   * @param WC_Order       $order Order data.
   * @param bool           $sent_to_admin Send to admin (default: false).
   * @param bool           $plain_text Plain text email (default: false).
   * @param WC_Email_Order $email Order email object.
   *
   * @since 1.4.0 Fix the send api method.
   * @since 1.3.0 Added tax checks and additional debug options.
   * @since 1.0.0
   */
  public function solo_api_send_api_request( $order, $sent_to_admin, $plain_text, $email ) {

    $method_executed = get_transient( 'solo_api_method_executed' );
    if ( $method_executed === false ) {
      // It wasn't there, so regenerate the data and save the transient.
      $method_executed = true;
      set_transient( 'solo_api_method_executed', $method_executed, 5 );
    } else {
      return;
    }

    $solo_api_send_control = get_option( 'solo_api_send_control' );

    // First check if the pdf should be send on checkout or on status change.
    if ( $solo_api_send_control === 'status_change' ) {
      // If the option is selected to sed pdf on admin we should only run it on admin.
      if ( is_admin() ) {
        // Execute only if status is changed to completed!
        $order        = $email->object;
        $order_data   = $order->get_data(); // The Order data.
        $order_status = $order_data['status'];

        if ( $order_status === 'completed' ) {
          $this->execute_solo_api_call( $order );
        }
      } else {
        return;
      }
    } else {
      // This should run only on the front facing side.
      if ( is_admin() ) {
        return;
      } else {
        $this->execute_solo_api_call( $order );
      }
    }
  }

  /**
   * Execute the call to the SOLO API
   *
   * @param  WC_Order $order Order data.
   * @since  1.9.3 Added translated notes for recalculating rates.
   * @since  1.8.1 Added a check for no taxes on items.
   * @since  1.7.0 Fixed tax rates and payment types per payment gateway.
   * @since  1.4.0 Separated the send method for more control. Add
   *               Check to send the mail in this method, so that the
   *               method that controls the send is not called at all.
   */
  public function execute_solo_api_call( $order ) {

    // Options.
    $solo_api_token        = get_option( 'solo_api_token' );
    $solo_api_measure      = get_option( 'solo_api_measure' );
    $solo_api_languages    = get_option( 'solo_api_languages' );
    $solo_api_currency     = get_option( 'solo_api_currency' );
    $solo_api_service_type = get_option( 'solo_api_service_type' );
    $solo_api_show_taxes   = get_option( 'solo_api_show_taxes' );
    $solo_api_invoice_type = get_option( 'solo_api_invoice_type' );
    $solo_api_due_date     = get_option( 'solo_api_due_date' );

    $order_data = $order->get_data(); // The Order data.

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
    $solo_api_payment_type  = get_option( 'solo_api_payment_type-' . esc_attr( $order_data['payment_method'] ) );

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

    $calculate_taxes = get_option( 'woocommerce_calc_taxes' );

    foreach ( array_unique( $order->get_items() ) as $item_key => $item_values ) {

      $item_name = $item_values->get_name(); // Name of the product.
      $item_data = $item_values->get_data(); // Product data.

      $tax_rates    = array_values( \WC_Tax::get_base_tax_rates( $item_values->get_tax_class() ) );
      $tax_rate     = (double) $tax_rates[0]['rate'];
      $product_name = $item_data['name'];
      $quantity     = (double) ( $item_data['quantity'] !== 0 ) ? $item_data['quantity'] : 1;
      $single_price = (double) $item_data['total'] / $quantity;

      // If the tax rate is not 5%, 13% or 25% then the set tax will be 0.
      // phpcs:disable WordPress.PHP.StrictInArray.MissingTrueStrict
      if ( ! in_array( $tax_rate, array( 5, 13, 25 ) ) ) {
        $tax_rate = 0;
      }
      // phpcs:enable

      if ( $calculate_taxes === 'no' ) {
        $tax_rate = 0;
      }

      $line_total = number_format( $single_price, 2, ',', '.' );

      $post_url .= '&usluga=' . $item_no . '&opis_usluge_' . $item_no . '=' . $product_name . '&jed_mjera_' . $item_no . '=' . $solo_api_measure . '&cijena_' . $item_no . '=' . $line_total . '&kolicina_' . $item_no . '=' . $quantity . '&popust_' . $item_no . '=0&porez_stopa_' . $item_no . '=' . $tax_rate;

      $item_no++;
    }

    // Shipping.
    $total_shipping = $order->get_total_shipping();
    if ( (double) $total_shipping > 0 ) {
      $shipping_items = $order->get_items( 'shipping' );

      foreach ( $shipping_items as $shipping_key => $shipping_object ) {
        $shipping_tax_rates = array_values( \WC_Tax::get_base_tax_rates( $shipping_object->get_tax_class() ) );
      }

      $shipping_tax_rate = (double) $shipping_tax_rates[0]['rate'];

      $shipping_price = number_format( $total_shipping, 2, ',', '.' );

      // phpcs:disable WordPress.PHP.StrictInArray.MissingTrueStrict
      if ( ! in_array( $shipping_tax_rate, array( 5, 13, 25 ) ) ) {
        $shipping_tax_rate = 0;
      }
      // phpcs:enable

      $post_url .= '&usluga=' . $item_no . '&opis_usluge_' . $item_no . '=' . esc_html__( 'Shipping fee', 'woo-solo-api' ) . '&jed_mjera_' . $item_no . '=' . $solo_api_measure . '&cijena_' . $item_no . '=' . $shipping_price . '&kolicina_' . $item_no . '=1&popust_' . $item_no . '=0&porez_stopa_' . $item_no . '=' . $shipping_tax_rate;
    }

    $customer_note = ( isset( $order->data['customer_note'] ) && '' !== $order->data['customer_note'] ) ? $order->data['customer_note'] : '';

    switch ( $solo_api_due_date ) {
      case '1d':
        $due_date = date( 'Y-m-d', strtotime( '+1 day' ) );
            break;
      case '2d':
        $due_date = date( 'Y-m-d', strtotime( '+2 days' ) );
            break;
      case '3d':
        $due_date = date( 'Y-m-d', strtotime( '+3 days' ) );
            break;
      case '4d':
        $due_date = date( 'Y-m-d', strtotime( '+4 days' ) );
            break;
      case '5d':
        $due_date = date( 'Y-m-d', strtotime( '+5 days' ) );
            break;
      case '6d':
        $due_date = date( 'Y-m-d', strtotime( '+6 days' ) );
            break;
      case '2':
        $due_date = date( 'Y-m-d', strtotime( '+2 weeks' ) );
            break;
      case '3':
        $due_date = date( 'Y-m-d', strtotime( '+3 weeks' ) );
            break;
      default:
        $due_date = date( 'Y-m-d', strtotime( '+1 week' ) );
    }

    $post_url .= '&nacin_placanja=' . $solo_api_payment_type . '&rok_placanja=' . $due_date;

    if ( ! empty( $iban_number ) ) {
      $post_url .= '&iban=' . esc_attr( $iban_number );
    }

    if ( $solo_api_bill_type === 'ponuda' ) {
      $post_url .= '&jezik_ponude=' . $solo_api_languages . '&valuta_ponude=' . $solo_api_currency;
    } else {
      $post_url .= '&jezik_racuna=' . $solo_api_languages . '&valuta_racuna=' . $solo_api_currency;
    }

    if ( $solo_api_currency !== '1' ) { // Only for foreign currency.
      $currency_helper = array(
          '1'  => 'HRK',
          '2'  => 'AUD',
          '3'  => 'CAD',
          '4'  => 'CZK',
          '5'  => 'DKK',
          '6'  => 'HUF',
          '7'  => 'JPY',
          '8'  => 'NOK',
          '9'  => 'SEK',
          '10' => 'CHF',
          '11' => 'GBP',
          '12' => 'USD',
          '13' => 'BAM',
          '14' => 'EUR',
          '15' => 'PLN',
      );

      $api_rates = $this->helper->get_exchange_rates();
      $currency  = $currency_helper[ $solo_api_currency ];

      $currency_rate = $api_rates[ $currency ];

      if ( ! empty( $currency_rate ) ) {
        $num       = (float) str_replace( ',', '.', $currency_rate );
        $post_url .= '&tecaj=' . str_replace( '.', ',', round( $num, 6 ) );

        switch ( $solo_api_languages ) {
          case '2':
            $translated_currency_note = esc_html__( 'Recalculated at the middle exchange rate of the CNB', 'woo-solo-api' );
                break;
          case '3':
            $translated_currency_note = esc_html__( 'Zum mittleren Wechselkurs der KNB neu berechnet', 'woo-solo-api' );
                break;
          case '4':
            $translated_currency_note = esc_html__( 'Recalculé au taux de change moyen de la BNC', 'woo-solo-api' );
                break;
          case '5':
            $translated_currency_note = esc_html__( 'Ricalcolato al tasso di cambio medio del BNC', 'woo-solo-api' );
                break;
          case '6':
            $translated_currency_note = esc_html__( 'Recalculado al tipo de cambio medio del BNC', 'woo-solo-api' );
                break;
          default:
            $translated_currency_note = esc_html__( 'Preračunato po srednjem tečaju HNB-a', 'woo-solo-api' );
                break;
        }


        $customer_note .= "\n" . sprintf(
          '%1$s (1 %2$s = %3$s HRK)',
          esc_html( $translated_currency_note ),
          esc_html( $currency ),
          esc_html( str_replace( '.', ',', round( $num, 6 ) ) )
        );
      }
    }

    if ( $solo_api_bill_type === 'racun' ) {
      if ( ! empty( $solo_api_fiscalization ) ) {
        $post_url .= '&fiskalizacija=1';
      } else {
        $post_url .= '&fiskalizacija=0';
      }
    }

    $post_url .= '&napomene=' . wp_kses_post( $customer_note );

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
    $response = wp_remote_post( $regular_url );

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

    // Send mail to the customer with the PDF of the invoice.
    $solo_api_send_pdf = get_option( 'solo_api_send_pdf' );

    if ( $solo_api_send_pdf === '1' ) {
      $this->solo_api_send_mail( $body, sanitize_email( $email ), $order_data['payment_method'], $solo_api_bill_type );
    }
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
   * @since  1.9.3 Made method private.
   * @since  1.9.2 Made WC() global method.
   * @since  1.4   Remove the check to send the mail or not.
   * @since  1.2   Added bill type check
   * @since  1.0.0
   */
  private function solo_api_send_mail( $body, $email, $payment_method, $bill_type ) {

    $checked_gateways   = get_option( 'solo_api_mail_gateway' );
    $available_gateways = \WC()->payment_gateways->get_available_payment_gateways();

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
