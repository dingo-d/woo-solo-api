<?php

/**
 * File holding SoloApiTest class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Callbacks
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Callbacks;

use MadeByDenis\WooSoloApi\Utils\ErrorCodes;

/**
 * SoloApiTest class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Callbacks
 */
class SoloApiTest extends AjaxCallback
{
	use ErrorCodes;

	/**
	 * Callback name
	 *
	 * @var string
	 */
	public const CALLBACK_ACTION = 'get_solo_data';

	/**
	 * Nonce action string
	 *
	 * @var string
	 */
	public const NONCE_ACTION = 'solo_api_options_action';

	/**
	 * Nonce $_POST key
	 *
	 * @var string
	 */
	public const NONCE = 'nonce';

	/**
	 * Callback privacy
	 *
	 * @var bool
	 */
	public const CB_PUBLIC = false;

	/**
	 * @inheritDoc
	 */
	public function callback()
	{
		if (!isset($_POST[self::NONCE]) || !wp_verify_nonce(sanitize_key($_POST[self::NONCE]), self::NONCE_ACTION)) {
			return wp_send_json_error($this->getErrorMessage('nonce'), 404);
		}

		if (!current_user_can('manage_options')) {
			return wp_send_json_error($this->getErrorMessage('authorization'), 401);
		}

		$soloApiToken = get_option('solo_api_token');

		$response = wp_remote_get('https://api.solo.com.hr/racun?token=' . $soloApiToken);

		if (is_wp_error($response)) {
			$errorCode = wp_remote_retrieve_response_code($response);
			$errorMessage = wp_remote_retrieve_response_message($response);

			$data = $errorCode . ': ' . $errorMessage;
		} else {
			$data = json_decode($response['body'], true);
		}

		return wp_send_json_success($data, 200);
	}

	/**
	 * @inheritDoc
	 */
	protected function isPublic(): bool
	{
		return self::CB_PUBLIC;
	}

	/**
	 * @inheritDoc
	 */
	protected function getActionName(): string
	{
		return self::CALLBACK_ACTION;
	}
}
