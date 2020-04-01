<?php

/**
 * File containing the invalid service exception class
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\Exception
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

/**
 * Class MissingManifest
 *
 * Exception when manifest.json is missing. Usually means that assets are not bundled correctly.
 *
 * @package MadeByDenis\WooSoloApi\Exception
 */
class MissingManifest extends \InvalidArgumentException implements GeneralException
{
	/**
     * Create a new instance of the exception in case
	 * a manifest file is missing.
     *
     * @param string $message Error message to show on thrown exception.
     *
     * @return static
     */
	public static function message($message)
	{
		return new static($message);
	}
}
