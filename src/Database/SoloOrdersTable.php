<?php

/**
 * File holding SoloOrdersTable class
 *
 * @package MadeByDenis\WooSoloApi\DB
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Database;

/**
 * SoloOrdersTable class
 *
 * @package MadeByDenis\WooSoloApi\DB
 * @since 2.0.0
 */
class SoloOrdersTable
{

	public const TABLE_NAME = 'solo_api_orders';

	/**
	 * Method that will create a custom orders table
	 *
	 * This will create a custom table that is used to track
	 * the sent orders to the API and sent orders to the customer from the API
	 * that is triggered by the background job.
	 */
	public static function createTable()
	{
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $tableName = $wpdb->prefix . self::TABLE_NAME;

        $collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
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
	 */
	public static function deleteTable()
	{
		global $wpdb;

		$tableName = $wpdb->prefix . self::TABLE_NAME;

		$wpdb->query( "DROP TABLE IF EXISTS {$tableName}" );
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

		$tableName = $wpdb->prefix . SoloOrdersTable::TABLE_NAME;

		$order = \wc_get_order($orderId);
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
}
