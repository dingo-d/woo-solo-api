<?php

/**
 * File holding class EnqueueResources
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\Assets
 */

declare(strict_types=1);


namespace MadeByDenis\WooSoloApi\Assets;


/**
 * Class EnqueueResources
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\Assets
 */
class EnqueueResources implements Assets
{

	const JS_HANDLE = 'woo-solo-api-js';
	const JS_URI = 'application.js';

	const CSS_HANDLE = 'woo-solo-api-css';
	const CSS_URI = 'application.css';

	const VERSION = false;
	const IN_FOOTER = true;

	const MEDIA_ALL = 'all';

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
			$this->get_manifest_assets_data(self::CSS_URI),
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
			$this->get_manifest_assets_data(self::JS_URI),
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
	 */
	public function setScriptTranslations()
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
	 * This is used for cache busting assets.
	 *
	 * @param string $key File name key you want to get from manifest.
	 * @return string     Full path to asset.
	 */
	private function get_manifest_assets_data(string $key = null): string
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
