<?php

namespace Board\PluginSdk\Contracts;

/**
 * A plugin capability: contribute a whole WORKSPACE TYPE to the host — a
 * workspace whose main surface is a page owned by the plugin instead of the
 * kanban boards grid (e.g. a document shelf, a wiki…).
 *
 * While the plugin is installed and enabled, the host offers "create a
 * <label> workspace" and routes such workspaces to {@see workspaceTypeRoute}.
 * If the plugin goes away, existing workspaces of the type remain listed but
 * unopenable (flagged "Power-Up requis") until it is reinstalled — the host
 * never deletes their data.
 *
 * This capability is ADDITIVE to the SDK contract: hosts that predate it
 * simply never call it.
 */
interface ProvidesWorkspaceType
{
    /**
     * Unique workspace type key (lowercase slug, e.g. 'shelf').
     * 'kanban' is reserved by the host.
     */
    public function workspaceTypeKey(): string;

    /** Display label, e.g. 'Shelf'. */
    public function workspaceTypeLabel(): string;

    /** A Phosphor icon name, e.g. 'books'. */
    public function workspaceTypeIcon(): string;

    /**
     * The NAME of the route opening a workspace of this type. The route must
     * accept the workspace as its single parameter — the plugin registers it
     * from its service provider (e.g. 'shelf.show').
     */
    public function workspaceTypeRoute(): string;
}
