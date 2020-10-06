<?php

/**
 * * File that holds Has_Deactivation interface
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

/**
 * Interface HasDeactivation
 *
 * An object that can be deactivated.
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */
interface HasDeactivation
{
  /**
   * Deactivate the service.
   *
   * Can be used to remove parts of the functionality defined by certain service.
   *
   * Examples: remove_role, remove_cap, flush_rewrite_rules etc.
   *
   * @return void
   */
    public function deactivate(): void;
}
