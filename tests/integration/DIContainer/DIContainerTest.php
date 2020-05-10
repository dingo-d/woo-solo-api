<?php

declare(strict_types=1);

namespace Tests\Integration\Activation;

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
        parent::setUp();

        $this->container = new DiContainer();
    }

    public function tearDown()
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
