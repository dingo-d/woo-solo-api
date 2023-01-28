<?php

/**
 * File holding SoloApiRequest class
 *
 * @package MadeByDenis\WooSoloApi\Request
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Request;

use MadeByDenis\WooSoloApi\BackgroundJobs\SendCustomerEmail;
use MadeByDenis\WooSoloApi\Exception\{ApiRequestException, OrderValidationException, WpException};
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\Utils\FetchExchangeRate;
use RuntimeException;
use TypeError;
use WC_Order;
use WC_Order_Refund;
use WC_Tax;

use function apply_filters;
use function esc_attr;
use function esc_html;
use function esc_html__;
use function get_option;
use function wp_kses_post;
use function wp_remote_post;
use function wp_remote_retrieve_body;
use function wp_remote_retrieve_response_code;
use function wp_remote_retrieve_response_message;

/**
 * Remote API call
 *
 * This class holds the actual remote call that will be made towards Solo API.
 *
 * @package MadeByDenis\WooSoloApi\Request
 * @since 2.3.0 Update the way the currency is handled and converted,
 *              due to Euro being official Croatian currency.
 * @since 2.0.0
 */
class SoloApiRequest implements ApiRequest
{
	/**
	 * Name of the invoice type
	 *
	 * Set by the API.
	 *
	 * @var string
	 */
	public const INVOICE = 'racun';

	/**
	 * Name of the offer type
	 *
	 * Set by the API.
	 *
	 * @var string
	 */
	public const OFFER = 'ponuda';

	/**
	 * Solo API URL
	 *
	 * @var string
	 */
	public const URL = 'https://api.solo.com.hr/';

	/**
	 * Execute the call to the SOLO API
	 *
	 * @since  2.0.0 Refactored the method to its own class.
	 * @since  1.9.5 Added check for empty taxes, fixed sending requests two times.
	 * @since  1.9.3 Added translated notes for recalculating rates.
	 * @since  1.8.1 Added a check for no taxes on items.
	 * @since  1.7.0 Fixed tax rates and payment types per payment gateway.
	 * @since  1.4.0 Separated the send method for more control. Add
	 *               a check to send the mail in this method, so that the
	 *               method that controls sending is not called at all.
	 *
	 * @param bool|WC_Order|WC_Order_Refund $order Order data.
	 *
	 * @retrun void
	 */
	public function executeApiCall($order): void
	{
		if (!($order instanceof WC_Order)) {
			throw OrderValidationException::invalidOrderType($order);
		}

		// Options.
		$token = get_option('solo_api_token', '');
		$measure = get_option('solo_api_measure', '1');
		$currency = get_option('solo_api_currency', '1');
		$serviceType = get_option('solo_api_service_type', 0);
		$showTaxes = get_option('solo_api_show_taxes', false);
		$invoiceType = get_option('solo_api_invoice_type', '1');
		$dueDate = get_option('solo_api_due_date', '1');
		$languages = get_option('solo_api_languages', '1');

		// Defensive coding.
		if (!is_string($dueDate)) {
			throw new TypeError(esc_html__('Due date option needs to be a string', 'woo-solo-api'));
		}

		if (!is_string($currency)) {
			throw new TypeError(esc_html__('Currency option needs to be a string', 'woo-solo-api'));
		}

		if (!is_string($languages)) {
			throw new TypeError(esc_html__('Language option needs to be a string', 'woo-solo-api'));
		}

		$orderData = $order->get_data(); // The Order data.

		// The bill data (invoice or offer) must be from billing fields!
		$firstName = $orderData['billing']['first_name'] ?? '';
		$lastName = $orderData['billing']['last_name'] ?? '';
		$companyName = $orderData['billing']['company'] ?? ''; // Used for R1.
		$address1 = $orderData['billing']['address_1'] ?? '';
		$address2 = $orderData['billing']['address_2'] ?? '';
		$city = $orderData['billing']['city'] ?? '';
		$state = $orderData['billing']['state'] ?? '';
		$country = $orderData['billing']['country'] ?? '';
		$postcode = $orderData['billing']['postcode'] ?? '';
		$email = $orderData['billing']['email'] ?? '';

		$metaData = $orderData['meta_data'];

		foreach ($metaData as $data => $metaValue) {
			$data = $metaValue->get_data();

			if ($data['key'] === '_billing_pin_number') {
				$pinNumber = $data['value'] ?? '';
			}

			if ($data['key'] === '_billing_iban_number') {
				$ibanNumber = $data['value'] ?? '';
			}
		}

		$orderBuyer = "$firstName $lastName";

		if (!empty($companyName)) {
			$orderBuyer = $companyName;
		}

		if ($address2 !== '') {
			if ($state !== '') {
				$orderAddress = "$address1, $address2 , $city $state $postcode, $country";
			} else {
				$orderAddress = "$address1, $address2, $city $postcode, $country";
			}
		} else {
			if ($state !== '') {
				$orderAddress = "$address1, $city $state $postcode, $country";
			} else {
				$orderAddress = "$address1, $city $postcode, $country";
			}
		}

		$paymentMethod = esc_attr($orderData['payment_method']);

		$billType = get_option('solo_api_bill_offer-' . $paymentMethod);

		/**
		 * Filters the bill type
		 *
		 * Use this filter if you need to dynamically change the bill type.
		 * Usage:
		 *
		 * add_filter('woo_solo_api_modify_bill_type', 'my_customer_filter', 10, 3);
		 *
		 * function my_customer_filter($billType, $paymentMethod, $order) {
		 *   // (maybe) modify $billType, based on some external parameters or $paymentMethod or $order.
		 *   return $billType;
		 * }
		 *
		 * @since 3.0.0
		 *
		 * @param string $billType Existing bill type.
		 * @param string $paymentMethod Selected payment method for the current order.
		 * @param object|WC_Order $order Current order.
		 *
		 * @return string Modified bill type. API allows only 'ponuda', 'racun', or 'katalog'.
		 *                If you put anything else, the API call will fail. The 'katalog' implementation
		 *                is not supported by this plugin at this time.
		 */
		$billType = apply_filters('woo_solo_api_modify_bill_type', $billType, $paymentMethod, $order);

		$soloApiFiscalization = get_option('solo_api_fiscalization-' . esc_attr($paymentMethod));
		$paymentType = get_option('solo_api_payment_type-' . esc_attr($paymentMethod));

		$url = self::URL . $billType;

		// Get all data needed for the request.
		$requestBody = [
			'token' => $token,
			'tip_usluge' => $serviceType,
			'prikazi_porez' => $showTaxes ? 1 : 0,
			'kupac_naziv' => esc_attr($orderBuyer),
			'kupac_adresa' => esc_attr($orderAddress),
		];

		$requestBody['tip_racuna'] = 4; // Default is no label.

		if ($billType === self::INVOICE) {
			$requestBody['tip_racuna'] = $invoiceType;
		}

		// Optional parameter.
		if (!empty($pinNumber)) {
			$requestBody['kupac_oib'] = (int)$pinNumber;
		}

		// Individual items.
		$itemNo = 1;

		$calculateTaxes = get_option('woocommerce_calc_taxes');

		foreach (array_unique($order->get_items()) as $itemDetail) {
			$itemData = $itemDetail->get_data(); // Product data.

			$taxRates = array_values(WC_Tax::get_base_tax_rates($itemDetail->get_tax_class()));

			$taxRate = !empty($taxRates) ? (float)$taxRates[0]['rate'] : 0;
			$productName = $itemData['name'];
			$quantity = (float)($itemData['quantity'] !== 0) ? $itemData['quantity'] : 1;
			$singlePrice = (float)$itemData['total'] / $quantity;

			/**
			 * Croatian law clarifies that the tax values on goods and services can be
			 * either 5%, 13% or 25%. We need to ensure that this is correct.
			 * If not, we'll set it to 0.
			 *
			 * @link https://www.porezna-uprava.hr/HR_porezni_sustav/Stranice/porez_na_dodanu_vrijednost.aspx
			 */
			if (!in_array($taxRate, [5, 13, 25])) {
				$taxRate = 0;
			}

			if ($calculateTaxes === 'no') {
				$taxRate = 0;
			}

			$lineTotal = number_format($singlePrice, 2, ',', '.');

			$requestBody["usluga{$itemNo}"] = $itemNo;

			$requestBody["opis_usluge_{$itemNo}"] = $productName;

			/**
			 * Filters the Unit measure for the current item
			 *
			 * Use this filter if you need to dynamically change measure for the item.
			 * Unit measure *MUST* be an integer corresponding to the following list:
			 *
			 * 1:  '-'
			 * 2:  'piece'
			 * 3:  'hour'
			 * 4:  'year'
			 * 5:  'km'
			 * 6:  'litre'
			 * 7:  'kg'
			 * 8:  'kWh'
			 * 9:  'm³'
			 * 10: 'tonne'
			 * 11: 'm²'
			 * 12: 'm'
			 * 13: 'day'
			 * 14: 'month'
			 * 15: 'night'
			 * 16: 'cart'
			 * 17: 'account'
			 * 18: 'pair'
			 * 19: 'ml'
			 * 20: 'pax'
			 * 21: 'room'
			 * 22: 'apartment'
			 * 23: 'term'
			 * 24: 'set'
			 * 25: 'package'
			 * 26: 'point'
			 * 27: 'service'
			 * 28: 'pal'
			 * 29: 'kont'
			 * 30: 'čl'
			 * 31: 'tis'
			 * 32: 'sec'
			 * 33: 'min'
			 * 34: 'str'
			 * 35: 'kpl'
			 * 36: 'pšl'
			 * 37: 'ha'
			 * 38: 'g'
			 * 39: 'x'
			 *
			 * add_filter('woo_solo_api_modify_item_measure', 'my_customer_filter', 10, 4);
			 *
			 * function my_customer_filter($measure, $itemNo, $order, $itemData) {
			 *   // Modify multiple item numbers in your order based on some settings, meta value, etc.
			 *   switch ($itemNo) {
			 *      case 0:
			 *          $measure = 7;
			 *          break;
			 *      case 1:
			 *          $measure = 19;
			 *          break;
			 *      default:
			 *          $measure = 2;
			 *          break;
			 *   }
			 *
			 *   // Or filter based on $order object or $itemData which contains other information about the product.
			 *
			 *   return $measure;
			 * }
			 *
			 * @since 3.0.0
			 *
			 * @param int $measure Current unit measure.
			 * @param int $itemNo Current item in the list.
			 * @param object|WC_Order $order Current order.
			 * @param array $itemData Current item data.
			 *
			 * @return int Modified unit measure. Be careful to select the measure from the settings.
			 */
			$measure = apply_filters('woo_solo_api_modify_item_measure', $measure, $itemNo, $order, $itemData);

			$requestBody["jed_mjera_{$itemNo}"] = $measure;
			$requestBody["cijena_{$itemNo}"] = $lineTotal;
			$requestBody["kolicina_{$itemNo}"] = $quantity;

			/**
			 * Adds a global discount
			 *
			 * WooCommerce will handle counting the discounts for us.
			 * This is why this is set to 0.
			 * We can hook into this if we want to change it.
			 * But this hook will affect every item. So use it with care.
			 *
			 * Usage:
			 *
			 * add_filter('woo_solo_api_add_global_discount', 'my_global_discount', 10, 1);
			 *
			 * function my_global_discount($globalDiscount) {
			 *   // (maybe) modify $globalDiscount.
			 *   return $globalDiscount;
			 * }
			 *
			 * @since 2.0.0
			 *
			 * @param int $globalDiscount The value of the global discount to apply to every item.
			 */
			$requestBody["popust_{$itemNo}"] = apply_filters('woo_solo_api_add_global_discount', $globalDiscount = 0);

			/**
			 * Modify tax rates
			 *
			 * This hook is used to set different tax rates for items based on certain criteria.
			 * For instance, if you want to modify taxes based on location you can change it here
			 * (if for some reason it's not working from the default settings).
			 *
			 * Usage:
			 *
			 * add_filter('woo_solo_api_modify_tax_rate', 'my_tax_rate', 10, 4);
			 *
			 * function my_tax_rate($taxRate, $itemData, $taxRates, $itemNo) {
			 *   // (maybe) modify $taxRate.
			 *   return $taxRate;
			 * }
			 *
			 * @since 2.1.0
			 *
			 * @param float $taxRate  The value of the tax rate for the current order item.
			 * @param array $itemData The data for the current order item.
			 * @param array $taxRates The value of the tax rates for the current order item.
			 * @param int $itemNo Item number.
			 */
			$requestBody["porez_stopa_{$itemNo}"] = apply_filters('woo_solo_api_modify_tax_rate', $taxRate, $itemData, $taxRates, $itemNo);

			$itemNo++;
		}

		/**
		 * Shipping part
		 *
		 * Shipping will be added as the last item in the post array, independent of other services.
		 * We'll reuse the $itemNo variable from the previous loops.
		 *
		 * @since 2.0.0 Replaced deprecated get_total_shipping() with get_shipping_total()
		 * @since 1.3.0
		 */
		$totalShipping = (float)$order->get_shipping_total();

		if ($totalShipping > 0) {
			$shippingItems = $order->get_items('shipping');

			foreach ($shippingItems as $shippingObject) {
				$shippingTaxRates = array_values(WC_Tax::get_base_tax_rates());
			}

			// We're fetching the first value always.
			$shippingTaxRate = !empty($shippingTaxRates) ? (float)$shippingTaxRates[0]['rate'] : 0;

			$shippingPrice = number_format($totalShipping, 2, ',', '.');

			if (!in_array($shippingTaxRate, [5, 13, 25])) {
				$shippingTaxRate = 0;
			}

			$requestBody["usluga{$itemNo}"] = $itemNo;
			$requestBody["opis_usluge_{$itemNo}"] = esc_html__('Shipping fee', 'woo-solo-api');

			/**
			 * Filters the Unit measure for the current shipping item
			 *
			 * Use this filter if you need to dynamically change measure for the shipping item.
			 * Unit measure *MUST* be an integer corresponding to the following list:
			 *
			 * 1:  '-'
			 * 2:  'piece'
			 * 3:  'hour'
			 * 4:  'year'
			 * 5:  'km'
			 * 6:  'litre'
			 * 7:  'kg'
			 * 8:  'kWh'
			 * 9:  'm³'
			 * 10: 'tonne'
			 * 11: 'm²'
			 * 12: 'm'
			 * 13: 'day'
			 * 14: 'month'
			 * 15: 'night'
			 * 16: 'cart'
			 * 17: 'account'
			 * 18: 'pair'
			 * 19: 'ml'
			 * 20: 'pax'
			 * 21: 'room'
			 * 22: 'apartment'
			 * 23: 'term'
			 * 24: 'set'
			 * 25: 'package'
			 * 26: 'point'
			 * 27: 'service'
			 * 28: 'pal'
			 * 29: 'kont'
			 * 30: 'čl'
			 * 31: 'tis'
			 * 32: 'sec'
			 * 33: 'min'
			 * 34: 'str'
			 * 35: 'kpl'
			 * 36: 'pšl'
			 * 37: 'ha'
			 * 38: 'g'
			 * 39: 'x'
			 *
			 * add_filter('woo_solo_api_modify_shipping_item_measure', 'my_customer_filter', 10, 4);
			 *
			 * function my_customer_filter($measure, $itemNo, $order, $itemData) {
			 *   // Modify multiple item numbers in your order based on some settings, meta value, etc.
			 *   switch ($itemNo) {
			 *      case 0:
			 *          $measure = 7;
			 *          break;
			 *      case 1:
			 *          $measure = 19;
			 *          break;
			 *      default:
			 *          $measure = 2;
			 *          break;
			 *   }
			 *
			 *   // Or filter based on $order object or $itemData which contains other information about the product.
			 *
			 *   return $measure;
			 * }
			 *
			 * In reality the filter will probably be used like this:
			 *
			 * add_filter('woo_solo_api_modify_shipping_item_measure', 'my_customer_filter', 10, 4);
			 *
			 * function my_customer_filter($measure, $itemNo, $order, $itemData) {
			 *     return 2; // Corresponds to: piece.
			 * }
			 *
			 * @since 3.0.0
			 *
			 * @param int $measure Current shipping unit measure. Will be picked up by whatever is in the default measure.
			 * @param int $itemNo Current item in the list.
			 * @param object|WC_Order $order Current order.
			 * @param object|null $shippingObject Current shipping item data.
			 *
			 * @return int Modified shipping unit measure.
			 *             If not used, the measure will be picked up from the item's unit measure.
			 *             The default value should probably be set tot 2 (piece),
			 *             so if you're changing the default value of the measure from piece to anything else,
			 *             make sure shipping fee is set to 2.
			 */
			$measure = apply_filters('woo_solo_api_modify_shipping_item_measure', $measure, $itemNo, $order, $shippingObject = null);

			$requestBody["jed_mjera_{$itemNo}"] = $measure;
			$requestBody["cijena_{$itemNo}"] = $shippingPrice;
			$requestBody["kolicina_{$itemNo}"] = 1;
			$requestBody["popust_{$itemNo}"] = 0;
			$requestBody["porez_stopa_{$itemNo}"] = $shippingTaxRate;
		}

		// Get the note from the customer.
		$customerNote = !empty($order->get_customer_note()) ? $order->get_customer_note() : '';

		$invoiceDueDate = $this->getDueDate($dueDate);

		$requestBody['nacin_placanja'] = $paymentType;
		$requestBody['rok_placanja'] = $invoiceDueDate;

		if (!empty($ibanNumber)) {
			$requestBody['iban'] = esc_attr($ibanNumber);
		}

		if ($billType === self::OFFER) {
			$requestBody['jezik_ponude'] = $languages;
			$requestBody['valuta_ponude'] = $currency;
		} else {
			$requestBody['jezik_racuna'] = $languages;
			$requestBody['valuta_racuna'] = $currency;
		}

		// Only for foreign currency.
		if ($currency !== '1' || $currency !== '14') { // @phpstan-ignore-line.
			$apiRates = $this->getHnbRates();

			if (empty($apiRates)) {
				throw new RuntimeException(esc_html__('Central bank rates API returned an empty result', 'woo-solo-api'));
			}

			$currency = $this->getCurrency($currency);

			$currencyRate = $apiRates[$currency];

			/**
			 * On 1.1.2023. Croatia became a part of the Euro-zone, so
			 * HRK ceased to be a valid Croatian currency.
			 */
			if (!in_array($currency, ['EUR', 'HRK'])) {
				if (!empty($currencyRate)) {
					$num = 1 / (float)str_replace(',', '.', $currencyRate);
					$requestBody['tecaj'] = str_replace('.', ',', (string)round($num, 6));

					$translatedCurrencyNote = $this->getCurrencyNote($languages);

					$currencyQuantity = '1';

					// If currency is HUF or JPY then 1 must be 100.
					if (in_array($currency, ['HUF', 'JPY'])) {
						$currencyQuantity = '100';
					}

					$customerNote .= "\n" . sprintf(
						'%1$s (%2$s EUR = %3$s %4$s)',
						esc_html($translatedCurrencyNote),
						esc_html($currencyQuantity),
						esc_html($requestBody['tecaj']),
						esc_html($currency)
					);
				}
			}
		}

		if ($billType === self::INVOICE) {
			$requestBody['fiskalizacija'] = '0';

			if (!empty($soloApiFiscalization)) {
				$requestBody['fiskalizacija'] = '1';
			}
		}

		/**
		 * Filters the custom message for customer note
		 *
		 * If you need to extend the customer note, you can just hook to this filter
		 * and modify the existing content
		 *
		 * Usage:
		 *
		 * add_filter('woo_solo_api_modify_customer_note', 'my_customer_filter', 10, 2);
		 *
		 * function my_customer_filter($customerNote, $order) {
		 *   // (maybe) modify $customerNote.
		 *   return $customerNote;
		 * }
		 *
		 * @since 2.1.0 Added order as a parameter for the filter.
		 * @since 2.0.2
		 *
		 * @param string $customerNote Existing customer note.
		 * @param object|WC_Order $order Order object.
		 *
		 * @return string Modified customer note.
		 */
		$customerNote = apply_filters('woo_solo_api_modify_customer_note', $customerNote, $order);

		$requestBody['napomene'] = wp_kses_post($customerNote);

		/**
		 * Filters the Solo API request body before it's being prepared for sending
		 *
		 * If you need to modify the request towards the SOLO API,
		 * you can just hook to this filter and modify the request body.
		 *
		 * Usage:
		 *
		 * add_filter('woo_solo_api_modify_request_body', 'my_customer_filter', 10, 2);
		 *
		 * function my_customer_filter($requestBody, $order) {
		 *   // (maybe) modify $requestBody.
		 *   return $requestBody;
		 * }
		 *
		 * @since 2.2.0
		 *
		 * @param array $requestBody Existing customer note.
		 * @param object|WP_Order $order Order object.
		 *
		 * @return array Modified request body.
		 */
		$requestBody = \apply_filters('woo_solo_api_modify_request_body', $requestBody, $order);

		$postData = $this->prepareRequestData($requestBody);

		if (defined('WP_DEBUG') && WP_DEBUG === true) {
			// phpcs:disable WordPress.PHP.DevelopmentFunctions
			error_log(print_r('Request body:', true));
			error_log(print_r($requestBody, true));
			// phpcs:enable
		}

		/**
		 * For more info go to: https://solo.com.hr/api-dokumentacija/izrada-racuna
		 */
		$response = wp_remote_post($url, ['body' => $postData]);

		if (defined('WP_DEBUG') && WP_DEBUG === true) {
			// phpcs:disable WordPress.PHP.DevelopmentFunctions
			error_log(print_r('Request response:', true));
			error_log(print_r($response, true));
			// phpcs:enable
		}

		$orderId = $order->get_id();

		if (\is_wp_error($response)) {
			/**
			 * If the above condition is true, the $response is an instance of \WP_Error class.
			 * This means that something internally is wrong with WordPress.
			 * We can extract the code and error message using get_error_code and get_error_message methods.
			 */
			$errorCode = $response->get_error_code();
			$errorMessage = $response->get_error_message();

			SoloOrdersTable::addApiResponseError($orderId, "{{$errorCode}, {$errorMessage}}");

			throw WpException::internalError($errorCode, $errorMessage);
		}

		// Try to see if the call is a successful or not.
		$responseBody = wp_remote_retrieve_body($response);
		$responseCode = wp_remote_retrieve_response_code($response);
		$responseMessage = wp_remote_retrieve_response_message($response);

		if ($responseCode < 200 || $responseCode >= 300) {
			// Write error to the database for better logging.
			SoloOrdersTable::addApiResponseError($orderId, $responseBody);

			throw ApiRequestException::apiResponse($responseCode, $responseMessage);
		}

		$responseDetails = json_decode($responseBody, true);

		if (!is_array($responseDetails)) {
			SoloOrdersTable::addApiResponseError($orderId, $responseBody);

			throw new TypeError(esc_html__('Response details from the API is not of an array type', 'woo-solo-api'));
		}

		// Usually an error if API throttling happened.
		if ($responseDetails['status'] !== 0) {
			SoloOrdersTable::addApiResponseError($orderId, $responseBody);

			throw ApiRequestException::apiResponse($responseDetails['status'], $responseDetails['message']);
		}

		// Get the Solo ID of the order.
		if ($billType === self::INVOICE) {
			$soloOrderId = $responseDetails[$billType]['broj_racuna'];
		} else {
			$soloOrderId = $responseDetails[$billType]['broj_ponude'];
		}

		/**
		 * Update the database status:
		 *
		 * Order sent to API - YES;
		 * Email sent to user - NO;
		 * Update - YES;
		 */
		SoloOrdersTable::updateOrdersTable($orderId, $soloOrderId, true, false, true);

		/**
		 * Clear the previous errors
		 *
		 * If curl error happened, for whatever reason, and you try to resend the order manually
		 * the error should be removed if the API call was successful.
		 */
		SoloOrdersTable::addApiResponseError($orderId, '');

		// Send mail to the customer with the PDF of the invoice.
		$sendPdf = get_option('solo_api_send_pdf');

		if ($sendPdf === '1') {
			// Register a background job - in 30 sec ping the API to get the PDF.
			wp_schedule_single_event(
				time() + 15,
				SendCustomerEmail::JOB_NAME,
				[
					$orderId,
					$responseDetails,
					$email,
					$billType,
					$paymentMethod,
				]
			);
		}
	}

	/**
	 * Return due date based on the passed value
	 *
	 * @param string $dueDate Due date set from the options.
	 *
	 * @return string Due date string.
	 */
	private function getDueDate(string $dueDate): string
	{
		switch ($dueDate) {
			case '1d':
				$invoiceDueDate = date('Y-m-d', strtotime('+1 day'));
				break;
			case '2d':
				$invoiceDueDate = date('Y-m-d', strtotime('+2 days'));
				break;
			case '3d':
				$invoiceDueDate = date('Y-m-d', strtotime('+3 days'));
				break;
			case '4d':
				$invoiceDueDate = date('Y-m-d', strtotime('+4 days'));
				break;
			case '5d':
				$invoiceDueDate = date('Y-m-d', strtotime('+5 days'));
				break;
			case '6d':
				$invoiceDueDate = date('Y-m-d', strtotime('+6 days'));
				break;
			case '2':
				$invoiceDueDate = date('Y-m-d', strtotime('+2 weeks'));
				break;
			case '3':
				$invoiceDueDate = date('Y-m-d', strtotime('+3 weeks'));
				break;
			default:
				$invoiceDueDate = date('Y-m-d', strtotime('+1 week'));
		}

		return $invoiceDueDate;
	}

	/**
	 * Get the currency identifier
	 *
	 * @param string $currencyID Currency ID saved in the settings.
	 * @return string String denoting the selected currency.
	 */
	private function getCurrency(string $currencyID): string
	{
		// Fallback in the case the old ID is still present:
		if ($currencyID === '1') {
			$currencyID = '14';
		}

		$currencyMap = [
			'2' => 'AUD',
			'3' => 'CAD',
			'4' => 'CZK',
			'5' => 'DKK',
			'6' => 'HUF',
			'7' => 'JPY',
			'8' => 'NOK',
			'9' => 'SEK',
			'10' => 'CHF',
			'11' => 'GBP',
			'12' => 'USD',
			'13' => 'BAM',
			'14' => 'EUR',
			'15' => 'PLN',
		];

		return $currencyMap[$currencyID];
	}

	/**
	 * Get the HNB money exchange rates from the transient
	 *
	 * @return array<string, string> The array consisting of currency => middle rate value pairs.
	 */
	private function getHnbRates(): array
	{
		$exchangeRates = get_transient(FetchExchangeRate::TRANSIENT);

		if (!is_string($exchangeRates)) {
			return [];
		}

		$apiRates = json_decode($exchangeRates, true);

		if (!is_array($apiRates)) {
			return [];
		}

		$ratesOut = [];

		foreach ($apiRates as $details) {
			$ratesOut[$details['valuta']] = $details['srednji_tecaj'];
		}

		// Add EUR as a default with value 1. Nothing will get converted.
		$ratesOut['EUR'] = 1;

		return $ratesOut;
	}

	/**
	 * Note that will be added if the currencies need to be converted.
	 *
	 * @param string $languages Language ID, set from the options
	 * @return string Note on specific language, based on the selected language.
	 */
	private function getCurrencyNote(string $languages): string
	{
		switch ($languages) {
			case '2':
				$note = 'Recalculated at the middle exchange rate of the CNB';
				break;
			case '3':
				$note = 'Zum mittleren Wechselkurs der KNB neu berechnet';
				break;
			case '4':
				$note = 'Recalculé au taux de change moyen de la BNC';
				break;
			case '5':
				$note = 'Ricalcolato al tasso di cambio medio del BNC';
				break;
			case '6':
				$note = 'Recalculado al tipo de cambio medio del BNC';
				break;
			default:
				$note = 'Preračunato po srednjem tečaju HNB-a';
				break;
		}

		return $note;
	}

	/**
	 * Helper to prepare HTTP query
	 *
	 * The SOLO API requires the specific format of the POST body, specifically
	 * they require each service to be numbered - usluga => 1, usluga => 2, etc.
	 * Because of that, we cannot just set this as an array key (because they need to be unique),
	 * so we number them, and once we turn the array to HTTP query string, we can use regex to replace numbers
	 * in the query string and use that as a body.
	 *
	 * @param array<mixed> $requestBody Request body.
	 *
	 * @return string Prepared body
	 */
	private function prepareRequestData(array $requestBody): string
	{
		$query = http_build_query($requestBody);

		return (string) preg_replace('/usluga(\d+)/', 'usluga', $query);
	}
}
