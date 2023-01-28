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

use function esc_html__;

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
	private SoloOrdersTable $soloOrdersTable;

	public function __construct(SoloOrdersTable $soloOrdersTable)
	{
		$this->soloOrdersTable = $soloOrdersTable;
	}

	/**
	 * Prepare order item for REST response
	 *
	 * @param array<string, mixed> $order Order details array.
	 *
	 * @return array<string, mixed> Modified order output.
	 */
	public static function prepareItemForOutput(array $order): array
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

		$orders = $this->soloOrdersTable->getOrders($id);

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
	 * @return array<string, mixed> Scheme details.
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
					'description' => 'Unique identifier for the solo order object.',
					'type' => 'integer',
					'readonly' => true,
					'context' => ['view'],
				],
				'order_id' => [
					'description' => 'The id of the WooCommerce order object.',
					'type' => 'integer',
					'context' => ['view'],
				],
				'solo_id' => [
					'description' => 'The id of the Solo order object.',
					'type' => 'string',
					'context' => ['view'],
				],
				'customer_email' => [
					'description' => 'The email of the customer who made the order.',
					'type' => 'string',
					'context' => ['view'],
				],
				'is_sent_to_api' => [
					'description' => 'Flag that notifies if the order was sent to API.',
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
				'pdf_url' => [
					'description' => esc_html__(
						'URL of the sent PDF. Used to resend the PDF in case there was some failure with the request.',
						'woo-solo-api'
					),
					'type' => 'string',
					'context' => ['view'],
				],
				'error_message' => [
					'description' => 'Error message send by the Solo API.',
					'type' => 'string',
					'context' => ['view'],
				],
				'created_at' => [
					'description' => 'The date and time of the solo order creation.',
					'type' => 'date-time',
					'context' => ['view'],
				],
				'updated_at' => [
					'description' => 'The date and time when the order was last updated.',
					'type' => 'date-time',
					'context' => ['view'],
				],
			],
		];
	}
}
