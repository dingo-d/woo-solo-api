<?php

declare(strict_types=1);

namespace Tests\Integration\AdminMenus;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\AdminMenus\OptionsSubmenu;

class OptionsSubmenuTest extends WPTestCase
{
	/**
	 * @var IntegrationTester
	 */
	protected $tester;

	/**
	 * @var OptionsSubmenu|\WP_UnitTest_Factory
	 */
	private $optionsSubmenu;

	public function setUp(): void
	{
		parent::setUp();

		$this->optionsSubmenu = new OptionsSubmenu();
	}

	public function tearDown(): void
	{
		parent::tearDown();
	}

	public function testOptionsSubmenuIsRegistered()
	{
		$this->optionsSubmenu->register();
		$this->optionsSubmenu->registerSubmenu('');

		ob_start();
		$this->optionsSubmenu->processAdminSubmenu([]);
		$view = ob_get_clean();

		// Output buffer keeps the formatting, so we need to add tabs and newlines.
		$this->assertEquals('<div class="wrap">
	<div id="solo-api-options-page"></div>
</div>
', $view);

		$hookname = get_plugin_page_hookname(OptionsSubmenu::MENU_SLUG, OptionsSubmenu::PARENT_MENU);

		$this->assertEquals('admin_page_solo_api_options', $hookname);
	}
}
