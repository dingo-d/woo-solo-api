<?php

/**
 * File holding MakeSoloApiCall class
 *
 * @package MadeByDenis\WooSoloApi\BackgroundJobs
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\BackgroundJobs;

use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\Exception\OrderValidationException;
use MadeByDenis\WooSoloApi\Request\ApiRequest;
use RuntimeException;
use TypeError;

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
	 * @var ApiRequest
	 */
	private $soloRequest;

	/**
	 * MakeSoloApiCall constructor.
	 *
	 * @param ApiRequest $soloRequest Request dependency.
	 */
	public function __construct(ApiRequest $soloRequest)
	{
		$this->soloRequest = $soloRequest;
	}

	/**
	 * @inheritDoc
	 */
	public function registerProcess(...$args)
	{
		$order = $args[0];

		try {
			$this->soloRequest->executeApiCall($order);
		} catch (RuntimeException | OrderValidationException | TypeError $e) {
			// Write error to the database for better logging.
			SoloOrdersTable::addApiResponseError($order->get_id(), $e->getMessage()); // @phpstan-ignore-line
		}
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
