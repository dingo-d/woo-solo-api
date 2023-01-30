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
	public const JS_HANDLE = 'woo-solo-api';
	public const JS_URI = 'assets/dev/application.jsx';
	public const VERSION = false;
	public const IN_FOOTER = true;
	public const MEDIA_ALL = 'all';

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
		add_action('admin_enqueue_scripts', [$this, 'setScriptTranslations'], 100);
	}

	/**
	 * @inheritDoc
	 */
	public function enqueueScripts($hookSuffix): void
	{
		if ($hookSuffix !== 'woocommerce_page_solo_api_options') {
			return;
		}

		Vite\enqueue_asset(
			dirname(__DIR__, 2) . '/assets/public',
			self::JS_URI,
			[
				'handle' => self::JS_HANDLE,
				'dependencies' => $this->getJsDependencies(),
				'css-dependencies' => $this->getCssDependencies(),
				'css-media' => self::MEDIA_ALL,
				'css-only' => false,
				'in-footer' => self::IN_FOOTER,
			]
		);

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
			plugin_dir_path(dirname(__DIR__)) . 'languages'
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
	 * Get style dependencies
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_style/
	 *
	 * @return string[] List of all the style dependencies
	 */
	private function getCssDependencies(): array
	{
		return [
			'wp-components'
		];
	}

	/**
	 * Get script localizations
	 *
	 * @return array<string, array<string, mixed>> Key value a pair of different localizations.
	 */
	private function getLocalizations(): array
	{
		return [];
	}
}
