<?php

/**
 * File holding MakeSoloApiCall class
 *
 * @package MadeByDenis\WooSoloApi\BackgroundJobs
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\BackgroundJobs;

use MadeByDenis\WooSoloApi\Request\SoloApiRequest;

/**
 * Call towards SOLO API
 *
 * This class holds logic for executing background job that makes API
 * call towards SOLO API.
 *
 * This is done in order to prevent API rejections when multiple orders have
 * their status changed to completed at once.
 * On order complete status jobs will be queued with 5 second interval between them.
 * This should, in theory, prevent API throttling.
 *
 * @package MadeByDenis\WooSoloApi\BackgroundJobs
 * @since 2.0.0
 */
class MakeSoloApiCall extends ScheduleEvent
{

	/**
	 * @var string Name of the custom job.
	 */
	public const JOB_NAME = 'makeApiCall';

	/**
	 * @var SoloApiRequest
	 */
	private $soloRequest;

	/**
	 * MakeSoloApiCall constructor.
	 * @param SoloApiRequest $soloRequest
	 */
	public function __construct(SoloApiRequest $soloRequest)
	{
		$this->soloRequest = $soloRequest;
	}

	/**
	 * @inheritDoc
	 */
	public function registerProcess(...$args)
	{
		$order = $args[0];

		$this->soloRequest->executeSoloApiCall($order);
	}

	/**
	 * @inheritDoc
	 */
	protected function getJobName(): string
	{
		return self::JOB_NAME;
	}

	/**
	 * @inheritDoc
	 */
	protected function getArgumentsCount(): int
	{
		return 1;
	}
}
