<?php

/**
 * File holding ErrorCodes trait
 *
 * @package MadeByDenis\WooSoloApi\Utils
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Utils;

/**
 * ErrorCodes trait
 *
 * @package MadeByDenis\WooSoloApi\Utils
 * @since 2.0.0
 */
trait ErrorCodes
{
	/**
	 * Error message helper
	 *
	 * Returns the error message based on the type of error.
	 *
	 * @param string $reason Error message identifier.
	 *
	 * @return string Error message string.
	 */
	public function getErrorMessage(string $reason): string
	{
		switch ($reason) {
			case 'nonce':
				$message = esc_html__('Number not only once is invalid.', 'woo-solo-api');
				break;
			case 'authorization':
				$message = esc_html__('User is not authorized to do this action.', 'woo-solo-api');
				break;
			default:
				$message = esc_html__('Error occurred', 'woo-solo-api');
				break;
		}

		return $message;
	}
}
