<?php

namespace Tests\Integration\Admin;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\Admin\PluginsPage;

class PluginsPageLinkTest extends WPTestCase
{
	/**
	 * @var IntegrationTester
	 */
	protected $tester;

	public function setUp(): void
	{
		parent::setUp();
	}

	public function tearDown(): void
	{
		parent::tearDown();
	}

	public function testSettingsLinkIsShown()
	{
		$page = new PluginsPage();

		$page->register();
		$links = $page->addActionLink([]);

		$this->assertIsArray($links, 'Returned value is not array');

		foreach ($links as $link) {
			$this->assertEquals('<a href="http://dev.wordpress.test/wp-admin/admin.php?page=solo_api_options">SOLO API Settings</a>', $link, 'Settings link is not the same');
		}
	}
}
