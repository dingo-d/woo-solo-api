<?php
/**
 * The plugin helpers class.
 *
 * @link       https://madebydenis.com
 * @since      1.5.0 Change hnb API to an official one.
 * @since      1.3.0
 *
 * @package    Woo_Solo_Api\Admin
 */

namespace Woo_Solo_Api\Admin;

/**
 * Helpers class for the plugin.
 *
 * Holds various helper methods for the plugin.
 *
 * @package    Woo_Solo_Api\Admin
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Helpers {
  /**
   * Returns the Croatian exchange rates
   *
   * @link https://www.hnb.hr/tecajn/htecajn.htm
   *
   * @since 1.5.0 Change link for the currency fetch.
   * @since 1.3.0
   *
   * @return array Exchange rates.
   */
  public function get_exchange_rates() {

    $currency_rates = get_transient( 'exchange_rate_transient' ); // Get transient.

    if ( false === $currency_rates ) { // If no valid transient exists, run this.
      $url = 'https://www.hnb.hr/tecajn/htecajn.htm';

      $headers = get_headers( $url );
      $status  = substr( $headers[0], 9, 3 );

      // Is the link up?
      if ( $status !== '200' ) {
        return false;
      }

      $contents = file_get_contents( $url );

      $array = explode( "\n", $contents );
      unset( $array[0] );
      $array = array_values( $array );

      $currency_rates = [];

      foreach ( $array as $arr_key => $arr_value ) {
        $single_rate   = array_values( array_filter( explode( ' ', $arr_value ) ) );
        $currency_name = preg_replace( '/[^a-zA-Z]+/', '', $single_rate[0] );

        $currency_rates[ $currency_name ] = $single_rate[2];
      }

      set_transient( 'exchange_rate_transient', $currency_rates, 6 * HOUR_IN_SECONDS );
    }

    // Are the results in an array?
    if ( ! is_array( $currency_rates ) ) {
      return false;
    }

    return $currency_rates;
  }
}
