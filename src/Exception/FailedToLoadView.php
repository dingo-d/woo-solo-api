<?php

/**
 * File containing the failed to load view exception class
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Exception;

use Exception;
use RuntimeException;

/**
 * Failed to load view
 *
 * Exception thrown when a view file cannot be found.
 *
 * @package MadeByDenis\WooSoloApi\Exception
 * @since 2.0.0
 */
final class FailedToLoadView extends RuntimeException implements GeneralException
{

	/**
	 * Create a new instance of the exception if the view file itself created
	 * an exception.
	 *
	 * @param string    $uri       URI of the file that is not accessible or not readable.
	 * @param Exception $exception Exception that was thrown by the view file.
	 *
	 * @return static
	 */
	public static function viewException(string $uri, Exception $exception)
	{
		$message = sprintf(
			/* translators: 1. View URI, 2. Reason for not exception. */
			esc_html__('Could not load the View URI "%1$s". Reason: "%2$s".', 'woo-solo-api'),
			$uri,
			$exception->getMessage()
		);

		return new FailedToLoadView($message, $exception->getCode(), $exception);
	}
}
