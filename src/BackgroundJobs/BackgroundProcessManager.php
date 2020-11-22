<?php

/**
 * File that holds cron event interface
 *
 * @package MadeByDenis\WooSoloApi\BackgroundJobs
 * @since   2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\BackgroundJobs;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * Background process manager interface
 *
 * Used to define background jobs that will be run regularly.
 *
 * @package MadeByDenis\WooSoloApi\Background_Jobs
 * @since   2.0.0
 */
interface BackgroundProcessManager extends Registrable
{
    /**
     * Register the process you wish to run in the background
     *
     * @return mixed
     */
    public function registerProcess();
}
