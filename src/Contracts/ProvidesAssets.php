<?php

namespace Board\PluginSdk\Contracts;

/**
 * A plugin capability: ship front-end assets (compiled CSS / JS) served on the
 * plugin's own pages — the Filament model.
 *
 * A runtime-installed plugin cannot join the host's asset build (its Blade is
 * never scanned by the host's Tailwind purge, and in production it is installed
 * AFTER the image is built). So the plugin **ships pre-built assets**: the
 * author compiles them once (Tailwind, a bundler…) and commits the output to a
 * `dist/` directory at the package root. The host serves those files from the
 * install volume through a versioned route and injects them on the plugin's
 * pages via the `<x-plugin-assets>` component.
 *
 * File names are relative to the package's `dist/` directory. This capability
 * is ADDITIVE to the SDK contract: hosts that predate it never call it.
 */
interface ProvidesAssets
{
    /**
     * CSS files (in `dist/`) to load on the plugin's pages, e.g. ['shelf.css'].
     *
     * @return array<int, string>
     */
    public function assetStyles(): array;

    /**
     * JS files (in `dist/`) to load on the plugin's pages, e.g. ['shelf.js'].
     * They run in the host page and may use host globals (window.Alpine,
     * window.boardTiptap); they must NOT bundle their own copy of those.
     *
     * @return array<int, string>
     */
    public function assetScripts(): array;
}
