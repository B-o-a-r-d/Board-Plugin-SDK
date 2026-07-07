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
 */
interface ProvidesOAuth
{
    /** The provider's authorization endpoint (where the user grants access). */
    public function authorizeUrl(): string;

    /** The provider's token endpoint (POST form-encoded, JSON `access_token` back). */
    public function tokenUrl(): string;

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
     */
    public function resolveAccount(string $accessToken): ?string;
}
