<?php

namespace Board\PluginSdk\Contracts;

/**
 * A plugin that connects a board instance to an external provider through the
 * OAuth 2.0 "web application" flow. The host ships a single, provider-agnostic
 * broker (state handling, the authorize redirect, the code→token exchange and
 * encrypted storage on the instance config); the plugin only declares the
 * provider's endpoints and how to read back the connected account.
 *
 * The host resolves `client_id` / `client_secret` from the instance config
 * first (entered via {@see Plugin::configFields()}), then falls back to
 * `config("services.{oauthProvider}.client_id")` for an instance-wide app.
 *
 * Self-hosted providers: the host passes the instance's stored `$config` to
 * {@see self::authorizeUrl()}, {@see self::tokenUrl()} and
 * {@see self::resolveAccount()}, so a plugin can derive its endpoints from a
 * per-board config field (e.g. an `instance_url` declared in `configFields()`).
 * The parameter is optional — providers with a fixed host may ignore it.
 */
interface ProvidesOAuth
{
    /**
     * The provider's authorization endpoint (where the user grants access).
     *
     * @param  array<string, mixed>  $config  the instance's stored config
     */
    public function authorizeUrl(array $config = []): string;

    /**
     * The provider's token endpoint (POST form-encoded, JSON `access_token` back).
     *
     * @param  array<string, mixed>  $config  the instance's stored config
     */
    public function tokenUrl(array $config = []): string;

    /**
     * OAuth scopes to request.
     *
     * @return array<int, string>
     */
    public function scopes(): array;

    /**
     * Extra provider-specific query parameters merged into the authorize
     * redirect (e.g. `['allow_signup' => 'false']`). Return an empty array when
     * none are needed.
     *
     * @return array<string, string>
     */
    public function authorizeParameters(): array;

    /**
     * Resolve a human label for the connected account (e.g. the login/username)
     * from a freshly obtained access token, or null when it cannot be read.
     * Called by the host right after the token exchange to show who is connected.
     *
     * @param  array<string, mixed>  $config  the instance's stored config
     */
    public function resolveAccount(string $accessToken, array $config = []): ?string;
}
