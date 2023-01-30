<?php

/**
 * File that holds scheduled events abstract class
 *
 * @package MadeByDenis\WooSoloApi\BackgroundJobs
 * @since   2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\BackgroundJobs;

use function add_action;

/**
 * Schedule event template class
 *
 * Abstract class used for creating any background job.
 *
 * @package MadeByDenis\WooSoloApi\Background_Jobs
 * @since   2.0.0
 */
abstract class ScheduleEvent implements BackgroundProcessManager
{

	/**
	 * Register scheduled jobs
	 *
	 * @link https://developer.wordpress.org/plugins/cron/scheduling-wp-cron-events/
	 *
	 * @return void
	 */
	public function register(): void
	{
		add_action($this->getJobName(), [$this, 'registerProcess'], $this->getPriority(), $this->getArgumentsCount());
	}

	/**
	 * Register the process you wish to run in the background
	 *
	 * @param mixed ...$args Arguments to pass to the background job callback.
	 * @return void
	 */
	abstract public function registerProcess(...$args);

	/**
	 * Get name of the background job
	 *
	 * @return string Name of the background job.
	 */
	abstract protected function getJobName(): string;

	/**
	 * Get the priority for the callback
	 *
	 * Default: 10
	 *
	 * @return int
	 */
	protected function getPriority(): int
	{
		return 10;
	}

	/**
	 * Number of arguments that will be passed to callback
	 *
	 * Default: 1
	 *
	 * @return int Number of argument the callback will take.
	 */
	protected function getArgumentsCount(): int
	{
		return 1;
	}
}
