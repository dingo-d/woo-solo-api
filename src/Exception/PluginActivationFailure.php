<?php

/**
 * File containing the plugin activation failure exception class
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

use RuntimeException;

/**
 * Plugin activation failure exception
 *
 * Exception when plugin activation error happens.
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */
final class PluginActivationFailure extends RuntimeException implements GeneralException
{

	/**
	* Create a new instance of the exception in case plugin cannot be activated
	*
	* @param string $message Error message to show on plugin activation failure.
	*
	* @return static
	*/
	public static function activationMessage(string $message)
	{
		return new PluginActivationFailure($message);
	}
}
