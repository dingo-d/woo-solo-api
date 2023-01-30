<?php

/**
 * Autowire dependency exception class
 *
 * File containing the failure exception class when trying to inject interface based dependencies into a class (using autowiring)
 * but failing to provide the correct variable name by which we can find a class to inject.
 *
 * Taken from the following link, licenced under MIT.
 * @link https://raw.githubusercontent.com/infinum/eightshift-libs/develop/src/Exception/InvalidAutowireDependency.php
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @license https://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

use InvalidArgumentException;

/**
 * Class InvalidAutowireDependency
 */
final class InvalidAutowireDependency extends InvalidArgumentException implements GeneralException
{
	/**
	 * Throws exception if we can't guess the class to inject.
	 *
	 * @param string $className Class name we're looking for.
	 * @param string $interfaceName Class we're looking for needs to implement this.
	 *
	 * @return static
	 */
	public static function throwUnableToFindClass(string $className, string $interfaceName): InvalidAutowireDependency
	{
		return new InvalidAutowireDependency(
			\sprintf(
				/* translators: 1: the className, 2: the interface name. */
				esc_html__('Unable to find "%1$s" class that implements %2$s (looking in $filenameIndex).', 'woo-solo-api'),
				$className,
				$interfaceName
			)
		);
	}

	/**
	 * Throws an exception if we can't guess the class to inject
	 * because we found more than 1 with the same name that implements $interfaceName.
	 *
	 * @param string $className Class name we're looking for.
	 * @param string $interfaceName Class we're looking for needs to implement this.
	 *
	 * @return static
	 */
	public static function throwMoreThanOneClassFound(string $className, string $interfaceName): InvalidAutowireDependency
	{
		return new InvalidAutowireDependency(
			\sprintf(
				/* translators: 1: The class name, 2: The interface name, 3: The interface name. */
				esc_html__('Found more than 1 class called "%1$s" that implements %2$s interface.', 'woo-solo-api'),
				$className,
				$interfaceName,
				$interfaceName
			)
		);
	}

	/**
	 * Throws an exception if we find a primitive dependency
	 * on a class that's not been manually built.
	 *
	 * @param string $className Class name we're looking for.
	 * @param string $param Parameter name that is causing the issue.
	 *
	 * @return static
	 */
	public static function throwPrimitiveDependencyFound(string $className, string $param): InvalidAutowireDependency
	{
		return new InvalidAutowireDependency(
			\sprintf(
				/* translators: 1: %s is replaced with the className and interfaceName, 2: %s is replaced with the parameter name. */
				esc_html__('Found a primitive dependency for %1$s with param %2$s. Autowire is unable to figure out what value needs to be injected here.', 'woo-solo-api'),
				$className,
				$param
			)
		);
	}
}
