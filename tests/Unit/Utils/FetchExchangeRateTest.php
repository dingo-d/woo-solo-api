<?php

namespace Tests\Unit\Utils;

use MadeByDenis\WooSoloApi\Utils\FetchExchangeRate;
use Brain\Monkey\Functions;

beforeEach(function() {
	parent::set_up();
	if (!defined('HOUR_IN_SECONDS')) {
		define('HOUR_IN_SECONDS', 3600);
	}
});

afterEach(function() {
	$envName = FetchExchangeRate::TRANSIENT;
	putenv("$envName");
	parent::tear_down();
});

test('Fetch exchange rate works', function () {
	$exchangeRateResponse = '[{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Australija","drzava_iso":"AUS","sifra_valute":"036","valuta":"AUD","kupovni_tecaj":"1,5383","srednji_tecaj":"1,5360","prodajni_tecaj":"1,5337"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Kanada","drzava_iso":"CAN","sifra_valute":"124","valuta":"CAD","kupovni_tecaj":"1,4566","srednji_tecaj":"1,4544","prodajni_tecaj":"1,4522"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Češka","drzava_iso":"CZE","sifra_valute":"203","valuta":"CZK","kupovni_tecaj":"23,844","srednji_tecaj":"23,808","prodajni_tecaj":"23,772"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Danska","drzava_iso":"DNK","sifra_valute":"208","valuta":"DKK","kupovni_tecaj":"7,4493","srednji_tecaj":"7,4381","prodajni_tecaj":"7,4269"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Mađarska","drzava_iso":"HUN","sifra_valute":"348","valuta":"HUF","kupovni_tecaj":"389,33","srednji_tecaj":"388,75","prodajni_tecaj":"388,17"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Japan","drzava_iso":"JPN","sifra_valute":"392","valuta":"JPY","kupovni_tecaj":"141,38","srednji_tecaj":"141,17","prodajni_tecaj":"140,96"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Norveška","drzava_iso":"NOR","sifra_valute":"578","valuta":"NOK","kupovni_tecaj":"10,8058","srednji_tecaj":"10,7896","prodajni_tecaj":"10,7734"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Švedska","drzava_iso":"SWE","sifra_valute":"752","valuta":"SEK","kupovni_tecaj":"11,1502","srednji_tecaj":"11,1335","prodajni_tecaj":"11,1168"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Švicarska","drzava_iso":"CHE","sifra_valute":"756","valuta":"CHF","kupovni_tecaj":"1,0035","srednji_tecaj":"1,0020","prodajni_tecaj":"1,0005"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Velika Britanija","drzava_iso":"GBR","sifra_valute":"826","valuta":"GBP","kupovni_tecaj":"0,88380","srednji_tecaj":"0,88248","prodajni_tecaj":"0,88116"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"SAD","drzava_iso":"USA","sifra_valute":"840","valuta":"USD","kupovni_tecaj":"1,0894","srednji_tecaj":"1,0878","prodajni_tecaj":"1,0862"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Bosna I Hercegovina","drzava_iso":"BIH","sifra_valute":"977","valuta":"BAM","kupovni_tecaj":"1,95876","srednji_tecaj":"1,95583","prodajni_tecaj":"1,95290"},{"broj_tecajnice":"19","datum_primjene":"2023-01-26","drzava":"Poljska","drzava_iso":"POL","sifra_valute":"985","valuta":"PLN","kupovni_tecaj":"4,7229","srednji_tecaj":"4,7158","prodajni_tecaj":"4,7087"}]'; //phpcs:ignore

	// Mock wp_remote_get response.
	Functions\when('wp_remote_get')->justReturn([
		'headers' => '',
		'body' => $exchangeRateResponse,
		'response' => [
			'code' => 200,
			'message' => 'OK',
		]
	]);

	Functions\when('get_transient')->justReturn(false);
	Functions\when('wp_remote_retrieve_body')->justReturn($exchangeRateResponse);

	Functions\when('set_transient')->alias(function($name, $duration) use ($exchangeRateResponse) {
		putenv("$name=$exchangeRateResponse");
	});

	$exchange = new FetchExchangeRate();

	$exchange->setExchangeRates();

	$transientValue = getenv(FetchExchangeRate::TRANSIENT);

	expect($transientValue)
		->not->toBeEmpty()
		->toBe($exchangeRateResponse);
});

test('Init hook is called in the fetch exchange class', function () {
	$exchange = new FetchExchangeRate();

	$exchange->register();
	$actionValue = has_action('init', 'MadeByDenis\WooSoloApi\Utils\FetchExchangeRate->setExchangeRates()');

	expect($actionValue)
		->toBe(10);
});
