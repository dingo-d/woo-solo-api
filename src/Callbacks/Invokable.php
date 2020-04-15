<?php

/**
 * File holding Invokable interface
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Callbacks
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Callbacks;

/**
 * Invokable interface
 *
 * An object that can be invoked or called (AJAX callback).
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Callbacks
 */
interface Invokable
{
	/**
     * Callback of the current Invokable.
     */
	public function callback();
}
