<?php

namespace Board\PluginSdk\Contracts;

/**
 * A bridge the host binds so decoupled plugin code (e.g. MCP tools that run
 * outside a board request) can read host state without depending on the host
 * application. Plugins type-hint `PluginContext`; the host provides the impl.
 */
interface PluginContext
{
    /**
     * The decrypted config of an installed instance of $pluginKey on the given
     * board, or null when the board/instance is missing or the current user has
     * no access.
     *
     * @return array<string, mixed>|null
     */
    public function boardPluginConfig(string $boardPublicId, string $pluginKey): ?array;

    /**
     * Whether the acting user may access the given board.
     */
    public function userCanAccessBoard(string $boardPublicId): bool;
}
