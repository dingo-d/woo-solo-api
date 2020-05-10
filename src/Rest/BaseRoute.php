<?php

/**
 * File holding BaseRoute class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Rest
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Rest;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * BaseRoute class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Rest
 */
abstract class BaseRoute implements Route, Registrable
{

	/**
	 * The custom REST prefix
	 *
	 * @var string
	 */
	public const NAMESPACE_NAME = 'woo-solo-api';

	/**
	 * The version of the API
	 *
	 * @var string
	 */
	public const VERSION = '/v1';

	/**
	 * The name of the registered route
	 *
	 * @var string
	 */
	public const ROUTE_NAME = '';

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('rest_api_init', [$this, 'registerRestRoute']);
	}

	public function registerRestRoute()
	{
		register_rest_route(
			self::NAMESPACE_NAME . self::VERSION,
			$this->getCallbackRoute(),
			$this->getCallbackArguments(),
			$this->overrideRoute()
		);
	}

	/**
	 * Get the base url of the route
	 *
	 * @return string The base URL for route you are adding.
	 */
	protected function getCallbackRoute(): string
	{
		return static::ROUTE_NAME;
	}

	/**
	 * Get callback arguments array
	 *
	 * @return array Either an array of options for the endpoint, or an array of arrays for multiple methods.
	 */
	abstract protected function getCallbackArguments(): array;

	/**
	 * Override the existing route
	 *
	 * @return bool If the route already exists, should we override it?
	 * True overrides, false merges (with newer overriding if duplicate keys exist).
	 */
	protected function overrideRoute(): bool
	{
		return false;
	}
}
