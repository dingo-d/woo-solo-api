<?php

/**
 * File holding OrderDetails class
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest\Endpoints;

use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\Rest\BaseRoute;
use MadeByDenis\WooSoloApi\Rest\RestCallable;
use WP_REST_Request;

/**
 * Order details endpoint
 *
 * This class holds the callback function for the REST endpoint used to get order details by ID.
 * These details should be only visible to admin logged in users as they are shown only in the admin
 * back end.
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 2.0.0
 */
class OrderDetails extends BaseRoute implements RestCallable
{
	/**
	 * @var string
	 */
	public const ROUTE_NAME = OrderDetailsCollection::ROUTE_NAME . '/(?P<id>[\d]+)';

	/**
	 * @var SoloOrdersTable
	 */
	private $database;

	public function __construct(SoloOrdersTable $database)
	{
		$this->database = $database;
	}

	/**
	 * Prepare order item for REST response
	 *
	 * @param array $order Order details array
	 * @return array
	 */
	public static function prepareItemForOutput(array $order)
	{
		$id = $order['id'];
		$wcOrderId = $order['order_id'];

		$base = sprintf('%s/%s', self::NAMESPACE_NAME . self::VERSION, ltrim(OrderDetailsCollection::ROUTE_NAME, '/'));

		// Entity meta.
		$links = [
			'self' => [
				'href' => rest_url(trailingslashit($base) . $id),
			],
			'collection' => [
				'href' => rest_url($base),
			],
			'wc:order' => [
				'href' => rest_url('wc/v3/orders/' . $wcOrderId),
			],
		];

		$order['_links'] = $links;

		return $order;
	}

	/**
	 * @inheritDoc
	 */
	protected function getCallbackArguments(): array
	{
		return [
			[
				'methods' => static::READABLE,
				'callback' => [$this, 'restCallback'],
				'permission_callback' => [$this, 'restPermissionCheck'],
			],
			'schema' => [$this, 'getRouteSchema']
		];
	}

	/**
	 * @inheritDoc
	 */
	public function restCallback(WP_REST_Request $request)
	{
		$params = $request->get_params();

		$id = (int)$params['id'];

		$orders = $this->database->getOrders($id);

		$data = [];

		if (empty($orders)) {
			return rest_ensure_response($data);
		}

		foreach ($orders as $order) {
			$data = self::prepareItemForOutput($order);
		}

		return rest_ensure_response($data);
	}

	/**
	 * Check if the current user has necessary privileges to access the endpoint
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function restPermissionCheck(WP_REST_Request $request)
	{
		return is_user_logged_in() && current_user_can('manage_options');
	}

	/**
	 * Get the route's scheme
	 *
	 * @return array Scheme details.
	 */
	public function getRouteSchema(): array
	{
		return [
			// This tells the spec of JSON Schema we are using which is draft 4.
			'$schema' => 'https://json-schema.org/draft-04/schema#',
			// The title property marks the identity of the resource.
			'title' => 'solo-order',
			'type' => 'object',
			// In JSON Schema you can specify object properties in the properties attribute.
			'properties' => [
				'id' => [
					'description' => esc_html__('Unique identifier for the solo order object.', 'woo-solo-api'),
					'type' => 'integer',
					'readonly' => true,
					'context' => ['view'],
				],
				'order_id' => [
					'description' => esc_html__('The id of the WooCommerce order object.', 'woo-solo-api'),
					'type' => 'integer',
					'context' => ['view'],
				],
				'solo_id' => [
					'description' => esc_html__('The id of the Solo order object.', 'woo-solo-api'),
					'type' => 'string',
					'context' => ['view'],
				],
				'customer_email' => [
					'description' => esc_html__('The email of the customer who made the order.', 'woo-solo-api'),
					'type' => 'string',
					'context' => ['view'],
				],
				'is_sent_to_api' => [
					'description' => esc_html__('Flag that notifies if the order was sent to API.', 'woo-solo-api'),
					'type' => 'boolean',
					'context' => ['view'],
				],
				'is_sent_to_user' => [
					'description' => esc_html__(
						'Flag that notifies if the PDF bill was sent to customer.',
						'woo-solo-api'
					),
					'type' => 'boolean',
					'context' => ['view'],
				],
				'error_message' => [
					'description' => esc_html__('Error message send by the Solo API.', 'woo-solo-api'),
					'type' => 'string',
					'context' => ['view'],
				],
				'created_at' => [
					'description' => esc_html__('The date and time of the solo order creation.', 'woo-solo-api'),
					'type' => 'date-time',
					'context' => ['view'],
				],
				'updated_at' => [
					'description' => esc_html__('The date and time when the order was last updated.', 'woo-solo-api'),
					'type' => 'date-time',
					'context' => ['view'],
				],
			],
		];
	}
}
