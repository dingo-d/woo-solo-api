<?php

/**
 * File holding ErrorCodes trait
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Utils
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Utils;

/**
 * ErrorCodes trait
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Utils
 */
trait ErrorCodes
{
	public function getErrorMessage(string $reason)
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
