<?php

/**
 * File holding SoloOrdersTable class
 *
 * @package MadeByDenis\WooSoloApi\DB
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Database;

use MadeByDenis\WooSoloApi\Exception\OrderValidationException;
use WC_Order;

/**
 * Manages all the database interactions in the plugin
 *
 * We don't want to pollute the codebase with global $wpdb calls.
 * Instead we have a dedicated class that will handle this. Since WordPress doesn't
 * have an ORM, this will have to do.
 *
 * @package MadeByDenis\WooSoloApi\DB
 * @since 2.0.0
 */
class SoloOrdersTable
{

	/**
	 * @var string Custom table name.
	 */
	public const TABLE_NAME = 'solo_api_orders';

	/**
	 * Helper method that checks the database existence
	 *
	 * @return bool True if database is missing, false if not.
	 */
	public function databaseTableIsMissing(): bool
	{
		global $wpdb;

		$tableName = $wpdb->prefix . self::TABLE_NAME;

		$check = $wpdb->query(
			$wpdb->prepare(
				"SELECT *
				FROM information_schema.tables
				WHERE table_schema = %s
					AND table_name = %s
				LIMIT 1;",
				$wpdb->dbname,
				$tableName
			)
		);

		if ($check === 0) {
			return true;
		}

		return false;
	}

	/**
	 * Check if order was sent
	 *
	 * The unique order ID gets written in an options array as a string
	 * and then checked against in order to prevent sending duplicate requests for the same order.
	 *
	 * @param int $id Order ID.
	 *
	 * @return bool
	 */
	public function wasOrderSent(int $id): bool
	{
		global $wpdb;

		$tableName = $wpdb->prefix . self::TABLE_NAME;

		$result = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT is_sent_to_api
				FROM {$tableName}
				WHERE order_id = %d;",
				$id
			),
			ARRAY_A
		);

		if (empty($result)) {
			return false;
		}

		if ($result[0]['is_sent_to_api'] === '0') {
			return false;
		}

		return true;
	}

	/**
	 * Query the data in the table
	 *
	 * @param string $emailAddress Email address to query against.
	 *
	 * @return array|null
	 */
	public function getOrderDetails(string $emailAddress)
	{
		global $wpdb;

		$tableName = $wpdb->prefix . self::TABLE_NAME;

		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$tableName} WHERE `customer_email` = %s",
				$emailAddress
			),
			ARRAY_A
		);
	}

	/**
	 * Delete the data in the table
	 *
	 * @param string $emailAddress Email address to query against.
	 *
	 * @return bool|int
	 */
	public function deleteOrderDetails(string $emailAddress)
	{
		global $wpdb;

		$tableName = $wpdb->prefix . self::TABLE_NAME;

		return $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$tableName} WHERE `customer_email` = %s",
				$emailAddress
			)
		);
	}

	/**
	 * Method that will create a custom orders table
	 *
	 * This will create a custom table that is used to track
	 * the sent orders to the API and sent orders to the customer from the API
	 * that is triggered by the background job.
	 *
	 * @return void
	 */
	public static function createTable(): void
	{
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$tableName = $wpdb->prefix . self::TABLE_NAME;

		$collate = '';

		if ($wpdb->has_cap('collation')) {
			$collate = $wpdb->get_charset_collate();
		}

		$sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (
				`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				`order_id` bigint(20) UNSIGNED NOT NULL,
				`customer_email` varchar(255) NOT NULL,
				`is_sent_to_api` boolean NOT NULL,
				`is_sent_to_user` boolean NOT NULL,
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`)
				) $collate;";

		$wpdb->query($sql);
	}

	/**
	 * Method that will delete a custom orders table
	 *
	 * This will be run on plugin uninstall. It should also be hooked somehow
	 * into WordPress privacy hooks, so that it can be exported or removed when
	 * the privacy requests are triggered.
	 *
	 * @return void
	 */
	public static function deleteTable(): void
	{
		global $wpdb;

		$tableName = $wpdb->prefix . self::TABLE_NAME;

		$wpdb->query("DROP TABLE IF EXISTS {$tableName}");
	}

	/**
	 * Update the custom table
	 *
	 * Once the order passes, we need to mark this in the DB in our custom table.
	 *
	 * @param int $orderId Order ID.
	 * @param bool $sentToApi Is the order sent to SOLO API.
	 * @param bool $sentToUser Is the order PDF sent to user.
	 * @param bool $update Is current call update to the table or creation?.
	 *
	 * @return void
	 */
	public static function updateOrdersTable(int $orderId, bool $sentToApi, bool $sentToUser, bool $update): void
	{
		global $wpdb;

		$tableName = $wpdb->prefix . self::TABLE_NAME;

		$order = \wc_get_order($orderId);

		if (!($order instanceof WC_Order)) {
			throw OrderValidationException::invalidOrderType($order);
		}

		$email = $order->get_billing_email();

		$insertData = [
			'order_id' => $orderId,
			'customer_email' => $email,
			'is_sent_to_api' => $sentToApi,
			'is_sent_to_user' => $sentToUser,
		];

		$time = current_time('mysql');

		if ($update) {
			$insertData['updated_at'] = $time;

			$wpdb->update($tableName, $insertData, ['order_id' => $orderId]);
		} else {
			$insertData['created_at'] = $time;
			$insertData['updated_at'] = $time;

			$wpdb->insert($tableName, $insertData);
		}
	}


	/**
	 * Get the collection of orders
	 *
	 * To do: Implement pagination so that only 20 records are fetched on every call.
	 *
	 * @param int|null $id ID of the order.
	 *
	 * @return array|null Collection of orders from the DB or one order from the DB.
	 */
	public function getOrders(int $id = null)
	{
		global $wpdb;

		$tableName = $wpdb->prefix . self::TABLE_NAME;

		if (!empty($id)) {
			return $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$tableName} WHERE id = %d",
					$id
				),
				ARRAY_A
			);
		}

		return $wpdb->get_results(
			"SELECT * FROM {$tableName}",
			ARRAY_A
		);
	}
}
