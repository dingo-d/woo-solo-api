<?php

/**
 * File holding SoloRequest class
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\ECommerce\WooCommerce;


use MadeByDenis\WooSoloApi\Core\Registrable;
use MadeByDenis\WooSoloApi\Utils\FetchExchangeRate;
use WC_Email_New_Order;
use WC_Order;
use WP_Error;

/**
 * SoloRequest class
 *
 * Class that makes api request towards SOLO service
 * and handles sending emails to clients from the SOLO service
 *
 * @package MadeByDenis\WooSoloApi\ECommerce
 * @since 2.0.0
 */
class SoloRequest implements Registrable
{
	/**
	 * Solo API URL
	 *
	 * @var string
	 */
	public const URL = 'https://api.solo.com.hr/';

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('woocommerce_email_order_details', [$this, 'sendApiRequest'], 15, 4);
		add_action('woocommerce_order_status_completed', [$this, 'sendApiRequestOnOrderCompleted'], 10, 1);

		// Explore the following hooks to see if we can cover both cases at once.
		// woocommerce_payment_complete_order_status
		// woocommerce_payment_complete
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
	 * @param WC_Email_New_Order $email Order email object.
	 *
	 * @retrun void
	 * @since 1.9.5 Add check if the order was sent to avoid multiple API calls. Separate order completed call.
	 * @since 1.4.0 Fix the send api method.
	 * @since 1.3.0 Added tax checks and additional debug options.
	 * @since 1.0.0
	 */
	public function sendApiRequest($order, bool $sentToAdmin = false, bool $plainText, WC_Email_New_Order $email): void
	{
		// This should run only on the front facing side.
		if (is_admin()) {
			return;
		}

		// Double check just to be sure.
		$sendControl = get_option('solo_api_send_control');

		if ($sendControl === 'status_change') {
			return;
		}

		$uniqueId = $order->get_id() . '-' . date('Ymd');

		// If the order was sent, don't send it again.
		// This checks the option where sent orders are stored and if the order is stored,
		// don't send request to solo API.
		if ($this->wasOrderSent($uniqueId)) {
			return;
		}

		$ordersList[] = $uniqueId;

		update_option('solo_sent_orders', wp_json_encode($ordersList));

		$this->executeSoloApiCall($order);
	}

	/**
	 * Send API call when order status changes
	 *
	 * @param int $orderID The ID of the order.
	 *
	 * @retrun void
	 * @since 1.9.5
	 *
	 */
	public function sendApiRequestOnOrderCompleted(int $orderID): void
	{
		// If the option is selected to send pdf on admin we should only run it on admin.
		if (!is_admin()) {
			return;
		}

		$uniqueId = $orderID . '-' . date('Ymd');

		// If the order was sent, don't send it again.
		// This checks the option where sent orders are stored and if the order is stored,
		// don't send request to solo API.
		if ($this->wasOrderSent($uniqueId)) {
			return;
		}

		$ordersList[] = $uniqueId;

		update_option('solo_sent_orders', wp_json_encode($ordersList));

		$order = \wc_get_order($orderID);

		// Execute only if status is changed to completed!
		$orderData = $order->get_data();
		$order_status = $orderData['status'];

		if ($order_status === 'completed') {
			$this->executeSoloApiCall($order);
		}
	}

	/**
	 * Execute the call to the SOLO API
	 *
	 * @param WC_Order $order Order data.
	 *
	 * @return WP_Error
	 * @since  1.7.0 Fixed tax rates and payment types per payment gateway.
	 * @since  1.4.0 Separated the send method for more control. Add
	 *               Check to send the mail in this method, so that the
	 *               method that controls the send is not called at all.
	 *
	 * @since  2.0.0 Refactored the method
	 * @since  1.9.5 Added check for empty taxes, fixed sending requests two times.
	 *
	 * @since  1.9.3 Added translated notes for recalculating rates.
	 * @since  1.8.1 Added a check for no taxes on items.
	 */
	private function executeSoloApiCall(WC_Order $order)
	{
		// Options.
		$token = get_option('solo_api_token');
		$measure = get_option('solo_api_measure');
		$languages = get_option('solo_api_languages');
		$currency = get_option('solo_api_currency');
		$serviceType = get_option('solo_api_service_type');
		$showTaxes = get_option('solo_api_show_taxes');
		$invoiceType = get_option('solo_api_invoice_type');
		$dueDate = get_option('solo_api_due_date');

		$orderData = $order->get_data(); // The Order data.

		// Check if billing or shipping.
		$field = 'shipping';

		if (!empty($orderData['billing']['first_name'])) {
			$field = 'billing';
		}

		$firstName = $orderData[$field]['first_name'] ?? '';
		$lastName = $orderData[$field]['last_name'] ?? '';
		$address1 = $orderData[$field]['address_1'] ?? '';
		$address2 = $orderData[$field]['address_2'] ?? '';
		$city = $orderData[$field]['city'] ?? '';
		$state = $orderData[$field]['state'] ?? '';
		$country = $orderData[$field]['country'] ?? '';
		$postcode = $orderData[$field]['postcode'] ?? '';
		$email = $orderData[$field]['email'] ?? '';

		$metaData = $orderData['meta_data'];

		foreach ($metaData as $data => $metaValue) {
			$data = $metaValue->get_data();

			if ($data['key'] === '_shipping_pin_number') {
				$pinNumber = $data['value'] ?? '';
			}

			if ($data['key'] === '_shipping_iban_number') {
				$ibanNumber = $data['value'] ?? '';
			}

			if ($field === 'billing') {
				if ($data['key'] === '_billing_pin_number') {
					$pinNumber = $data['value'] ?? '';
				}

				if ($data['key'] === '_billing_iban_number') {
					$ibanNumber = $data['value'] ?? '';
				}
			}
		}

		$orderBuyer = "$firstName $lastName";

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

		$paymentMethod = $orderData['payment_method'];

		$billType = get_option('solo_api_bill_offer-' . esc_attr($paymentMethod));
		$soloApiFiscalization = get_option('solo_api_fiscalization-' . esc_attr($paymentMethod));
		$paymentType = get_option('solo_api_payment_type-' . esc_attr($paymentMethod));

		$url = self::URL . $billType;

		// Get all data needed for the request.
		$requestBody = [
			'token' => $token,
			'tip_usluge' => $serviceType,
			'prikazi_porez' => $showTaxes,
			'kupac_naziv' => esc_attr($orderBuyer),
			'kupac_adresa' => esc_attr($orderAddress),
		];

		$requestBody['tip_racuna'] = 4; // Default is no label.

		if ($billType === 'racun') {
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

			$taxRates = array_values(\WC_Tax::get_base_tax_rates($itemDetail->get_tax_class()));

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
			$requestBody["jed_mjera_{$itemNo}"] = $measure;
			$requestBody["cijena_{$itemNo}"] = $lineTotal;
			$requestBody["kolicina_{$itemNo}"] = $quantity;

			/**
			 * WooCommerce will handle counting the discounts for us.
			 * This is why this is set to 0.
			 * We can hook into this if we want to change it.
			 * But this hook will affect every item.
			 */
			$requestBody["popust_{$itemNo}"] = apply_filters('woo-solo-api-global-discount', 0);
			$requestBody["porez_stopa_{$itemNo}"] = $taxRate;

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
				$shippingTaxRates = array_values(\WC_Tax::get_base_tax_rates($shippingObject->get_tax_class()));
			}

			$shippingTaxRate = !empty($shippingTaxRates) ? (float)$shippingTaxRates[0]['rate'] : 0;

			$shippingPrice = number_format($totalShipping, 2, ',', '.');

			if (!in_array($shippingTaxRate, [5, 13, 25])) {
				$shippingTaxRate = 0;
			}

			$requestBody["usluga{$itemNo}"] = $itemNo;
			$requestBody["opis_usluge_{$itemNo}"] = esc_html__('Shipping fee', 'woo-solo-api');
			$requestBody["jed_mjera_{$itemNo}"] = $measure;
			$requestBody["cijena_{$itemNo}"] = $shippingPrice;
			$requestBody["kolicina_{$itemNo}"] = 1;
			$requestBody["popust_{$itemNo}"] = 0;
			$requestBody["porez_stopa_{$itemNo}"] = $shippingTaxRate;
		}

		// Get the note from the customer.
		$customerNote = $order->data['customer_note'] ?? '';

		$invoiceDueDate = $this->getDueDate($dueDate);

		$requestBody['nacin_placanja'] = $paymentType;
		$requestBody['rok_placanja'] = $invoiceDueDate;

		if (!empty($ibanNumber)) {
			$requestBody['iban'] = esc_attr($ibanNumber);
		}

		if ($billType === 'ponuda') {
			$requestBody['jezik_ponude'] = $languages;
			$requestBody['valuta_ponude'] = $currency;
		} else {
			$requestBody['jezik_racuna'] = $languages;
			$requestBody['valuta_racuna'] = $currency;
		}

		if ($currency !== '1') { // Only for foreign currency.

			$apiRates = $this->getHnbRates();
			$currency = $this->getCurrency($currency);

			$currencyRate = $apiRates[$currency];

			if (!empty($currencyRate)) {
				$num = (float)str_replace(',', '.', $currencyRate);
				$requestBody['tecaj'] = str_replace('.', ',', round($num, 6));

				$translatedCurrencyNote = $this->getCurrencyNote($languages);

				$currencyQuantity = '1';

				// If currency is HUF or JPY then 1 must be 100.
				if (in_array($currency, ['HUF', 'JPY'])) {
					$currencyQuantity = '100';
				}

				$customerNote .= "\n" . sprintf(
						'%1$s (%2$s %3$s = %4$s HRK)',
						esc_html($translatedCurrencyNote),
						esc_html($currencyQuantity),
						esc_html($currency),
						esc_html(str_replace('.', ',', round($num, 6)))
					);
			}
		}

		if ($billType === 'racun') {
			$requestBody['fiskalizacija'] = '0';

			if (!empty($soloApiFiscalization)) {
				$requestBody['fiskalizacija'] = '1';
			}
		}

		$customerNote = apply_filters('woo-solo-api-customer-note', $customerNote);

		$requestBody['napomene'] = wp_kses_post($customerNote);

		$postUrl = $this->prepareRequestData($requestBody);

		$regularUrl = str_replace(' ', '%20', $postUrl);

		if (defined('WP_DEBUG') && WP_DEBUG === true) {
			// phpcs:disable WordPress.PHP.DevelopmentFunctions
			error_log(print_r($regularUrl, true));
			// phpcs:enable
		}

		/**
		 * For more info go to: https://solo.com.hr/api-dokumentacija/izrada-racuna
		 */
		$response = wp_remote_post($regularUrl);

		if (defined('WP_DEBUG') && WP_DEBUG === true) {
			// phpcs:disable WordPress.PHP.DevelopmentFunctions
			error_log(print_r($response, true));
			// phpcs:enable
		}

		if (is_wp_error($response)) {
			$error_code = wp_remote_retrieve_response_code($response);
			$error_message = wp_remote_retrieve_response_message($response);
			return new WP_Error($error_code, $error_message);
		}

		$body = json_decode($response['body']);

		if ($body->status !== 0) {
			return new WP_Error($body->status, $body->message);
		}

		// Send mail to the customer with the PDF of the invoice.
		$solo_api_send_pdf = get_option('solo_api_send_pdf');

		if ($solo_api_send_pdf === '1') {
			$this->solo_api_send_mail(
				$body,
				sanitize_email($email),
				$billType,
				$paymentMethod
			);
		}
	}

	/**
	 * Check if order was sent
	 *
	 * The unique order ID gets written in an options array as a string
	 * and then checked against in order to prevent sending duplicate requests for the same order.
	 *
	 * @param string $id Order ID.
	 *
	 * @return bool
	 * @since 2.0.0
	 *
	 */
	private function wasOrderSent(string $id): bool
	{
		$sentOrders = get_option('solo_sent_orders');

		if (empty($sentOrders)) {
			return false;
		}

		$ordersList = json_decode($sentOrders, true);

		if (!empty($ordersList) && in_array($id, $ordersList)) {
			return true;
		}

		return false;
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
		$currencyMap = [
			'1' => 'HRK',
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
	 * @array The array consisting of currency => middle rate value pairs.
	 */
	private function getHnbRates(): array
	{
		$apiRates = json_decode(get_transient(FetchExchangeRate::TRANSIENT), true);

		$ratesOut = [];

		foreach ($apiRates as $details) {
			$ratesOut[$details['valuta']] = $details['srednji_tecaj'];
		}

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
				$note = esc_html__(
					'Recalculated at the middle exchange rate of the CNB',
					'woo-solo-api'
				);
				break;
			case '3':
				$note = esc_html__(
					'Zum mittleren Wechselkurs der KNB neu berechnet',
					'woo-solo-api'
				);
				break;
			case '4':
				$note = esc_html__(
					'Recalculé au taux de change moyen de la BNC',
					'woo-solo-api'
				);
				break;
			case '5':
				$note = esc_html__(
					'Ricalcolato al tasso di cambio medio del BNC',
					'woo-solo-api'
				);
				break;
			case '6':
				$note = esc_html__(
					'Recalculado al tipo de cambio medio del BNC',
					'woo-solo-api'
				);
				break;
			default:
				$note = esc_html__('Preračunato po srednjem tečaju HNB-a', 'woo-solo-api');
				break;
		}

		return $note;
	}

	/**
	 * @param array $requestBody
	 * @return string Prepared body
	 */
	private function prepareRequestData(array $requestBody): string
	{
		$query = http_build_query($requestBody);

		return preg_replace('/usluga(\d+)/', 'usluga', $query);
	}


}
