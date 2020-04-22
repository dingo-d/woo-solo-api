<?php

/**
 * File holding Route interface
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Rest
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest;

/**
 * Route interface
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Rest
 */
interface Route
{
	/**
	 * Alias for GET transport method.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public const READABLE = 'GET';

	/**
	 * Alias for POST transport method.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public const CREATABLE = 'POST';

	/**
	 * Alias for PATCH transport method.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public const EDITABLE = 'PATCH';

	/**
	 * Alias for PUT transport method.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public const UPDATEABLE = 'PUT';

	/**
	 * Alias for DELETE transport method.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public const DELETABLE = 'DELETE';
}
