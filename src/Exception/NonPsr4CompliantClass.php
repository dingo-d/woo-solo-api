<?php

/**
 * File containing the failure exception class when trying to autowire a class that's not PSR-4 compliant
 *
 * Taken from the following link, licenced under MIT.
 * @link https://raw.githubusercontent.com/infinum/eightshift-libs/develop/src/Exception/NonPsr4CompliantClass.php
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @license https://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

use InvalidArgumentException;

/**
 * Non Psr4 Compliant exception
 */
final class NonPsr4CompliantClass extends InvalidArgumentException implements GeneralException
{
	/**
	 * Throws exception if class has non psr-4 compliant namespace.
	 *
	 * @param string $className Class name we're looking for.
	 * @return static
	 */
	public static function throwInvalidNamespace(string $className): NonPsr4CompliantClass
	{
		return new NonPsr4CompliantClass(
			\sprintf(
				/* translators: %s is replaced with the className. */
				esc_html__('Unable to autowire %s. Please check if the namespace is PSR-4 compliant (i.e. it needs to match the folder structure).
				See: https://www.php-fig.org/psr/psr-4/#3-examples', 'woo-solo-api'),
				$className
			)
		);
	}
}
