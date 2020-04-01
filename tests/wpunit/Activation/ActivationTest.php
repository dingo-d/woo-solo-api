<?php

namespace Tests\WPUnit\Activation;

use MadeByDenis\WooSoloApi\Core\Plugin;

class ActivationTest extends \Codeception\TestCase\WPTestCase
{
    /**
     * @var \WpunitTester
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

    // Tests
    public function testItWorks()
    {
        $post = static::factory()->post->create_and_get();

        $this->assertInstanceOf(\WP_Post::class, $post);
    }

	public function testPluginCanBeActivated()
	{
		$plugin = new Plugin();

		$this->assertInstanceOf(Plugin::class, $plugin);
    }
}
