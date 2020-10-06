<?php

/**
 * Factory pattern that creates a plugin instance
 *
 * @package Made_By_Denis\Woo_Solo_Api\Core
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Core;

/**
 * Plugin factory
 *
 * Creates a shared plugin instance.
 *
 * @package Made_By_Denis\Woo_Solo_Api\Core
 * @since 2.0.0
 */
final class PluginFactory
{
    /**
     * Create and return an instance of the plugin.
     *
     * This always returns a shared instance.
     *
     * @return Plugin Plugin instance.
     */
    public static function create(): Plugin
    {
        static $plugin = null;

        if ($plugin === null) {
            $plugin = new Plugin();
        }

        return $plugin;
    }
}
