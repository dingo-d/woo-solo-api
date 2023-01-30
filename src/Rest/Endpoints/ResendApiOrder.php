<?php

/**
 * File holding ResendCustomerEmail class
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.3.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest\Endpoints;

use Exception;
use MadeByDenis\WooSoloApi\Request\ApiRequest;
use MadeByDenis\WooSoloApi\Rest\BaseRoute;
use MadeByDenis\WooSoloApi\Rest\RestCallable;
use WP_Error;
use WP_REST_Request;

/**
 * Account details endpoint
 *
 * This class holds the callback function for the REST endpoint used to check the details from SOLO API.
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.0.0
 */
class ResendApiOrder extends BaseRoute implements RestCallable
{
	use IsUserLoggedIn;

	public const ROUTE_NAME = '/resend-api-order';

	private ApiRequest $soloApiRequest;

	/**
	 * Class dependencies
	 *
	 * @param ApiRequest $soloApiRequest Solo Api request dependency.
	 */
	public function __construct(ApiRequest $soloApiRequest)
	{
		$this->soloApiRequest = $soloApiRequest;
	}

	/**
	 * @inheritDoc
	 */
	protected function getCallbackArguments(): array
	{
		return [
			'methods' => static::CREATABLE,
			'callback' => [$this, 'restCallback'],
			'permission_callback' => [$this, 'canUserAccessEndpoint'],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function restCallback(WP_REST_Request $request)
	{
		// Get the information about the account from the details.
		$orderId = (int) $request->get_param('orderID');

		// Check if the order ID is not empty.
		if (empty($orderId)) {
			return new WP_Error(esc_html__('Order ID is empty.', 'woo-solo-api'));
		}

		// Check if order exists.
		$orderObject = wc_get_order($orderId);
		if (empty($orderObject)) {
			return new WP_Error(
				sprintf(
					/* translators: %d is the order ID. */
					esc_html__('Order with the ID %d doesn\'t exist.', 'woo-solo-api'),
					$orderId
				)
			);
		}

		try {
			$this->soloApiRequest->executeApiCall($orderObject);
			$message = esc_html__('API call to SOLO service executed, please wait a bit for it to execute, then reload this page.', 'woo-solo-api');
			$data = [
				'code'    => 'success',
				'message' => $message,
				'data'    => [
					'status' => 200,
					'message' => $message,
				]
			];
		} catch (Exception $exception) {
			$data = new WP_Error(
				'api_exception',
				$exception->getMessage(),
				[
					'code' => $exception->getCode(),
					'message' => $exception->getMessage(),
				]
			);
		}

		return rest_ensure_response($data);
	}
}
