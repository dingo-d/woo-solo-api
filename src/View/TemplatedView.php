<?php

/**
 * Templated view class file
 *
 * @package MadeByDenis\WooSoloApi\View
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\View;

use MadeByDenis\WooSoloApi\Exception\InvalidUri;

/**
 * Templated view
 *
 * Looks within the child theme and parent theme folders first for a view,
 * before defaulting to the plugin folder.
 *
 * @package MadeByDenis\WooSoloApi\View
 * @since 2.0.0
 */
final class TemplatedView extends BaseView
{

	/**
	 * Validate an URI.
	 *
	 * @param string $uri URI to validate.
	 *
	 * @return string Validated URI.
	 * @throws InvalidUri If an invalid URI was passed into the View.
	 */
	protected function validate($uri): string
	{
		$uri = $this->checkExtension($uri, static::VIEW_EXTENSION);

		foreach ($this->getLocations($uri) as $location) {
			if (is_readable($location)) {
				return $location;
			}
		}

		if (!is_readable($uri)) {
			throw InvalidUri::fromUri($uri);
		}

		return $uri;
	}

	/**
	 * Get the possible locations for the view.
	 *
	 * @param string $uri URI of the view to get the locations for.
	 *
	 * @return array Array of possible locations.
	 */
	protected function getLocations($uri): array
	{
		return [
			trailingslashit(\get_stylesheet_directory()) . $uri,
			trailingslashit(\get_template_directory()) . $uri,
			trailingslashit(dirname(__DIR__, 2)) . $uri,
		];
	}
}
