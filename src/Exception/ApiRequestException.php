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

use function esc_html;
use function esc_html__;

/**
 * Api request exception
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
	 * @param string|int $code Error code from the response.
	 * @param string $message Error message from the response.
	 *
	 * @return static
	 */
	public static function apiResponse($code, string $message)
	{
		$exceptionMessage = sprintf(
			/* translators: %1$s: error message, %2$s: error code. */
			esc_html__('API request error happened. %1$s. (Error code %2$s).', 'woo-solo-api'),
			esc_html($message),
			esc_html((string)$code)
		);

		return new static($exceptionMessage);
	}
}
