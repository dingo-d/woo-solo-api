<?php

namespace Tests\WPUnit\Activation;

use Codeception\TestCase\WPTestCase;
use MadeByDenis\WooSoloApi\Core\Plugin;

class ActivationTest extends WPTestCase
{
    /**
     * @var \WpunitTester
     */
    protected $tester;

    public function setUp()
    {
        // Before...
        parent::setUp();
		error_log( print_r( _get_dropins(), true ) );
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

	public function testActionSchedulerWorks()
	{
		error_log( print_r( $GLOBALS['wp_actions'], true ) );
    }
}
