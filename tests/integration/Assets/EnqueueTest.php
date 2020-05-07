<?php

declare(strict_types=1);

namespace Tests\Integration\Enqueue;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\AdminMenus\OptionsSubmenu;
use MadeByDenis\WooSoloApi\Assets\EnqueueResources;

class EnqueueTest extends WPTestCase
{
	/**
	 * @var IntegrationTester
	 */
	protected $tester;

	/**
	 * @var mixed|\WP_UnitTest_Factory|null
	 */
	private $oldWpScripts;

	public function setUp()
	{
		parent::setUp();

		$this->oldWpScripts = isset($GLOBALS['wp_scripts']) ? $GLOBALS['wp_scripts'] : null;
		remove_action('wp_default_scripts', 'wp_default_scripts');
		remove_action('wp_default_scripts', 'wp_default_packages');
		$GLOBALS['wp_scripts'] = new \WP_Scripts();
		$GLOBALS['wp_scripts']->default_version = get_bloginfo('version');
	}

	public function tearDown()
	{
		$GLOBALS['wp_scripts'] = $this->old_wp_scripts;
		add_action('wp_default_scripts', 'wp_default_scripts');

		parent::tearDown();
	}
	
	public function testAssetsAreEnqueued()
	{
		global $wp_styles, $wp_scripts;
		set_current_screen(OptionsSubmenu::WOO_PAGE_ID);

		(new EnqueueResources())->register();

		(new EnqueueResources())->enqueueScripts(OptionsSubmenu::WOO_PAGE_ID);
		(new EnqueueResources())->enqueueStyles(OptionsSubmenu::WOO_PAGE_ID);

		$this->assertContains(EnqueueResources::CSS_HANDLE, $wp_styles->queue);
		$this->assertContains(EnqueueResources::JS_HANDLE, $wp_scripts->queue);
	}
	
	public function testAssetsAreNotEnqueuedOnDifferentScreen()
	{
		global $wp_styles, $wp_scripts;
		set_current_screen('dashboard');

		(new EnqueueResources())->register();

		(new EnqueueResources())->enqueueScripts('dashboard');
		(new EnqueueResources())->enqueueStyles('dashboard');

		$this->assertNotContains(EnqueueResources::CSS_HANDLE, $wp_styles->queue);
		$this->assertNotContains(EnqueueResources::JS_HANDLE, $wp_scripts->queue);
	}
}
