<?php

/**
 * File that holds Has_Activation interface
 *
 * @since 2.0.0
 * @package Developer_Portal\Core
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

/**
 * Interface Has_Activation.
 *
 * An object that can be activated.
 *
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
