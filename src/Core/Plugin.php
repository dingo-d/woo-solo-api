<?php

/**
 * File containing the main plugin class
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

use Exception;
use MadeByDenis\WooSoloApi\Admin\AdminNotices\DatabaseTableMissingNotice;
use MadeByDenis\WooSoloApi\Admin\PluginsPage;
use MadeByDenis\WooSoloApi\Admin\AdminMenus\OptionsSubmenu;
use MadeByDenis\WooSoloApi\Assets\EnqueueResources;
use MadeByDenis\WooSoloApi\BackgroundJobs\MakeSoloApiCall;
use MadeByDenis\WooSoloApi\BackgroundJobs\SendCustomerEmail;
use MadeByDenis\WooSoloApi\Database\PluginActivationCheck;
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\ECommerce\WooCommerce\AdminOrder;
use MadeByDenis\WooSoloApi\ECommerce\WooCommerce\CheckoutFields;
use MadeByDenis\WooSoloApi\ECommerce\WooCommerce\MakeApiRequest;
use MadeByDenis\WooSoloApi\ECommerce\WooCommerce\WooPaymentGateways;
use MadeByDenis\WooSoloApi\Email\EmailFunctionality;
use MadeByDenis\WooSoloApi\i18n\Internationalization;
use MadeByDenis\WooSoloApi\Privacy\DataHandling;
use MadeByDenis\WooSoloApi\Request\SoloApiRequest;
use MadeByDenis\WooSoloApi\Rest\Endpoints\{OrderDetails, OrderDetailsCollection};
use MadeByDenis\WooSoloApi\Utils\FetchExchangeRate;
use MadeByDenis\WooSoloApi\Exception\{MissingManifest, PluginActivationFailure};
use MadeByDenis\WooSoloApi\Rest\Endpoints\AccountDetails;
use MadeByDenis\WooSoloApi\Settings\PluginSettings;
use Tests\Fixtures\MockApiRequest;

use function add_action;
use function deactivate_plugins;
use function esc_html__;
use function flush_rewrite_rules;

/**
 * Plugin entrypoint
 *
 * Main plugin controller class that hooks the plugin's functionality
 * into the WordPress request lifecycle.
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */
final class Plugin implements Registrable, HasActivation, HasDeactivation
{

	/**
	 * Array of instantiated services.
	 *
	 * @var array
	 */
	private $services = [];

	/**
	 * Activate the plugin
	 *
	 * @return void
	 * @throws Exception If a condition for plugin activation isn't met.
	 */
	public function activate(): void
	{
		if (!function_exists('is_plugin_active_for_network')) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		if (version_compare((string)PHP_VERSION_ID, '70300', '<')) {
			deactivate_plugins(plugin_basename(__FILE__));

			$error_message = esc_html__('This plugin requires PHP 7.3 or greater to function.', 'woo-solo-api');

			throw PluginActivationFailure::activationMessage($error_message);
		}

		if (!class_exists('WooCommerce')) {
			// Deactivate the plugin.
			deactivate_plugins(plugin_basename(__FILE__));

			$errorMessage = esc_html__('This plugin requires WooCommerce plugin to be active.', 'woo-solo-api');

			throw PluginActivationFailure::activationMessage($errorMessage);
		}

		SoloOrdersTable::createTable();

		$this->registerServices();

		// Activate that which can be activated.
		foreach ($this->services as $service) {
			if ($service instanceof HasActivation) {
				$service->activate();
			}
		}

		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function deactivate(): void
	{
		$this->registerServices();

		// Deactivate that which can be deactivated.
		foreach ($this->services as $service) {
			if ($service instanceof HasDeactivation) {
				$service->deactivate();
			}
		}

		flush_rewrite_rules();
	}

	/**
	 * Register the plugin with the WordPress system.
	 *
	 * The register_service method will call the register() method in every service class,
	 * which holds the actions and filters - effectively replacing the need to manually add
	 * them in one place.
	 */
	public function register(): void
	{
		/**
		 * If WooCommerce is not active deactivate the plugin and prevent execution of the container.
		 * This prevents nasty errors if users accidentally deactivate WooCommerce before this plugin.
		 */
		if (!PluginActivationCheck::isWooCommerceActive()) {
			PluginActivationCheck::deactivatePlugin();
			return;
		}

		add_action('plugins_loaded', [$this, 'registerServices']);

		$this->registerAssetsManifestData();
	}

	/**
	 * Register the individual services of this plugin.
	 *
	 * @return void
	 * @throws Exception If a service is not valid.
	 */
	public function registerServices(): void
	{
		// Bail early so we don't instantiate services twice.
		if (!empty($this->services)) {
			return;
		}

		static $container = null;

		if ($container === null) {
			$container = new DiContainer();
		}

		$this->services = $container->getDiServices($this->getServiceClasses());

		array_walk(
			$this->services,
			static function ($class) {
				if (!$class instanceof Registrable) {
					return;
				}

				$class->register();
			}
		);
	}

	/**
	 * Register bundled asset manifest
	 *
	 * @return void
	 * @throws MissingManifest Throws error if manifest is missing.
	 */
	public function registerAssetsManifestData()
	{
		$response = file_get_contents(dirname(__DIR__, 2) . '/assets/public/manifest.json');

		if (!$response) {
			$error_message = esc_html__('manifest.json is missing. Bundle the plugin before using it.', 'woo-solo-api');
			throw MissingManifest::message($error_message);
		}

		if (!defined('ASSETS_MANIFEST')) {
			define('ASSETS_MANIFEST', (string)$response);
		}
	}

	/**
	 * Get the list of services to register.
	 *
	 * A list of classes which contain hooks.
	 *
	 * @return array<int|string, array<int, string>|string> Array that contains FQCN as a key of the class to instantiate,
	 *                                                      and array as a value of that key that denotes its dependencies.
	 */
	private function getServiceClasses(): array
	{
		$services = [
			AccountDetails::class,
			AdminOrder::class,
			CheckoutFields::class,
			DataHandling::class,
			DatabaseTableMissingNotice::class,
			EmailFunctionality::class,
			EnqueueResources::class,
			FetchExchangeRate::class,
			Internationalization::class,
			OptionsSubmenu::class,
			OrderDetails::class,
			OrderDetailsCollection::class,
			PluginsPage::class,
			PluginSettings::class => [WooPaymentGateways::class],
			SendCustomerEmail::class,
			MakeSoloApiCall::class => [SoloApiRequest::class],
			MakeApiRequest::class => [SoloOrdersTable::class, SoloApiRequest::class],
		];

		// Test mocks.
		if (getenv('TEST') === 'true') {
			$services[MakeSoloApiCall::class] = [MockApiRequest::class];
			$services[MakeApiRequest::class] = [SoloOrdersTable::class, MockApiRequest::class];
		}

		return $services;
	}
}
