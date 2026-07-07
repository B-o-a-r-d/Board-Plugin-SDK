<?php

namespace Board\PluginSdk\Contracts;

/**
 * A Board plugin (Power-Up). Concrete plugins live in their own Composer
 * packages and register themselves into the PluginRegistry from their service
 * provider (via Laravel package auto-discovery). The host app never depends on
 * a plugin directly — only on this SDK — which is what lets plugins ship and
 * version independently (`composer require board/plugin-github`).
 *
 * Capability interfaces (ProvidesListSource, EnrichesCards, ReceivesWebhooks)
 * are opt-in: a plugin implements the ones it supports and the host inspects
 * `instanceof` to know what it can do.
 */
interface Plugin
{
    /** Stable identifier stored on installed instances (e.g. 'github'). */
    public static function key(): string;

    /** Human label for the Power-Ups UI. */
    public function label(): string;

    /** One-line description shown in the plugin catalog. */
    public function description(): string;

    /** Phosphor icon name (without the "phosphor-" prefix), e.g. 'github-logo'. */
    public function icon(): string;

    /** Whether installing an instance requires an OAuth connection first. */
    public function requiresOAuth(): bool;

    /** OAuth provider key the host should drive (e.g. 'github'), or null. */
    public function oauthProvider(): ?string;

    /**
     * Config fields for a board instance, rendered by the host UI. Receives the
     * instance's current (decrypted) config so a plugin can return dynamic
     * options (e.g. the connected account's repositories).
     *
     * @param  array<string, mixed>  $config
     * @return array<int, array{key: string, label: string, type: string, options?: array<int, array{value: string, label: string}>, help?: string, placeholder?: string}>
     */
    public function configFields(array $config = []): array;
}
