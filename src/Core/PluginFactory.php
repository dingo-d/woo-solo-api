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
     * @param array<string, string[]> $prefixes List of PSR-4 prefixes.
     * @param string $namespace Main plugin namespace.
     *
     * @return Plugin Plugin instance.
     */
    public static function create(array $prefixes, string $namespace): Plugin
    {
        static $plugin = null;

        if ($plugin === null) {
            $plugin = new Plugin($prefixes, $namespace);
        }

        return $plugin;
    }
}
