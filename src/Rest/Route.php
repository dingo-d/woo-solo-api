<?php

/**
 * File holding Route interface
 *
 * @package MadeByDenis\WooSoloApi\Rest
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest;

/**
 * Route interface
 *
 * @package MadeByDenis\WooSoloApi\Rest
 * @since 2.0.0
 */
interface Route
{
	/**
	 * Alias for GET transport method.
	 *
	 * @var string
	 */
	public const READABLE = 'GET';

	/**
	 * Alias for POST transport method.
	 *
	 * @var string
	 */
	public const CREATABLE = 'POST';

	/**
	 * Alias for PATCH transport method.
	 *
	 * @var string
	 */
	public const EDITABLE = 'PATCH';

	/**
	 * Alias for PUT transport method.
	 *
	 * @var string
	 */
	public const UPDATABLE = 'PUT';

	/**
	 * Alias for DELETE transport method.
	 *
	 * @var string
	 */
	public const DELETABLE = 'DELETE';
}
