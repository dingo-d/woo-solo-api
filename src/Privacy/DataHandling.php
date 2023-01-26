<?php

/**
 * File holding DataHandling class
 *
 * @package MadeByDenis\WooSoloApi\Privacy
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Privacy;

use MadeByDenis\WooSoloApi\Core\Registrable;
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;

use function add_filter;
use function esc_html__;

/**
 * Data handling
 *
 * This class will hold the hooks that will be run when a user makes a request for either
 * data export or data deletion using the native WordPress privacy functionality.
 *
 * @package MadeByDenis\WooSoloApi\Privacy
 * @since 2.0.0
 */
class DataHandling implements Registrable
{
	private const IDENTIFIER = 'solo-api-order-data';

	private const GROUP_ID = 'woo-solo-api-order-items';

	/**
	 * @var SoloOrdersTable
	 */
	private $soloOrdersTable;

	/**
	 * DatabaseTableMissingNotice constructor
	 *
	 * @param SoloOrdersTable $soloOrdersTable Dependency that manages database concern.
	 */
	public function __construct(SoloOrdersTable $soloOrdersTable)
	{
		$this->soloOrdersTable = $soloOrdersTable;
	}

	public function register(): void
	{
		add_filter('wp_privacy_personal_data_exporters', [$this, 'dataExportHandler']);
		add_filter('wp_privacy_personal_data_erasers', [$this, 'dataDeletionHandler']);
	}

	/**
	 * Callback that will handle the array of exporter callbacks
	 *
	 * @param array<string, array<mixed>> $args An array of callable exporters of personal data. Default empty array.
	 *
	 * @return array<string, array<mixed>> Updated list of exporters.
	 */
	public function dataExportHandler(array $args): array
	{
		$args[self::IDENTIFIER] = [
			'exporter_friendly_name' => esc_html__('Woo Solo Api Order Data', 'woo-solo-api'),
			'callback' => [$this, 'soloOrderDataExporter'],
		];

		return $args;
	}

	/**
	 * Callback that will handle exporting user data when requested
	 *
	 * @param string $emailAddress Email address that can be queried against.
	 * @param int $page Pagination identifier.
	 *
	 * @return array<string, mixed> Array of exported data.
	 */
	public function soloOrderDataExporter(string $emailAddress, $page = 1): array
	{
		$results = $this->soloOrdersTable->getOrderDetails($emailAddress);

		$exportedItems = [];

		if (!empty($results)) {
			foreach ($results as $item) {
				$data = [
					[
						'name' => esc_html__('Order Item', 'woo-solo-api'),
						'value' => $item->id
					],
					[
						'name' => esc_html__('WooCommerce order item ID', 'woo-solo-api'),
						'value' => $item->order_id
					],
					[
						'name' => esc_html__('Solo ID', 'woo-solo-api'),
						'value' => $item->solo_id,
					],
					[
						'name' => esc_html__('Customer email', 'woo-solo-api'),
						'value' => $item->customer_email,
					],
					[
						'name' => esc_html__('Is sent to API', 'woo-solo-api'),
						'value' => $item->is_sent_to_api,
					],
					[
						'name' => esc_html__('Is sent to user', 'woo-solo-api'),
						'value' => $item->is_sent_to_user,
					],
					[
						'name' => esc_html__('API request error messages', 'woo-solo-api'),
						'value' => $item->error_message,
					],
					[
						'name' => esc_html__('Created at', 'woo-solo-api'),
						'value' => $item->created_at
					],
					[
						'name' => esc_html__('Updated at', 'woo-solo-api'),
						'value' => $item->updated_at
					],
				];

				$exportedItems[] = [
					'group_id' => self::GROUP_ID,
					'group_label' => esc_html__('Woo Solo Api Order Items', 'woo-solo-api'),
					'item_id' => $item->id,
					'data' => $data
				];
			}
		}

		return [
			'data' => $exportedItems,
			'done' => true
		];
	}

	/**
	 * Callback that will handle the array of eraser callbacks
	 *
	 * @param array<string, mixed> $args An array of callable erasers of personal data. Default empty array.
	 *
	 * @return array<string, mixed> Updated list of erasers.
	 */
	public function dataDeletionHandler(array $args): array
	{
		$args[self::IDENTIFIER] = [
			'eraser_friendly_name' => esc_html__('Woo Solo Api Order Data', 'woo-solo-api'),
			'callback' => [$this, 'soloOrderDataEraser'],
		];

		return $args;
	}

	/**
	 * Callback that will handle erasing user data when requested
	 *
	 * @param string $emailAddress Email address that can be queried against.
	 * @param int $page Pagination identifier.
	 *
	 * @return array<string, mixed> Array of erased data.
	 */
	public function soloOrderDataEraser(string $emailAddress, $page = 1): array
	{
		$results = $this->soloOrdersTable->deleteOrderDetails($emailAddress);

		return [
			'items_removed' => $results,
			'items_retained' => false,
			'messages' => [],
			'done' => true,
		];
	}
}
