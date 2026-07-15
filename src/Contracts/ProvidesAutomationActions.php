<?php

namespace Board\PluginSdk\Contracts;

use Board\PluginSdk\PluginToast;

/**
 * A plugin capability: contribute actions to the host's automation builder
 * ("create a GitLab issue", "notify Slack"…). The host lists the declarations
 * in a category named after the plugin, and executes them inside its pipeline
 * sandbox — a throwing action is reported and counted, never blocking the
 * other actions of the rule.
 *
 * Adding this interface is additive — implementing it is optional and does not
 * bump {@see \Board\PluginSdk\Sdk::CONTRACT_VERSION}.
 */
interface ProvidesAutomationActions
{
    /**
     * The automation actions this plugin contributes.
     *
     * Each declaration:
     * - `key`          stable identifier inside this plugin (max 60 chars)
     * - `label`        human label shown in the builder
     * - `configFields` inputs the rule author fills, same shape as
     *                  {@see Plugin::configFields()} (optional)
     *
     * @return array<int, array{key: string, label: string, configFields?: array<int, array{key: string, label: string, type: string}>}>
     */
    public function automationActions(): array;

    /**
     * Execute one declared action. The card is a normalized snapshot (never a
     * host model): id (public), title, list, board, due_at, completed_at —
     * id/list/due/completed may be null for board-scope (cardless) runs.
     *
     * Optionally return a {@see PluginToast} — the host pushes it to the
     * acting user's browser (custom duration, link buttons opening a new tab).
     *
     * @param  array<string, mixed>  $config  the installed instance config (incl. OAuth token)
     * @param  array<string, mixed>  $card  normalized card payload
     * @param  array<string, mixed>  $actionConfig  the rule's stored action config
     */
    public function runAutomationAction(array $config, string $key, array $card, array $actionConfig): ?PluginToast;
}
