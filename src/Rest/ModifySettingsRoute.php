<?php

/**
 * File holding Modify Settings Route class
 *
 * @package MadeByDenis\WooSoloApi\Rest
 * @since 3.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest;

use MadeByDenis\WooSoloApi\Core\Registrable;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Server;

use function add_filter;

/**
 * Modify settings route class
 *
 * We want to modify the get response of the wp/v2/settings request for SOLO settings.
 *
 * @package MadeByDenis\WooSoloApi\Rest
 * @since 3.0.0
 */
class ModifySettingsRoute implements Registrable
{

	/**
	 * Register the current Registrable.
	 *
	 * A register method holds the plugin action and filter hooks.
	 * Following the single responsibility principle, every class
	 * holds a functionality for a certain part of the plugin.
	 * This is why every class should hold its own hooks.
	 *
	 * @return void
	 */
	public function register(): void
	{
		add_filter('rest_post_dispatch', [$this, 'modifySettingsResponse'], 10, 3);
	}

	/**
	 * Modify the settings response from the API
	 *
	 * We need to unserialize certain settings, so that we don't need to mess with that
	 * in the JS part.
	 *
	 * @param WP_HTTP_Response $result Response from the API.
	 * @param WP_REST_Server $server REST server.
	 * @param WP_REST_Request $request REST Request.
	 *
	 * @return WP_HTTP_Response Modified response.
	 */
	public function modifySettingsResponse(WP_HTTP_Response $result, WP_REST_Server $server, WP_REST_Request $request): WP_HTTP_Response
	{

		if ($request->get_route() !== '/wp/v2/settings') {
			return $result;
		}

		$data = $result->get_data();

		foreach ($data as $settingName => $settingValue) {
			if (strpos($settingName, 'solo_api_') === false) {
				continue;
			}

			if ($settingName === 'solo_api_mail_gateway') {
				$data['solo_api_mail_gateway'] = is_string($settingValue) ? unserialize($settingValue) : $settingValue;
			}


			if ($settingName === 'solo_api_available_gateways') {
				$data['solo_api_available_gateways'] = is_string($settingValue) ? unserialize($settingValue) : $settingValue;
			}
		}

		$result->set_data($data);

		return $result;
	}
}
