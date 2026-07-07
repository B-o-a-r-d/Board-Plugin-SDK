<?php

namespace Board\PluginSdk;

use Board\PluginSdk\Contracts\Plugin;

/**
 * The runtime catalog of available plugins. The host app binds a single
 * instance as a container singleton; each installed plugin package registers
 * itself here from its service provider (Laravel package auto-discovery), so
 * `composer require`-ing a plugin is all it takes to make it available.
 */
class PluginRegistry
{
    /** @var array<string, Plugin> */
    private array $plugins = [];

    public function register(Plugin $plugin): void
    {
        $this->plugins[$plugin::key()] = $plugin;
    }

    /** @return array<string, Plugin> */
    public function all(): array
    {
        return $this->plugins;
    }

    public function get(string $key): ?Plugin
    {
        return $this->plugins[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->plugins[$key]);
    }
}
