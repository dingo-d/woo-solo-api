<?php

/**
 * File containing the api request exception class
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

use RuntimeException;

/**
 * Class ApiRequestException
 *
 * Exception when an error happens in the request towards the SOLO API
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */
final class ApiRequestException extends RuntimeException implements GeneralException
{

	/**
	 * Create a new instance of the exception in case plugin cannot be activated
	 *
	 * @param string $code Error code from the response.
	 * @param string $message Error message from the response.
	 *
	 * @return static
	 */
	public static function apiResponse($code, $message)
	{
		$exceptionMessage = sprintf(
			esc_html__('API request error happened. %1$s. (Error code %2$s).', 'woo-solo-api'),
			esc_html($message),
			esc_html($code)
		);

		return new static($exceptionMessage);
	}
}
