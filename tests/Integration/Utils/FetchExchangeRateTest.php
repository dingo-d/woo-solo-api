<?php

namespace Tests\Integration\Utils;

use Codeception\TestCase\WPTestCase;
use Brain\Monkey;
use Brain\Monkey\Functions;
use MadeByDenis\WooSoloApi\Utils\FetchExchangeRate;

class FetchExchangeRateTest extends WPTestCase
{
	private const EXCHANGE_RATE = '[{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Australija","drzava_iso":"AUS","sifra_valute":"036","valuta":"AUD","kupovni_tecaj":"1,5732","srednji_tecaj":"1,5708","prodajni_tecaj":"1,5684"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Kanada","drzava_iso":"CAN","sifra_valute":"124","valuta":"CAD","kupovni_tecaj":"1,4436","srednji_tecaj":"1,4414","prodajni_tecaj":"1,4392"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Češka","drzava_iso":"CZE","sifra_valute":"203","valuta":"CZK","kupovni_tecaj":"24,160","srednji_tecaj":"24,124","prodajni_tecaj":"24,088"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Danska","drzava_iso":"DNK","sifra_valute":"208","valuta":"DKK","kupovni_tecaj":"7,4482","srednji_tecaj":"7,4370","prodajni_tecaj":"7,4258"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Mađarska","drzava_iso":"HUN","sifra_valute":"348","valuta":"HUF","kupovni_tecaj":"403,93","srednji_tecaj":"403,33","prodajni_tecaj":"402,73"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Japan","drzava_iso":"JPN","sifra_valute":"392","valuta":"JPY","kupovni_tecaj":"138,14","srednji_tecaj":"137,93","prodajni_tecaj":"137,72"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Norveška","drzava_iso":"NOR","sifra_valute":"578","valuta":"NOK","kupovni_tecaj":"10,5438","srednji_tecaj":"10,5280","prodajni_tecaj":"10,5122"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Švedska","drzava_iso":"SWE","sifra_valute":"752","valuta":"SEK","kupovni_tecaj":"11,1597","srednji_tecaj":"11,1430","prodajni_tecaj":"11,1263"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Švicarska","drzava_iso":"CHE","sifra_valute":"756","valuta":"CHF","kupovni_tecaj":"0,9894","srednji_tecaj":"0,9879","prodajni_tecaj":"0,9864"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Velika Britanija","drzava_iso":"GBR","sifra_valute":"826","valuta":"GBP","kupovni_tecaj":"0,88180","srednji_tecaj":"0,88048","prodajni_tecaj":"0,87916"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"SAD","drzava_iso":"USA","sifra_valute":"840","valuta":"USD","kupovni_tecaj":"1,0561","srednji_tecaj":"1,0545","prodajni_tecaj":"1,0529"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Bosna I Hercegovina","drzava_iso":"BIH","sifra_valute":"977","valuta":"BAM","kupovni_tecaj":"1,95876","srednji_tecaj":"1,95583","prodajni_tecaj":"1,95290"},{"broj_tecajnice":"3","datum_primjene":"2023-01-04","drzava":"Poljska","drzava_iso":"POL","sifra_valute":"985","valuta":"PLN","kupovni_tecaj":"4,6901","srednji_tecaj":"4,6831","prodajni_tecaj":"4,6761"}]'; //phpcs:ignore

		/**
	 * @var IntegrationTester
	 */
	protected $tester;

	public function setUp(): void
	{
		parent::setUp();
		Monkey\setUp();
		Functions\stubEscapeFunctions();
		Functions\stubTranslationFunctions();
	}

	public function tearDown(): void
	{
		Monkey\tearDown();
		parent::tearDown();
	}

	public function testFetchExchangeRate()
	{
		// Mock wp_remote_get response.
		Functions\when('wp_remote_get')->justReturn([
			'headers' => '',
			'body' => self::EXCHANGE_RATE,
			'response' => [
				'code' => 200,
				'message' => 'OK',
			]
		]);

		$exchange = new FetchExchangeRate();

		$exchange->setExchangeRates();

		$transient = get_transient(FetchExchangeRate::TRANSIENT);

		$this->assertIsString($transient);
	}
}
