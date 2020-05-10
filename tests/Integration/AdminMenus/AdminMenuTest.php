<?php

declare(strict_types=1);

namespace Tests\Integration\AdminMenus;

use IntegrationTester;
use Exception;
use Codeception\TestCase\WPTestCase;
use MadeByDenis\WooSoloApi\AdminMenus\AdminMenu;

class AdminMenuTest extends WPTestCase
{
	/**
	 * @var IntegrationTester
	 */
	protected $tester;

	/**
	 * @var AdminMenu|\WP_UnitTest_Factory
	 */
	private $mockAdminMenu;

	public function setUp()
	{
		parent::setUp();

		$this->mockAdminMenu = new class extends AdminMenu {
			public const CAPABILITY = 'manage_options';
			public const MENU_SLUG = 'mock_menu';
			public const VIEW_URI = 'fake/path';

			protected function getTitle(): string
			{
				return __('Mock Menu', 'woo-solo-api');
			}

			protected function getMenuTitle(): string
			{
				return __('Mock Menu', 'woo-solo-api');
			}

			protected function getCapability(): string
			{
				return self::CAPABILITY;
			}

			protected function getMenuSlug(): string
			{
				return self::MENU_SLUG;
			}

			protected function getViewUri(): string
			{
				return self::VIEW_URI;
			}

			protected function processAttributes($attr): array
			{
				$attr = (array) $attr;

				return $attr;
			}
		};
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testAdminMenuShowsErrorIfViewIsNotPresent()
	{
		$this->mockAdminMenu->register();
		$this->mockAdminMenu->registerAdminMenu('');
		$this->mockAdminMenu->processAdminMenu('');
		$message = $this->mockAdminMenu->render([]);

		$expectedMessage = sprintf(
			'<pre>The View URI "%s.php" is not accessible or readable.</pre>',
			$this->mockAdminMenu::VIEW_URI
		);

		$this->assertEquals($expectedMessage, $message);
	}
}
