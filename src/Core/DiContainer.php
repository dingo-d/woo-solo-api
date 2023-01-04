<?php

/**
 * File containing the DI container
 *
 * @package Made_By_Denis\Woo_Solo_Api\Core
 * @since   2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

use DI\Container;
use DI\ContainerBuilder;
use Exception;

use function DI\autowire;
use function DI\get;

/**
 * A dependency injection container implementation
 *
 * @package Made_By_Denis\Woo_Solo_Api\Core
 * @since   2.0.0
 */
final class DiContainer
{

	/**
	 * Return a prepared list of services with definitions for DI container
	 *
	 * @since 2.0.0
	 *
	 * @param array $services List of service classes.
	 *
	 * @return array  Definition list of services for DI container.
	 * @throws Exception Error in the case there is no DI container.
	 */
	public function getDiServices(array $services): array
	{
		$di_services = $this->getPreparedServiceArray($services);
		$container = $this->getDiContainer($di_services);

		return array_map(
			static function ($class) use ($container) {
				return $container->get($class);
			},
			array_keys($di_services)
		);
	}

	/**
	 * Return a DI container
	 *
	 * Build and return a DI container.
	 * Wire all the dependencies automatically, based on the provided array of
	 * class => dependencies from the get_di_services().
	 *
	 * @since 2.0.0
	 *
	 * @param array $services Array of service.
	 *
	 * @return Container Container instance.
	 * @throws Exception Throws exception in case of DI error.
	 */
	private function getDiContainer(array $services)
	{
		$builder = new ContainerBuilder();

		if (!$this->isDevelopment()) {
			$builder->enableCompilation(__DIR__);
		}

		$definitions = [];

		foreach ($services as $service_name => $service_dependencies) {
			if (!is_array($service_dependencies)) {
				continue;
			}

			$definitions[$service_name] = autowire()->constructor(...$this->getDiDependencies($service_dependencies));
		}

		return $builder->addDefinitions($definitions)->build();
	}

	/**
	 * Get dependencies from PHP-DI
	 *
	 * Return prepared Dependency Injection objects.
	 * If you pass a class use PHP-DI to prepare if not just output it.
	 *
	 * @since 2.0.0
	 *
	 * @param array $dependencies Array of classes/parameters to inject in constructor.
	 *
	 * @return array Array of dependencies.
	 */
	private function getDiDependencies(array $dependencies): array
	{
		return array_map(
			function ($dependency) {
				if (class_exists($dependency)) {
					return get($dependency);
				}

				return $dependency;
			},
			$dependencies
		);
	}

	/**
	 * Prepare services array
	 *
	 * Takes an argument of services, which is a multidimensional array, that has a class name for a key,
	 * and a list of dependencies as a value, or no value at all.
	 * It then loops though this array, and if the dependencies are an array it will just add this to
	 * the value of the $prepared_services array, and the key will be the class name.
	 * In case that there is no dependency.
	 *
	 * @since 2.0.0
	 *
	 * @param array $services A list of classes with optional dependencies.
	 *
	 * @return array
	 */
	private function getPreparedServiceArray(array $services): array
	{
		$prepared_services = [];

		foreach ($services as $class => $dependencies) {
			if (is_array($dependencies)) {
				$prepared_services[$class] = $dependencies;
				continue;
			}

			$prepared_services[$dependencies] = [];
		}

		return $prepared_services;
	}

	/**
	 * Helper to determine if we should compile the container
	 *
	 * Only compile container when on production, not when testing or in development.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function isDevelopment(): bool
	{
		$development = getenv('DEVELOPMENT') ||
			(defined('DEVELOPMENT') && DEVELOPMENT) ||
			(defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'development');
		$testing = getenv('TEST') || (defined('TEST') && TEST);

		if ($development || $testing) {
			return true;
		}

		return false;
	}
}
