<?php

namespace Board\PluginSdk\Contracts;

use Board\PluginSdk\PluginListItem;
use Illuminate\Support\Collection;

/**
 * A plugin capability: feed a board list with read-only "virtual cards"
 * computed from an external source (commits, pull requests, pipelines, …)
 * instead of manually-created cards.
 */
interface ProvidesListSource
{
    /**
     * The selectable source modes for a list (e.g. commits / pull_requests).
     *
     * @return array<int, array{key: string, label: string}>
     */
    public function sourceModes(): array;

    /**
     * Per-list config fields shown when creating a plugin list (on top of the
     * mode selector) — e.g. which repository to pull from. Receives the
     * instance config so options can be resolved dynamically.
     *
     * @param  array<string, mixed>  $config
     * @return array<int, array{key: string, label: string, type: string, options?: array<int, array{value: string, label: string}>, help?: string, placeholder?: string}>
     */
    public function listConfigFields(array $config = []): array;

    /**
     * Resolve the current items for a plugin list.
     *
     * @param  array<string, mixed>  $config  the installed instance config (incl. OAuth token)
     * @param  string  $mode  one of sourceModes()' keys
     * @param  array<string, mixed>  $sourceConfig  the per-list config
     * @return Collection<int, PluginListItem>
     */
    public function items(array $config, string $mode, array $sourceConfig): Collection;
}
