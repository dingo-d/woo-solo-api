<?php
/**
 * The plugin helpers class.
 *
 * @link       https://madebydenis.com
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
   * The link from which the exchange rates are pulled from
   * http://hnbex.eu/api/v1/rates/daily/, is not an official API
   * from HNB, but they are pulling the rates from the official
   * site.
   *
   * @return array Exchange rates.
   */
  public function get_exchange_rates() {

    $currency_transient = get_transient( 'exchange_rate_transient' ); // Get transient.

    if ( false === $currency_transient ) { // If no valid transient exists, run this.
      $currency_api_url   = 'http://hnbex.eu/api/v1/rates/daily/';
      $currency_transient = wp_remote_get( $currency_api_url );

      // Is the API up?
      if ( ! 200 === wp_remote_retrieve_response_code( $currency_transient ) ) {
        return false;
      }

      set_transient( 'exchange_rate_transient', $currency_transient, 1 * DAY_IN_SECONDS );
    }

    $currency_rates = json_decode( wp_remote_retrieve_body( $currency_transient ), true );

    // Are the results in an array?
    if ( ! is_array( $currency_rates ) ) {
      return false;
    }

    return $currency_rates;
  }
}
