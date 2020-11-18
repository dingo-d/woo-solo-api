<?php

/**
 * File holding class EnqueueResources
 *
 * @package MadeByDenis\WooSoloApi\Assets
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Assets;

use function add_action;
use function esc_html__;

/**
 * Enqueues all the scripts and styles in the plugin
 *
 * This class holds the logic for enqueueing the scripts and styles of the plugin.
 *
 * @package MadeByDenis\WooSoloApi\Assets
 * @since 2.0.0
 */
class EnqueueResources implements Assets
{

	public const JS_HANDLE = 'woo-solo-api-js';
	public const JS_URI = 'application.js';

	public const CSS_HANDLE = 'woo-solo-api-css';
	public const CSS_URI = 'application.css';

	public const VERSION = false;
	public const IN_FOOTER = true;

	public const MEDIA_ALL = 'all';

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('admin_enqueue_scripts', [$this, 'enqueueStyles']);
		add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
		add_action('init', [$this, 'setScriptTranslations']);
	}

	/**
	 * @inheritDoc
	 */
	public function enqueueStyles($hookSuffix)
	{
		if ($hookSuffix !== 'woocommerce_page_solo_api_options') {
			return;
		}

		wp_register_style(
			self::CSS_HANDLE,
			$this->getManifestAssetsData(self::CSS_URI),
			['wp-components'],
			self::VERSION,
			self::MEDIA_ALL
		);

		wp_enqueue_style(self::CSS_HANDLE);
	}

	/**
	 * @inheritDoc
	 */
	public function enqueueScripts($hookSuffix)
	{
		if ($hookSuffix !== 'woocommerce_page_solo_api_options') {
			return;
		}

		wp_register_script(
			self::JS_HANDLE,
			$this->getManifestAssetsData(self::JS_URI),
			$this->getJsDependencies(),
			self::VERSION,
			self::IN_FOOTER
		);

		wp_enqueue_script(self::JS_HANDLE);

		foreach ($this->getLocalizations() as $object_name => $data_array) {
			wp_localize_script(self::JS_HANDLE, $object_name, $data_array);
		}
	}

	/**
	 * Set the translations inside the JS files
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_set_script_translations/
	 *
	 * @return void
	 */
	public function setScriptTranslations(): void
	{
		wp_set_script_translations(self::JS_HANDLE, 'woo-solo-api');
	}

	/**
	 * Get script dependencies
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script/#default-scripts-included-and-registered-by-wordpress
	 *
	 * @return array List of all the script dependencies
	 */
	protected function getJsDependencies(): array
	{
		return [
			'wp-api',
			'wp-i18n',
			'wp-components',
			'wp-element',
			'wp-api-fetch',
		];
	}

	/**
	 * Get script localizations
	 *
	 * @return array Key value pair of different localizations
	 */
	protected function getLocalizations(): array
	{
		return [
			'optionSaved' => esc_html__('Options saved.', 'woo-solo-api'),
		];
	}

	/**
	 * Return full path for specific asset from manifest.json
	 *
	 * This is used for cache busting assets.
	 *
	 * @param string $key File name key you want to get from manifest.
	 *
	 * @return string Full path to asset.
	 */
	private function getManifestAssetsData(string $key = ''): string
	{
		$data = ASSETS_MANIFEST;

		if (!$key || $data === null) {
			return '';
		}

		$data = json_decode($data, true);

		if (empty($data)) {
			return '';
		}

		return isset($data[$key]) ? $data[$key] : '';
	}
}
