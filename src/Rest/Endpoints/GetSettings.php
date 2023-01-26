<?php

/**
 * File holding GetSettings class
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 3.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest\Endpoints;

use MadeByDenis\WooSoloApi\ECommerce\PaymentGateways;
use MadeByDenis\WooSoloApi\Rest\BaseRoute;
use MadeByDenis\WooSoloApi\Rest\RestCallable;
use WP_REST_Request;

use function get_option;

/**
 * Get plugin settings endpoint
 *
 * This class holds the callback function for the REST endpoint used to get plugin settings
 *
 * @package MadeByDenis\WooSoloApi\Rest\Endpoints
 * @since 3.0.0
 */
class GetSettings extends BaseRoute implements RestCallable
{
	use IsUserLoggedIn;

	public const ROUTE_NAME = '/solo-settings';

	/**
	 * @var PaymentGateways
	 */
	private PaymentGateways $wooPaymentGateways;

	/**
	 * Class constructor
	 *
	 * @param PaymentGateways $wooPaymentGateways Payment gateway dependency.
	 */
	public function __construct(PaymentGateways $wooPaymentGateways)
	{
		$this->wooPaymentGateways = $wooPaymentGateways;
	}

	/**
	 * @inheritDoc
	 */
	protected function getCallbackArguments(): array
	{
		return [
			'methods' => static::READABLE,
			'callback' => [$this, 'restCallback'],
			'permission_callback' => [$this, 'canUserAccessEndpoint'],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function restCallback(WP_REST_Request $request)
	{
		$data = [];

		$data['solo_api_token'] = get_option('solo_api_token');
		$data['solo_api_measure'] = get_option('solo_api_measure');
		$data['solo_api_payment_type'] = get_option('solo_api_payment_type');
		$data['solo_api_languages'] = get_option('solo_api_languages');
		$data['solo_api_currency'] = get_option('solo_api_currency');
		$data['solo_api_service_type'] = get_option('solo_api_service_type');
		$data['solo_api_show_taxes'] = get_option('solo_api_show_taxes');
		$data['solo_api_tax_rate'] = get_option('solo_api_tax_rate');
		$data['solo_api_invoice_type'] = get_option('solo_api_invoice_type');
		$data['solo_api_mail_title'] = get_option('solo_api_mail_title');
		$data['solo_api_message'] = get_option('solo_api_message');
		$data['solo_api_change_mail_from'] = get_option('solo_api_change_mail_from');
		$data['solo_api_enable_pin'] = get_option('solo_api_enable_pin');
		$data['solo_api_enable_iban'] = get_option('solo_api_enable_iban');
		$data['solo_api_currency_rate'] = get_option('solo_api_currency_rate');
		$data['solo_api_due_date'] = get_option('solo_api_due_date');

		$mailGateways = get_option('solo_api_mail_gateway');
		$data['solo_api_mail_gateway'] = is_string($mailGateways) ? unserialize($mailGateways) : $mailGateways;

		$availableGateways = get_option('solo_api_available_gateways');
		$data['solo_api_available_gateways'] = is_string($availableGateways) ? unserialize($availableGateways) : $availableGateways;

		$data['solo_api_send_pdf'] = get_option('solo_api_send_pdf');

		$availableWooGateways = $this->wooPaymentGateways->getAvailablePaymentGateways();

		foreach ($availableWooGateways as $gatewayWooValue) {
			$data["solo_api_bill_offer-{$gatewayWooValue->id}"] = get_option('solo_api_bill_offer-' . esc_attr($gatewayWooValue->id));
			$data["solo_api_fiscalization-{$gatewayWooValue->id}"] = get_option('solo_api_fiscalization-' . esc_attr($gatewayWooValue->id));
		}

		return rest_ensure_response($data);
	}
}
