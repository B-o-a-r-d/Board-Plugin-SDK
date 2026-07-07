<?php

namespace Board\PluginSdk\Contracts;

/**
 * A plugin capability: contribute MCP tools to the host's MCP server. Returns
 * the tool class-strings (each extending Laravel MCP's Tool). The SDK stays
 * free of a laravel/mcp dependency — the plugin package requires it to author
 * the tool classes; the host merges them into its server.
 */
interface ProvidesMcpTools
{
    /**
     * @return array<int, class-string>
     */
    public function mcpTools(): array;
}
