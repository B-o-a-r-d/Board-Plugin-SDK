<?php

namespace Board\PluginSdk\Contracts;

use Board\PluginSdk\Support\PluginSettings;

/**
 * Opt-in capability: a plugin that has INSTANCE-level settings — configured once
 * by a platform admin in the marketplace after install, not per board.
 *
 * This is the "no-code" counterpart to editing `.env`: instead of a deployment
 * setting environment variables, the plugin declares its settings here and the
 * host renders a form and persists the values (see {@see PluginSettings}).
 *
 * Contrast with {@see Plugin::configFields()}, which is PER-BOARD config stored
 * on each installed board instance (e.g. OAuth client id/secret for that board).
 */
interface ProvidesSettings
{
    /**
     * Instance-level setting fields, rendered by the marketplace admin UI.
     *
     * Each field: `key` and `label` are required; `type` is one of
     * text|url|password|number|boolean|select (default text); `required` marks a
     * field that must be filled; `default` seeds the initial value; `options`
     * lists {value,label} pairs for a select; `help`/`placeholder` are hints.
     *
     * @return array<int, array{key: string, label: string, type?: string, required?: bool, default?: mixed, help?: string, placeholder?: string, options?: array<int, array{value: string, label: string}>}>
     */
    public function settings(): array;
}
