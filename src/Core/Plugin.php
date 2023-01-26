<?php

/**
 * File containing the main plugin class
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

use DI\Container;
use DI\ContainerBuilder;
use DI\Definition\Helper\AutowireDefinitionHelper;
use DI\Definition\Reference;
use Exception;
use MadeByDenis\WooSoloApi\BackgroundJobs\MakeSoloApiCall;
use MadeByDenis\WooSoloApi\Database\PluginActivationCheck;
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\ECommerce\WooCommerce\MakeApiRequest;
use MadeByDenis\WooSoloApi\Exception\PluginActivationFailure;
use MadeByDenis\WooSoloApi\Utils\FetchExchangeRate;
use Tests\Fixtures\MockApiRequest;
use WP_Upgrader;

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
final class Plugin extends Autowiring implements Registrable, HasActivation, HasDeactivation
{
	/**
	 * Array of instantiated services.
	 *
	 * @var Object[]
	 */
	private array $services = [];

	/**
	 * @var array<string, mixed>
	 */
	protected array $psr4Prefixes;

	protected string $namespace;

	/**
	 * Constructs object and inserts prefixes from composer.
	 *
	 * @param array<string, mixed> $psr4Prefixes Composer's ClassLoader psr4Prefixes. $ClassLoader->getPsr4Prefixes().
	 * @param string $projectNamespace Projects namespace.
	 */
	public function __construct(array $psr4Prefixes, string $projectNamespace)
	{
		$this->psr4Prefixes = $psr4Prefixes;
		$this->namespace = $projectNamespace;
	}

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
		add_action('upgrader_process_complete', [$this, 'updatePluginActions'], 10, 2);
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

		$this->services = $this->getServiceClassesWithDi();

		\array_walk(
			$this->services,
			static function ($class) {
				if ($class instanceof Registrable) {
					$class->register();
				}
			}
		);
	}

	/**
	 * Run actions on plugin update
	 *
	 * @param object|WP_Upgrader $upgrader WP_Upgrader instance.
	 * @param array<string, mixed> $hookExtra Array of bulk item update data.
	 *
	 * @return void
	 */
	public function updatePluginActions(object $upgrader, array $hookExtra): void
	{
		$wooSoloPlugin = 'woo-solo-api/woo-solo-api.php';

		if ($hookExtra['action'] !== 'update' || $hookExtra['type'] !== 'plugin') {
			return;
		}

		foreach ($hookExtra['plugins'] as $installedPlugins) {
			if ($installedPlugins !== $wooSoloPlugin) {
				continue;
			}

			// Clear exchange rate transient.
			\delete_transient(FetchExchangeRate::TRANSIENT);
		}
	}

	/**
	 * Get the list of services to register.
	 *
	 * A list of classes which contain hooks.
	 *
	 * @return array<class-string, string|string[]> Array of fully qualified service class names.
	 */
	protected function getServiceClasses(): array
	{
		return [];
	}

	/**
	 * Merges the autowired definition list with custom user-defined definition list.
	 *
	 * You can override autowired definition lists in $this->getServiceClasses().
	 *
	 * Taken from the following link, licenced under MIT.
	 * @link https://github.com/infinum/eightshift-libs/blob/develop/src/Main/AbstractMain.php
	 *
	 * @throws Exception Exception thrown in case class is missing.
	 *
	 * @return array<string, mixed>
	 */
	private function getServiceClassesWithAutowire(): array
	{
		return $this->buildServiceClasses($this->getServiceClasses());
	}

	/**
	 * Return array of services with Dependency Injection parameters.
	 *
	 * Taken from the following link, licenced under MIT.
	 * @link https://github.com/infinum/eightshift-libs/blob/develop/src/Main/AbstractMain.php
	 *
	 * @return Object[]
	 *
	 * @throws Exception Exception thrown by the DI container.
	 */
	private function getServiceClassesWithDi(): array
	{
		$services = $this->getServiceClassesPreparedArray();

		$container = $this->getDiContainer($services);

		return \array_map(
			static function ($class) use ($container) {
				return $container->get($class);
			},
			\array_keys($services)
		);
	}

	/**
	 * Get services classes array and prepare it for dependency injection.
	 * Key should be a class name, and value should be an empty array or the dependencies of the class.
	 *
	 * Taken from the following link, licenced under MIT.
	 * @link https://github.com/infinum/eightshift-libs/blob/develop/src/Main/AbstractMain.php
	 *
	 * @throws Exception Exception thrown in case class is missing.
	 *
	 * @return array<string, mixed>
	 */
	private function getServiceClassesPreparedArray(): array
	{
		$output = [];

		foreach ($this->getServiceClassesWithAutowire() as $class => $dependencies) {
			if (\is_array($dependencies)) {
				$output[$class] = $dependencies;
				continue;
			}

			$output[$dependencies] = [];
		}

		return $output;
	}

	/**
	 * Implement PHP-DI.
	 *
	 * Build and return a DI container.
	 * Wire all the dependencies automatically, based on the provided array of
	 * class => dependencies from the get_di_items().
	 *
	 * Taken from the following link, licenced under MIT.
	 * @link https://github.com/infinum/eightshift-libs/blob/develop/src/Main/AbstractMain.php
	 *
	 * @param array<string, mixed> $services Array of service.
	 *
	 * @throws Exception Exception thrown by the DI container.
	 *
	 * @return Container
	 */
	private function getDiContainer(array $services): Container
	{
		$definitions = [];

		foreach ($services as $serviceKey => $serviceValues) {
			if (\gettype($serviceValues) !== 'array') {
				continue;
			}

			$autowire = new AutowireDefinitionHelper();

			$definitions[$serviceKey] = $autowire->constructor(...$this->getDiDependencies($serviceValues));
		}

		$builder = new ContainerBuilder();

		if (!$this->isDevelopment()) {
			$builder->enableCompilation(__DIR__);
		}

		return $builder->addDefinitions($definitions)->build();
	}

	/**
	 * Return prepared Dependency Injection objects.
	 * If you pass a class use PHP-DI to prepare if not just output it.
	 *
	 * Taken from the following link, licenced under MIT.
	 * @link https://github.com/infinum/eightshift-libs/blob/develop/src/Main/AbstractMain.php
	 *
	 * @param array<string, mixed> $dependencies Array of classes/parameters to push in constructor.
	 *
	 * @return array<string, mixed>
	 */
	private function getDiDependencies(array $dependencies): array
	{
		return \array_map(
			function ($dependency) {
				if (\class_exists($dependency)) {
					return new Reference($dependency);
				}
				return $dependency;
			},
			$dependencies
		);
	}

	/**
	 * Helper to determine if we should compile the container
	 *
	 * Only compile container when on production, not when testing or in development.
	 *
	 * @since 3.0.0 Moved to Plugin class.
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function isDevelopment(): bool
	{
		$development = getenv('DEVELOPMENT') ||
			(defined('DEVELOPMENT') && DEVELOPMENT) ||
			(defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'development');
		$testing = getenv('TEST') || (defined('TEST') && TEST);

		if ($development || $testing) {
			return true;
		}

		return false;
	}
}
