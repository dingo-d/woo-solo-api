<?php

/**
 * File containing the missing manifest exception class
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

use InvalidArgumentException;

/**
 * Missing manifest exception
 *
 * Exception when manifest.json is missing. Usually means that assets are not bundled correctly.
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */
final class MissingManifest extends InvalidArgumentException implements GeneralException
{
	/**
     * Create a new instance of the exception in case a manifest file is missing.
     *
     * @param string $message Error message to show on thrown exception.
     *
     * @return static
     */
	public static function message(string $message)
	{
		return new MissingManifest($message);
	}
}
