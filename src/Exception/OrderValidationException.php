<?php

/**
 * File holding OrderValidationException class
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

use InvalidArgumentException;

/**
 * Order validation exception
 *
 * Exception that will be thrown in case there is an error with the order.
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since
 */
final class OrderValidationException extends InvalidArgumentException implements GeneralException
{
	/**
	 * Message thrown in case there is a problem with an order
	 *
	 * @param mixed $order Order value.
	 *
	 * @return static
	 */
	public static function invalidOrderType($order)
	{
		$message = sprintf(
			'Order expected, %s returned.',
			gettype($order)
		);

		return new OrderValidationException($message);
	}
}
