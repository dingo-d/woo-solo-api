<?php

namespace Tests\Integration\Core;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\Core\PluginFactory;

class CorePluginTest extends WPTestCase
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

	public function testPluginCoreFunctionalityWorks()
	{
		$pluginInstance = PluginFactory::create();
		$pluginInstance->register();
		$pluginInstance->registerAssetsManifestData();
		$pluginInstance->registerServices();
		$pluginInstance->activate();
		$pluginInstance->deactivate();

		try {
			$reflection = new \ReflectionClass($pluginInstance);
		} catch (\ReflectionException $e) {
		}

		$privateServicesList = $reflection->getProperty('services');
		$privateServicesList->setAccessible(true);
		$services = $privateServicesList->getValue($pluginInstance);

		$privateServicesMethod = $reflection->getMethod('getServiceClasses');
		$privateServicesMethod->setAccessible(true);
		$serviceClassesList = $privateServicesMethod->invokeArgs($pluginInstance, []);

		$serviceList = [];

		foreach ($serviceClassesList as $classListKey => $classListValue) {
			if (gettype($classListKey) !== 'string') {
				$serviceList[$classListValue] = $classListValue;
			} else {
				$serviceList[$classListKey] = $classListValue;
			}
		}

		foreach ($services as $service) {
			$class = get_class($service);
			$this->assertTrue(isset($serviceList[$class]));
		}

		$this->assertNotEmpty(ASSETS_MANIFEST);
	}
}
