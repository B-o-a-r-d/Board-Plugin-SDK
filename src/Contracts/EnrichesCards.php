<?php

namespace Board\PluginSdk\Contracts;

/**
 * A plugin capability (roadmap): attach external references to a card
 * (a pull request, an issue, a commit) and render status widgets for them.
 *
 * Declared now so external plugin authors can target a stable shape; the host
 * app wires this capability in a later phase.
 */
interface EnrichesCards
{
    /**
     * External reference types this plugin can attach to a card
     * (e.g. pull_request, issue, commit).
     *
     * @return array<int, array{key: string, label: string}>
     */
    public function cardRefTypes(): array;

    /**
     * Resolve a live widget payload for a card's attached external reference.
     *
     * @param  array<string, mixed>  $config
     * @param  array<string, mixed>  $ref  the stored reference (type + external id/url)
     * @return array<string, mixed>
     */
    public function cardWidget(array $config, array $ref): array;
}
