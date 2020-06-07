<?php

declare(strict_types=1);

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

	public function setUp()
	{
		parent::setUp();
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	public function testPluginFactoryReturnsPluginInstance()
	{
		$plugin = PluginFactory::create();

		$this->assertInstanceOf(Plugin::class, $plugin);
	}
}
