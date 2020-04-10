<?php

namespace Tests\WPUnit\Activation;

use Codeception\TestCase\WPTestCase;
use MadeByDenis\WooSoloApi\Core\Plugin;
use IntegrationTester;

class ActivationTest extends WPTestCase
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

    public function setUp()
    {
        // Before...
        parent::setUp();

        // Your set up methods here.
    }

    public function tearDown()
    {
        // Your tear down methods here.

        // Then...
        parent::tearDown();
    }

	public function testPluginCanBeActivated()
	{
		$plugin = new Plugin();

		$this->assertInstanceOf(Plugin::class, $plugin);
    }
}
