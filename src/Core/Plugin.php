<?php

/**
 * File containing the main plugin class
 *
 * @since 2.0.0
 * @package Developer_Portal\Core
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

use Exception;
use MadeByDenis\WooSoloApi\Exception\{PluginActivationFailure, MissingManifest, InvalidService};

/**
 * Class Plugin.
 *
 * Main plugin controller class that hooks the plugin's functionality
 * into the WordPress request lifecycle.
 *
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
	 * @throws Exception If a condition for plugin activation isn't met.
	 */
    public function activate(): void
    {
        if (!function_exists('is_plugin_active_for_network')) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
        }

        if (!class_exists('WooCommerce')) {
          	// Deactivate the plugin.
            deactivate_plugins(plugin_basename(__FILE__));

            $errorMessage = __('This plugin requires ', 'woo-solo-api') .
				'<a href="' . esc_url('https://wordpress.org/plugins/woocommerce/') . '">WooCommerce</a>'
				. __(' plugin to be active.', 'woo-solo-api');

            throw PluginActivationFailure::activationMessage($errorMessage);
        }

        $this->registerServices();

        // Activate that which can be activated.
        foreach ($this->services as $service) {
            if ($service instanceof HasActivation) {
                $service->activate();
            }
        }

        \flush_rewrite_rules();
    }

	/**
	 * Deactivate the plugin.
	 *
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

        \flush_rewrite_rules();
    }

    /**
     * Register the plugin with the WordPress system.
     *
     * The register_service method will call the register() method in every service class,
     * which holds the actions and filters - effectively replacing the need to manually add
     * them in one place.
     *
     * @throws InvalidService If a service is not valid.
     */
    public function register(): void
    {
        add_action('plugins_loaded', [ $this, 'registerServices']);

//        $this->registerAssetsManifestData();
    }

    /**
     * Register bundled asset manifest
     *
     * @throws MissingManifest Throws error if manifest is missing.
     * @return void
     */
    public function registerAssetsManifestData()
    {

      	$response = file_get_contents(rtrim(plugin_dir_path(__DIR__), '/') . '/assets/public/manifest.json');

        if (! $response) {
            $error_message = __('manifest.json is missing. Bundle the plugin before using it.', 'woo-solo-api');
            throw MissingManifest::message($error_message);
        }

        if (! defined('ASSETS_MANIFEST')) {
            define('ASSETS_MANIFEST', (string) $response);
        }
    }

	/**
	 * Register the individual services of this plugin.
	 *
	 * @throws Exception If a service is not valid.
	 */
    public function registerServices()
    {
        // Bail early so we don't instantiate services twice.
        if (! empty($this->services)) {
            return;
        }

        $container = new DiContainer();

        $this->services = $container->getDiServices($this->getServiceClasses());

        array_walk(
            $this->services,
            static function ($class) {
                if (! $class instanceof Registrable) {
                    return;
                }

                $class->register();
            }
        );
    }

    /**
     * Get the list of services to register.
     *
     * A list of classes which contain hooks.
     *
     * @return array<array> Array that contains FQCN as a key of the class to instantiate, and
     *                      Array as a value of that key that denotes its dependencies.
     */
    private function getServiceClasses(): array
    {
        $services = [

        ];

        // Test mocks.
        if (getenv('TEST') === 'true') {
        	// Overwrite real classes.
        }

        return $services;
    }
}
