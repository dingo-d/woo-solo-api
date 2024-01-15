<?php

namespace Tests\Integration\BackgroundJobs;

use MadeByDenis\WooSoloApi\BackgroundJobs\SendCustomerEmail;

use Yoast\WPTestUtils\WPIntegration\TestCase;

if (isUnitTest()) {
	return;
}

uses(TestCase::class);

beforeEach(function() {
	parent::set_up();
	update_option('solo_api_mail_gateway', 'a:3:{i:0;s:4:"bacs";i:1;s:6:"cheque";i:2;s:3:"cod";}');
});

afterEach(function() {
	parent::tear_down();
});

test('Sending emails in a bg job works correctly', function () {

	$body = [
		'status' => 0,
		'ponuda' => [
			'id' => '12345678',
			'broj_ponude' => '0001-2024',
			'tip_usluge' => 1,
			'prikazi_porez' => 0,
			'kupac_naziv' => 'Test Test',
			'kupac_adresa' => 'Teeest 12, Zagreb Zagreb 10000, HR',
			'kupac_oib' => '',
			'usluge' => [
				[
					'broj' => 1,
					'opis_usluge' => 'Album',
					'jed_mjera' => 'kom',
					'kolicina' => 1,
					'cijena' => '15,00',
					'popust' => 0,
					'porez_stopa' => 0,
					'suma' => '15,00',
				]

			],
			'neto_suma' => '15,00',
			'porezi' => [
				[
					'stopa' => 0,
					'osnovica' => '15,00',
					'porez' => '0,00',
				]

			],
			'bruto_suma' => '15,00',
			'nacin_placanja' => 1,
			'ponudu_izdao' => 'Test',
			'datum_ponude' => '15.1.2024. 17:19:00',
			'rok_placanja' => '22.1.2024.',
			'napomene' => '',
            'ponavljanje' => 0,
            'iban' => 'HR1234567890123456789',
            'jezik_ponude' => 1,
            'valuta_ponude' => 'EUR',
            'tecaj' => 1,
            'status' => 1,
            'boja' => '#ffcf03',
            'pdf' => 'https://solo.com.hr/download/12345678',
		],
		'message' => 'Ponuda uspješno kreirana.',
	];
	$email = 'admin@sitetitle.test';
	$billType = 'ponuda';
	$paymentMethod = 'cod';

	// Order mock.
	$productId = $this->factory->post->create(array(
		'post_type' => 'product',
		'post_title' => 'Fake Product',
		'post_content' => 'This is a fake product for testing.',
		'post_status' => 'publish',
	));

	// Create a fake order.
	$order = wc_create_order();

	// Add the product to the order.
	$order->add_product(wc_get_product($productId));

	$orderId = $order->get_id();

	$sendEmailJob = new SendCustomerEmail();

	$sendEmailJob->registerProcess($orderId, $body, $email, $billType, $paymentMethod);

	$sentEmail = tests_retrieve_phpmailer_instance()->get_sent();

	expect($sentEmail)->not->toBeEmpty()
		->and($sentEmail->to)->toBeArray()
		->and($sentEmail->to)->toBe([[$email, '']])
		->and($sentEmail->subject)->toBeString()
		->and($sentEmail->subject)->toBe('Your offer from Test Blog');
});
