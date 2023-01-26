<?php

/**
 * File holding MockApiRequest class
 *
 * @package Tests\Fixtures
 * @since 2.0.0
 */

declare(strict_types=1);

namespace Tests\Fixtures;

use MadeByDenis\WooSoloApi\BackgroundJobs\SendCustomerEmail;
use MadeByDenis\WooSoloApi\Request\ApiRequest;
use MadeByDenis\WooSoloApi\Request\SoloApiRequest;

/**
 * MockApiRequest class
 *
 * @package Tests\Fixtures
 * @since 2.0.0
 */
class MockApiRequest implements ApiRequest
{
	public function executeApiCall($order): void
	{
		wp_schedule_single_event(
			time() + 15,
			SendCustomerEmail::JOB_NAME,
			[
				'orderId' => $order->get_id(),
				'responseDetails' => [
					SoloApiRequest::INVOICE => [
						'pdf' => 'asdasd.pdf',
						'broj_racuna' => '1-1-1',
						'broj_ponude' => '0001-0000',
					]
				],
				'email' => 'email@email.com',
				'billType' => SoloApiRequest::INVOICE,
				'paymentMethod' => 'bacs',
			]
		);
	}
}
