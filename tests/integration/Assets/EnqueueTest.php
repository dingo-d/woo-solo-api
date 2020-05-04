<?php

declare(strict_types=1);

namespace Tests\Integration\Enqueue;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\Assets\EnqueueResources;

class EnqueueTest extends WPTestCase
{
	/**
	 * @var IntegrationTester
	 */
	protected $tester;

	public function setUp()
	{
		parent::setUp();
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testAssetsAreEnqueued()
	{
		(new EnqueueResources())->register();

		set_current_screen('woocommerce_page_solo_api_options');

		$scripts = get_echo('wp_print_scripts');
	}
}
