<?php

/**
 * File containing the invalid service exception class
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\Exception
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

class InvalidService extends \InvalidArgumentException implements GeneralException
{
	/**
     * Create a new instance of the exception for a service class name that is
     * not recognized.
     *
     * @param string $service Class name of the service that was not recognized.
     *
     * @return static
     */
	public static function fromService($service)
	{
		$message = sprintf(
			'The service "%s" is not recognized and cannot be registered.',
			is_object( $service ) ? get_class( $service ) : (string) $service
		);

		return new static($message);
	}
}
