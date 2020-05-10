<?php

/**
 * Templated view class file
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\View
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\View;

use MadeByDenis\WooSoloApi\Exception\InvalidURI;

/**
 * Templated view class
 *
 * Looks within the child theme and parent theme folders first for a view,
 * before defaulting to the plugin folder.
 */
final class TemplatedView extends BaseView
{

	/**
	 * Validate an URI.
	 *
	 * @param string $uri URI to validate.
	 *
	 * @return string Validated URI.
	 * @throws InvalidURI If an invalid URI was passed into the View.
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
			throw InvalidURI::fromUri($uri);
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
