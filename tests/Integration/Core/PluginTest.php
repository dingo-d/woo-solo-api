<?php

namespace Tests\Integration\Core;

use MadeByDenis\WooSoloApi\Core\PluginFactory;
use MadeByDenis\WooSoloApi\Utils\FetchExchangeRate;
use Yoast\WPTestUtils\WPIntegration\TestCase;

uses(TestCase::class);

beforeEach(function () {
	parent::setUp();
});

afterEach(function () {
	parent::tearDown();
});

test('Plugin upgrader will exit early in the case the action is not update', function () {
	/*
	 * Namespace and PSR-4 prefixes shouldn't matter,
	 * as the instance of the plugin is already set during test initialization.
	 */
	$pluginInstance = PluginFactory::create(['test' => ['test']], '\\WooSoloApi\\Test');

	$hookExtra = [
		'action' => 'delete',
		'type' => 'theme',
		'bulk' => false,
		'plugins' => [
			'woocommerce/woocommerce.php',
			'woo-solo-api/woo-solo-api.php',
		],
		'themes' => ['twentytwenty'],
		'translations' => [
			'language' => 'en-En',
			'type' => 'plugin',
			'slug' => 'woo-solo-api',
			'version' => '3.0.0',
		],
	];

	$pluginInstance->updatePluginActions(new \WP_Upgrader(), $hookExtra);
	$transient = get_transient(FetchExchangeRate::TRANSIENT);

	expect($transient)->toBeEmpty();
});
