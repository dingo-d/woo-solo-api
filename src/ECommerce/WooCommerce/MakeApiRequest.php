<?php

/**
 * File holding ApiRequest class
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\ECommerce\WooCommerce;

use MadeByDenis\WooSoloApi\BackgroundJobs\MakeSoloApiCall;
use MadeByDenis\WooSoloApi\Core\Registrable;
use MadeByDenis\WooSoloApi\Exception\OrderValidationException;
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\Request\ApiRequest;
use WC_Order;

use function add_action;
use function get_option;
use function wc_get_order;

/**
 * API request
 *
 * Class that makes api request towards SOLO service
 * and handles sending emails to clients from the SOLO service.
 * In the case of order being completed, a background job will be scheduled
 * that will send the orders towards the SOLO API.
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */
class MakeApiRequest implements Registrable
{

	/**
	 * @var SoloOrdersTable
	 */
	private $soloOrdersTable;

	/**
	 * @var ApiRequest
	 */
	private $soloApiRequest;

	/**
	 * ApiRequest constructor
	 *
	 * @param SoloOrdersTable $soloOrdersTable Dependency that manages database concern.
	 * @param ApiRequest $soloApiRequest Api request dependency.
	 */
	public function __construct(SoloOrdersTable $soloOrdersTable, ApiRequest $soloApiRequest)
	{
		$this->soloOrdersTable = $soloOrdersTable;
		$this->soloApiRequest = $soloApiRequest;
	}

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('woocommerce_email_order_details', [$this, 'sendApiRequestOnCheckout'], 15, 4);
		add_action('woocommerce_order_status_completed', [$this, 'sendApiRequestOnOrderCompleted'], 30, 1);
	}

	/**
	 * Solo API create an invoice on successful WooCommerce order
	 *
	 * The main function of the plugin. It handles the request from
	 * the order once the order is sent.
	 *
	 * @param WC_Order $order Order data.
	 * @param bool $sentToAdmin Send to admin (default: false).
	 * @param bool $plainText Plain text email (default: false).
	 * @param object $email Order email object.
	 *
	 * @retrun void
	 *
	 * @since 1.0.0
	 *
	 * @since 2.2.0 Add woo_solo_api_overwrite_request_on_checkout filter
	 * @since 2.0.0 Refactored method -
	 *                    Extracted logic to private methods
	 *                    Added a database checks for consistency, preventing the duplicate calls
	 * @since 1.9.5 Add check if the order was sent to avoid multiple API calls. Separate order completed call.
	 * @since 1.4.0 Fix the 'send api method'.
	 * @since 1.3.0 Added tax checks and additional debug options.
	 */
	public function sendApiRequestOnCheckout($order, bool $sentToAdmin, bool $plainText, object $email): void // phpcs:ignore
	{
		// This should run only on the front facing side.
		if (is_admin()) {
			return;
		}

		// Double check just to be sure.
		$sendControl = get_option('solo_api_send_control');

		/**
		 * Overwrite the checkout request for specific payment cases
		 *
		 * This filter is used if you have globally selected the API request to happen on
		 * manual status change of the order, but you want to allow some payment gateways to
		 * execute on the checkout.
		 *
		 * Usage:
		 *
		 * add_filter('woo_solo_api_overwrite_request_on_checkout', 'my_payment_processor_overwrite', 10, 2);
		 *
		 * function my_payment_processor_overwrite($sendControl, $order) {
		 *   // Say we want to allow api call on checkout for direct bank transfer (bacs),
		 *   // and all others on status change.
		 *
		 *   // Check the payment gateway from the order.
		 *   $paymentMethod = $order->get_payment_method();
		 *
		 *   // Check if the current payment method is the one you want to overwrite.
		 *   if ($paymentMethod === 'bacs') {
		 *     return 'checkout';
		 *   }
		 *
		 *   // Default fallback.
		 *   return $sendControl;
		 * }
		 */
		$sendControl = \apply_filters('woo_solo_api_overwrite_request_on_checkout', $sendControl, $order);

		if ($sendControl === 'status_change') {
			return;
		}

		$orderId = $order->get_id();

		/**
		 * If the order was sent, don't send it again.
		 *
		 * This checks the option where sent orders are stored and if the order is stored,
		 * don't send request to solo API.
		 */
		if ($this->soloOrdersTable->wasOrderSent($orderId)) {
			return;
		}

		/**
		 * Check if the order entry exists in the DB.
		 * This can happen if the API call failed. If it does, we won't create a new entry
		 * in the woo orders table, we'll just update it.
		 */
		$orderEntryExists = $this->soloOrdersTable->orderExists($orderId);

		/**
		 * Create a database entry for consistency checks
		 *
		 * Order sent to API - NO;
		 * Email sent to user - NO;
		 * Update - Should be no, but if previous call failed for the same order, just update it;
		 */
		SoloOrdersTable::updateOrdersTable($orderId, '', false, false, $orderEntryExists);

		$this->soloApiRequest->executeApiCall($order);
	}

	/**
	 * Send API call when order status changes
	 *
	 * We'll schedule call or multiple calls, in case there are multiple orders checked in the
	 * admin area, so that we don't overburden the API.
	 *
	 * @since 1.9.5
	 *
	 * @param int $orderId The ID of the order.
	 *
	 * @retrun void
	 */
	public function sendApiRequestOnOrderCompleted(int $orderId): void
	{
		// If the option is selected to send pdf on admin we should only run it on admin.
		if (!is_admin()) {
			return;
		}

		/**
		 * If the order was sent, don't send it again.
		 *
		 * This checks the option where sent orders are stored and if the order is stored,
		 * don't send request to solo API.
		 */
		if ($this->soloOrdersTable->wasOrderSent($orderId)) {
			return;
		}

		$order = wc_get_order($orderId);

		// Track the number of call numbers in case of multiple calls.
		static $callNumber = 1;

		if (!($order instanceof WC_Order)) {
			throw OrderValidationException::invalidOrderType($order);
		}

		// Execute only if status is changed to completed!
		$orderData = $order->get_data();
		$orderStatus = $orderData['status'];

		if ($orderStatus === 'completed') {
			/**
			 * Check if the order entry exists in the DB.
			 * This can happen if the API call failed. If it does, we won't create a new entry
			 * in the woo orders table, we'll just update it.
			 */
			$orderEntryExists = $this->soloOrdersTable->orderExists($orderId);

			/**
			 * Create a database entry for consistency checks
			 *
			 * Order sent to API - NO;
			 * Email sent to user - NO;
			 * Update - Should be no, but if previous call failed for the same order, just update it;
			 */
			SoloOrdersTable::updateOrdersTable($orderId, '', false, false, $orderEntryExists);

			// Schedule a calls towards the API.
			wp_schedule_single_event(
				time() + (30 * $callNumber),
				MakeSoloApiCall::JOB_NAME,
				[$order]
			);

			$callNumber++;
		}
	}
}
