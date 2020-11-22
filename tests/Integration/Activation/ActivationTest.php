<?php

namespace Tests\Integration\Activation;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\Core\{Plugin, PluginFactory};

class ActivationTest extends WPTestCase
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

	public function testPluginFactoryReturnsPluginInstance()
	{
		$plugin = PluginFactory::create();

		$this->assertInstanceOf(Plugin::class, $plugin);
	}
}
