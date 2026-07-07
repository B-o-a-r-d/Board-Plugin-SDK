<?php

namespace Board\PluginSdk;

use Board\PluginSdk\Contracts\Plugin;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

/**
 * Base service provider for a Board plugin package. Extending it (and exposing
 * it via `extra.laravel.providers`) is all a plugin needs to:
 *  - register itself into the host's PluginRegistry (guarded so the package
 *    stays loadable outside a Board host, e.g. isolated package tests), and
 *  - load its own translation files under the `<plugin-key>::` namespace, so a
 *    plugin ships strings as files without touching the host's core lang.
 */
abstract class PluginServiceProvider extends ServiceProvider
{
    /**
     * The plugin instance this provider registers.
     */
    abstract protected function plugin(): Plugin;

    public function boot(): void
    {
        $plugin = $this->plugin();

        if ($this->app->bound(PluginRegistry::class)) {
            $this->app->make(PluginRegistry::class)->register($plugin);
        }

        $path = $this->translationsPath();

        if ($path !== null && is_dir($path)) {
            $this->loadTranslationsFrom($path, $plugin::key());
        }
    }

    /**
     * Directory holding the plugin's translation files. Defaults to a `lang/`
     * directory at the package root (i.e. next to `src/`). Override to change.
     */
    protected function translationsPath(): ?string
    {
        return dirname((new ReflectionClass($this))->getFileName(), 2).'/lang';
    }
}
