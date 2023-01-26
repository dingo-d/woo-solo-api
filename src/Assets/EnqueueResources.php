<?php

/**
 * File holding class EnqueueResources
 *
 * @package MadeByDenis\WooSoloApi\Assets
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Assets;

use Kucrut\Vite;

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
	public const JS_URI = '/assets/dev/application.jsx';

	public const CSS_HANDLE = 'woo-solo-api-css';

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
	public function enqueueStyles($hookSuffix): void
	{
		if ($hookSuffix !== 'woocommerce_page_solo_api_options') {
			return;
		}

		Vite\register_asset(
			dirname(__DIR__, 2) . '/assets/public',
			self::JS_URI,
			[
				'handle' => self::CSS_HANDLE,
				'dependencies' => [],
				'css-dependencies' => ['wp-components'],
				'css-media' => self::MEDIA_ALL,
				'css-only' => false,
				'in-footer' => self::IN_FOOTER,
			]
		);

		wp_enqueue_style(self::CSS_HANDLE);
	}

	/**
	 * @inheritDoc
	 */
	public function enqueueScripts($hookSuffix): void
	{
		if ($hookSuffix !== 'woocommerce_page_solo_api_options') {
			return;
		}

		Vite\register_asset(
			dirname(__DIR__, 2) . '/assets/public',
			self::JS_URI,
			[
				'handle' => self::JS_HANDLE,
				'dependencies' => $this->getJsDependencies(),
				'css-dependencies' => [],
				'css-media' => self::MEDIA_ALL,
				'css-only' => false,
				'in-footer' => self::IN_FOOTER,
			]
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
		wp_set_script_translations(
			self::JS_HANDLE,
			'woo-solo-api',
			dirname(__FILE__, 3) . '/languages/'
		);
	}

	/**
	 * Get script dependencies
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script/#default-scripts-included-and-registered-by-wordpress
	 *
	 * @return string[] List of all the script dependencies
	 */
	private function getJsDependencies(): array
	{
		return [
			'wp-api',
			'wp-i18n',
			'wp-components',
			'wp-data',
			'wp-element',
			'wp-api-fetch',
		];
	}

	/**
	 * Get script localizations
	 *
	 * @return array<string, array<string, string>> Key value pair of different localizations
	 */
	private function getLocalizations(): array
	{
		return [
			'wooSoloApiLocalizations' => [
				'optionSaved' => esc_html__('Options saved.', 'woo-solo-api')
			]
		];
	}
}
