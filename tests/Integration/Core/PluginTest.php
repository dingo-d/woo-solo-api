<?php

namespace Tests\Integration\Core;

use MadeByDenis\WooSoloApi\Core\PluginFactory;
use MadeByDenis\WooSoloApi\Utils\FetchExchangeRate;
use Mockery;
use WP_Upgrader;
use Yoast\WPTestUtils\WPIntegration\TestCase;

uses(TestCase::class);

beforeEach(function () {
	parent::setUp();
	delete_transient(FetchExchangeRate::TRANSIENT);

	$this->upgradeMock = Mockery::mock(WP_Upgrader::class);
	/*
	 * Namespace and PSR-4 prefixes shouldn't matter,
	 * as the instance of the plugin is already set during test initialization.
	 */
	$this->pluginInstance = PluginFactory::create(['test' => ['test']], '\\WooSoloApi\\Test');
});

afterEach(function () {
	$this->upgradeMock = null;
	$this->pluginInstance = null;
	delete_transient(FetchExchangeRate::TRANSIENT);

	parent::tearDown();
});

test('Plugin upgrader will exit early in the case the action is not update', function () {
	$hookExtra = [
		'action' => 'delete',
		'type' => 'plugin',
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

	$this->pluginInstance->updatePluginActions($this->upgradeMock, $hookExtra);
	$transient = get_transient(FetchExchangeRate::TRANSIENT);

	expect($transient)->toBeEmpty();
});

test('Plugin upgrader will exit early in the case the type is not plugin', function () {
	$hookExtra = [
		'action' => 'update',
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

	$this->pluginInstance->updatePluginActions($this->upgradeMock, $hookExtra);
	$transient = get_transient(FetchExchangeRate::TRANSIENT);

	expect($transient)->toBeEmpty();
});

test('Plugin upgrader won\'t do anything in the case our plugin is not in the list', function () {
	$hookExtra = [
		'action' => 'update',
		'type' => 'plugin',
		'bulk' => false,
		'plugins' => [
			'woocommerce/woocommerce.php',
		],
		'themes' => ['twentytwenty'],
		'translations' => [
			'language' => 'en-En',
			'type' => 'plugin',
			'slug' => 'woo-solo-api',
			'version' => '3.0.0',
		],
	];

	$this->pluginInstance->updatePluginActions($this->upgradeMock, $hookExtra);
	$transient = get_transient(FetchExchangeRate::TRANSIENT);

	expect($transient)->toBeEmpty();
});

test('Plugin upgrader will delete the transient', function () {
	set_transient(FetchExchangeRate::TRANSIENT, 'Testing transient!');
	$transient = get_transient(FetchExchangeRate::TRANSIENT);

	expect($transient)
		->not->toBeEmpty()
		->toBe('Testing transient!');

	$hookExtra = [
		'action' => 'update',
		'type' => 'plugin',
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

	$this->pluginInstance->updatePluginActions($this->upgradeMock, $hookExtra);
	$transient = get_transient(FetchExchangeRate::TRANSIENT);

	expect($transient)->toBeEmpty();
});
