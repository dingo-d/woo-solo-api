<?php

/**
 * File that holds the renderable interface.
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

/**
 * Renderable interface.
 *
 * An object that can be rendered.
 *
 * @package Developer_Portal\Core
 * @since 2.0.0
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
