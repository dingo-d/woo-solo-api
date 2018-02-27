<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Woo_Solo_Api
 */

if ( ! current_user_can( 'activate_plugins' ) ) {
  return;
}

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
}

/**
 * Delete saved options in the database
 */
delete_option( 'solo_api_token' );
delete_option( 'solo_api_measure' );
delete_option( 'solo_api_payment_type' );
delete_option( 'solo_api_languages' );
delete_option( 'solo_api_currency' );
delete_option( 'solo_api_service_type' );
delete_option( 'solo_api_show_taxes' );
delete_option( 'solo_api_invoice_type' );
delete_option( 'solo_api_mail_title' );
delete_option( 'solo_api_message' );
delete_option( 'solo_api_change_mail_from' );
delete_option( 'solo_api_enable_pin' );
delete_option( 'solo_api_enable_iban' );
delete_option( 'solo_api_due_date' );
delete_option( 'solo_api_mail_gateway' );
delete_option( 'solo_api_send_pdf' );
delete_option( 'solo_api_send_control' );

$available_woo_gateways = WC()->payment_gateways->get_available_payment_gateways();
foreach ( $available_woo_gateways as $gateway_woo_sett => $gateway_woo_val ) {
  delete_option( 'solo_api_bill_offer-' . esc_attr( $gateway_woo_val->id ) );
  delete_option( 'solo_api_fiscalization-' . esc_attr( $gateway_woo_val->id ) );
}

add_action( 'wp_mail_from_name', 'solo_api_revert_mail_from_name' );

/**
 * Revert mail from name that is send from WordPress to default
 *
 * @param  string $name Name that is shown.
 * @return string       Changed name.
 */
function solo_api_revert_mail_from_name( $name ) {
  $from_name = 'WordPress';
  return $from_name;
}
