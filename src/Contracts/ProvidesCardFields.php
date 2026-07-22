<?php

namespace Board\PluginSdk\Contracts;

use Board\PluginSdk\Sdk;

/**
 * A plugin capability: inject custom fields into every card of a board where
 * the plugin is installed and active. The host materializes each declaration
 * as a managed custom field (synced on install/activation, removed on
 * uninstall) and owns the storage, validation and rendering of values.
 *
 * Adding this interface is additive — implementing it is optional and does not
 * bump {@see Sdk::CONTRACT_VERSION}.
 */
interface ProvidesCardFields
{
    /**
     * The custom fields this plugin contributes to cards.
     *
     * Each declaration:
     * - `key`       stable identifier inside this plugin (sync key, max 60 chars)
     * - `name`      display label of the field
     * - `type`      one of the host field types: text, number, date, select,
     *               checkbox, url, email, multiselect, member, money, rating,
     *               progress (unknown types fall back to text)
     * - `options`   list of choices for select/multiselect, or
     *               ['currency' => '€'] for money (optional)
     * - `placement` where the field renders in the card modal:
     *               'sidebar' (default) or 'content'
     *
     * @param  array<string, mixed>  $config  the installed instance config
     * @return array<int, array{key: string, name: string, type?: string, options?: array<int|string, mixed>|null, placement?: string}>
     */
    public function cardFields(array $config = []): array;
}
