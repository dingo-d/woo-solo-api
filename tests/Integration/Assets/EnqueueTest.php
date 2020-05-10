<?php

declare(strict_types=1);

namespace Tests\Integration\Assets;

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

	/**
	 * @var EnqueueResources|\WP_UnitTest_Factory
	 */
	private $enqueueClass;

	public function setUp()
	{
		parent::setUp();

		$this->oldWpScripts = $GLOBALS['wp_scripts'] ?? null;

		remove_action('wp_default_scripts', 'wp_default_scripts');
		remove_action('wp_default_scripts', 'wp_default_packages');

		$GLOBALS['wp_scripts'] = new \WP_Scripts();
		$GLOBALS['wp_scripts']->default_version = get_bloginfo('version');

		$this->enqueueClass = new EnqueueResources();
	}

	public function tearDown()
	{
		$GLOBALS['wp_scripts'] = $this->oldWpScripts;
		add_action('wp_default_scripts', 'wp_default_scripts');

		// Cleanup after every test.
		wp_dequeue_style(EnqueueResources::CSS_HANDLE);
		wp_dequeue_script(EnqueueResources::JS_HANDLE);

		parent::tearDown();
	}

	public function testAssetsAreEnqueued()
	{
		global $wp_styles, $wp_scripts;
		set_current_screen(OptionsSubmenu::WOO_PAGE_ID);

		$this->enqueueClass->register();

		$this->enqueueClass->enqueueScripts(OptionsSubmenu::WOO_PAGE_ID);
		$this->enqueueClass->enqueueStyles(OptionsSubmenu::WOO_PAGE_ID);

		$this->assertContains(EnqueueResources::CSS_HANDLE, $wp_styles->queue);
		$this->assertContains(EnqueueResources::JS_HANDLE, $wp_scripts->queue);
	}

	public function testAssetsAreNotEnqueuedOnDifferentScreen()
	{
		global $wp_styles, $wp_scripts;
		set_current_screen('dashboard');

		$this->enqueueClass->register();

		$this->enqueueClass->enqueueScripts('dashboard');
		$this->enqueueClass->enqueueStyles('dashboard');

		$this->assertNotContains(EnqueueResources::CSS_HANDLE, $wp_styles->queue);
		$this->assertNotContains(EnqueueResources::JS_HANDLE, $wp_scripts->queue);
	}

	public function testScriptTranslationWorks()
	{
		global $wp_scripts;
		set_current_screen(OptionsSubmenu::WOO_PAGE_ID);

		$this->enqueueClass->enqueueScripts(OptionsSubmenu::WOO_PAGE_ID);

		$this->enqueueClass->setScriptTranslations();

		$this->assertEquals('woo-solo-api', $wp_scripts->registered[EnqueueResources::JS_HANDLE]->textdomain);
	}
}
