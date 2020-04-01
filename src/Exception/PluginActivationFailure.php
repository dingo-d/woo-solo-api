<?php

/**
 * File containing the plugin activation failure exception class
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\Exception
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

/**
 * Class PluginActivationFailure
 *
 * Exception when plugin activation error happens
 *
 * @package MadeByDenis\WooSoloApi\Exception
 */
class PluginActivationFailure extends \RuntimeException implements GeneralException
{

	/**
	* Create a new instance of the exception in case plugin cannot be activated
	*
	* @param string $message Error message to show on plugin activation failure.
	*
	* @return static
	*/
	public static function activationMessage($message)
	{
		return new static($message);
	}
}
