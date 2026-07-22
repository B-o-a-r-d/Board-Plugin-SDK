<?php

namespace Board\PluginSdk\Contracts;

use Board\PluginSdk\PluginServiceProvider;

/**
 * Host-provided sink that records where a plugin's pre-built assets live so the
 * host can serve them. The host binds an implementation; {@see PluginServiceProvider}
 * feeds it for every plugin that {@see ProvidesAssets}. Plugins never implement
 * this — they only declare their files via {@see ProvidesAssets}.
 */
interface AssetRegistrar
{
    /**
     * @param  string  $key  the plugin key ({@see Plugin::key()})
     * @param  string  $baseDir  absolute path of the package root (holds `dist/`)
     * @param  array<int, string>  $styles  CSS file names in `dist/`
     * @param  array<int, string>  $scripts  JS file names in `dist/`
     */
    public function register(string $key, string $baseDir, array $styles, array $scripts): void;
}
