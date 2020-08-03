<?php

/**
 * File holding FetchExchangeRate class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Utils
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Utils;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * FetchExchangeRate class
 *
 * This class is responsible for fetching an exchange rate
 * every 12 hours from the official Croatian National Bank API.
 *
 * For the documentation @see https://api.hnb.hr/
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Utils
 */
class FetchExchangeRate implements Registrable
{
	/**
	 * Transient name
	 *
	 * @string
	 */
	public const TRANSIENT = 'exchange_rate_transient';

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('init', [$this, 'setExchangeRates']);
	}

	/**
	 * Sets the Croatian exchange rates for foreign currencies
	 *
	 * @return void.
	 * @since 1.7.5 Add fallback method in case the allow_url_fopen is disabled.
	 * @since 1.5.0 Change link for the currency fetch.
	 * @since 1.3.0
	 *
	 * @since 2.0.0 Change the way the data is fetched.
	 */
	public function setExchangeRates(): void
	{
		if (\get_transient(self::TRANSIENT) === false) {
			$this->setExchangeRatesTransient();
		}
	}

	/**
	 * Fetch exchange rates from the CNB api and store it in a transient
	 *
	 * @link http://api.hnb.hr/tecajn/v2
	 */
	private function setExchangeRatesTransient(): void
	{
		$apiURL = 'http://api.hnb.hr/tecajn/v2';

		$response = wp_remote_get($apiURL);

		$body = wp_remote_retrieve_body($response);

		set_transient(self::TRANSIENT, $body, 6 * HOUR_IN_SECONDS);
	}
}
