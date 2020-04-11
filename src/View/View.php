<?php

/**
 * Interface that handles plugin views
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\View
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\View;

use MadeByDenis\WooSoloApi\Core\Renderable;
use MadeByDenis\WooSoloApi\Exception\FailedToLoadView;

/**
 * Interface View
 *
 * @package MadeByDenis\WooSoloApi\View
 */
interface View extends Renderable
{
	/**
     * Render a given URI.
     *
     * @param array $context Context in which to render.
     *
     * @return string Rendered HTML.
     * @throws FailedToLoadView If the View URI could not be loaded.
     */
	public function render(array $context = []): string;

	/**
	 * Render a partial view.
	 *
	 * This can be used from within a currently rendered view, to include
	 * nested partials.
	 *
	 * The passed-in context is optional, and will fall back to the parent's
	 * context if omitted.
	 *
	 * @param string     $uri     URI of the partial to render.
	 * @param array|null $context Context in which to render the partial.
	 *
	 * @return string           Rendered HTML.
	 * @throws InvalidURI       If the provided URI was not valid.
	 * @throws FailedToLoadView If the view could not be loaded.
	 */
	public function renderPartial($uri, array $context = null): string;
}
