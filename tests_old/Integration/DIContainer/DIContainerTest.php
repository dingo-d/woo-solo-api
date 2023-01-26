<?php

namespace Tests\Integration\DIContainer;

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

	public function setUp(): void
    {
        parent::setUp();

        $this->container = new DiContainer();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

	public function testServicesWillBePreparedCorrectly()
	{
		// Add some service classes.
		$services = [];

		$preparedServices = $this->container->getDiServices($services);
    }
}
