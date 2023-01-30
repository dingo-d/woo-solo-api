<?php

/**
 * File that holds Has_Activation interface
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

/**
 * Interface HasActivation
 *
 * An object that can be activated.
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */
interface HasActivation
{
	/**
	 * Activate the service.
	 *
	 * Used when adding certain capabilities of a service.
	 *
	 * Example: add_role, add_cap, etc.
	 *
	 * @return void
	 */
	public function activate(): void;
}
