<?php

/**
 * File that holds the registrable interface.
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

/**
 * Registrable interface
 *
 * An object that can be registered.
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */
interface Registrable
{
	/**
	 * Register the current Registrable.
	 *
	 * A register method holds the plugin action and filter hooks.
	 * Following the single responsibility principle, every class
	 * holds a functionality for a certain part of the plugin.
	 * This is why every class should hold its own hooks.
	 *
	 * @return void
	 */
	public function register(): void;
}
