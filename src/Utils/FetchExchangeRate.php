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
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('init', [$this, 'setExchangeRates']);
	}

	/**
	 * Returns the Croatian exchange rates
	 *
	 * @link http://api.hnb.hr/tecajn/v2
	 *
	 * @since 2.0.0 Change the way the data is fetched.
	 * @since 1.7.5 Add fallback method in case the allow_url_fopen is disabled.
	 * @since 1.5.0 Change link for the currency fetch.
	 * @since 1.3.0
	 *
	 * @return void.
	 */
	public function setExchangeRates()
	{
		$currencyRates = \get_transient('exchange_rate_transient');

		if ($currencyRates === false) {
			$this->setExchangeRatesTransient();
		}
	}

	private function setExchangeRatesTransient()
	{
	}


}
