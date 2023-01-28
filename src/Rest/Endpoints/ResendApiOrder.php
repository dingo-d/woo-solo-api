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
use MadeByDenis\WooSoloApi\BackgroundJobs\SendCustomerEmail;
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\Request\ApiRequest;
use MadeByDenis\WooSoloApi\Rest\BaseRoute;
use MadeByDenis\WooSoloApi\Rest\RestCallable;
use WP_Error;
use WP_REST_Request;

use function get_option;

/**
 * Account details endpoint
 *
 * This class holds the callback function for the REST endpoint used to check the details from SOLO API.
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.0.0
 */
class ResendCustomerEmail extends BaseRoute implements RestCallable
{
	use IsUserLoggedIn;

	public const ROUTE_NAME = '/resend-customer-email';

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

			$data = [
				'code'    => 'success',
				'message' => esc_html__('API call to SOLO service executed', 'woo-solo-api'),
				'data'    => [
					'status' => 200,
					'message' => esc_html__('API call to SOLO service executed', 'woo-solo-api'),
				]
			];
		} catch(Exception $exception) {
			$data = [
				'code'    => 'rest_exception',
				'message' => $exception->getMessage(),
				'data'    => [
					'status' => $exception->getCode(),
					'message' => $exception->getMessage()
				]
			];
		}
error_log(print_r($data, true));
		return rest_ensure_response($data);
	}
}
