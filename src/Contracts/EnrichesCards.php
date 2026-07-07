<?php

namespace Board\PluginSdk\Contracts;

/**
 * A plugin capability: attach external references to a card (a commit, a pull
 * request, an issue…) and resolve them into a rendered status widget. The host
 * owns storage and the card UI; the plugin turns a raw reference into a live,
 * normalized payload.
 */
interface EnrichesCards
{
    /**
     * The reference types a user can attach to a card.
     *
     * @return array<int, array{key: string, label: string}>
     */
    public function cardRefTypes(): array;

    /**
     * Resolve a raw reference (an id, a "owner/repo@sha", or a full URL) into a
     * normalized widget payload, or null when it can't be resolved.
     *
     * @param  array<string, mixed>  $config  the installed instance config (incl. OAuth token)
     * @return array{ref_id: string, title: string, url?: string|null, subtitle?: string|null, badge?: string|null, badge_color?: string|null, icon?: string|null, timestamp?: string|null}|null
     */
    public function resolveCardRef(array $config, string $type, string $rawRef): ?array;
}
