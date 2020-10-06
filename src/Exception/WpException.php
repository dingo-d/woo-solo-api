<?php

/**
 * File containing WordPress exception class
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

use RuntimeException;

/**
 * Wp exception
 *
 * Exception when a WordPress error happens.
 * This will be thrown inside \is_wp_error() conditions.
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */
final class WpException extends RuntimeException implements GeneralException
{

	/**
	 * Create a new instance of the exception in case plugin cannot be activated
	 *
	 * @param string|int $code Error code from the response.
	 * @param string $message Error message from the response.
	 *
	 * @return static
	 */
	public static function internalError($code, string $message)
	{
		$exceptionMessage = sprintf(
			esc_html__('WordPress internal error happened. %1$s. (Error code %2$s).', 'woo-solo-api'),
			esc_html($message),
			esc_html((string)$code)
		);

		return new static($exceptionMessage);
	}
}
