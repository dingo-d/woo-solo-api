<?php

namespace Tests\WPUnit\Activation;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\Core\DiContainer;

class DIContainerTest extends WPTestCase
{
    /**
     * @var IntegrationTester
     */
    protected $tester;

	/**
	 * @var DiContainer
	 */
	private $container;

	public function setUp()
    {
        // Before...
        parent::setUp();

        $this->container = new DiContainer();

        // Your set up methods here.
    }

    public function tearDown()
    {
        // Your tear down methods here.

        // Then...
        parent::tearDown();
    }

	public function testServicesWillBePreparedCorrectly()
	{
		// Add some service classes.
		$services = [];

		$preparedServices = $this->container->getDiServices($services);


    }
}
