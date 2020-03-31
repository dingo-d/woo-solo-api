<?php

/**
 * File that holds the renderable interface.
 *
 * @since 2.0.0
 * @package Developer_Portal\Core
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

/**
 * Renderable interface.
 *
 * An object that can be rendered.
 */
interface Renderable
{
    /**
     * Render the current Renderable.
     *
     * @param array $context Context in which to render.
     *
     * @return string Rendered HTML.
     */
    public function render(array $context = []): string;
}
