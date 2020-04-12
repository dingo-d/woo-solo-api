<?php

declare(strict_types=1);

namespace Tests\Integration\AdminMenus;

use Brain\Monkey;
use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\AdminMenus\OptionsSubmenu;
use MadeByDenis\WooSoloApi\ECommerce\WooPaymentGateways;

class WooCommerceSubmenuTest extends WPTestCase
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

    public function setUp()
    {
        parent::setUp();
        Monkey\setUp();
    }

    public function tearDown()
    {
		Monkey\tearDown();
        parent::tearDown();
    }

	public function testWooCommerceSubmenuExists()
	{
		wp_set_current_user($this->factory()->user->create(['role' => 'administrator']));

		$siteUrl = getenv('TEST_SITE_WP_URL');

		$gateways = [
			'bacs' => 'bacs',
			'cheque' => 'cheque',
			'cod' => 'cod',
		];

		// Mock the gateways.
		$mockGateway = $this->createMock(WooPaymentGateways::class);

		// Configure the mock.
		$mockGateway->method('getAvailablePaymentGateways')
			->willReturn($gateways);

		(new OptionsSubmenu($mockGateway))->register();

		$this->assertEquals(
			"{$siteUrl}/wp-admin/admin.php?page=solo_api_options",
			\menu_page_url(OptionsSubmenu::MENU_SLUG, false)
		);
    }
}
