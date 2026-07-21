<?php

namespace Board\PluginSdk\Contracts;

/**
 * A plugin capability: contribute a BOARD TYPE to the host — a board whose
 * whole surface is a page owned by the plugin instead of the kanban lists
 * grid (e.g. a document shelf, a wiki…). Workspaces keep grouping boards;
 * typed boards live side by side with kanban ones and inherit the host's
 * per-board membership, pinning and cross-workspace moves.
 *
 * While the plugin is installed and enabled, the host offers "create a
 * <label> board" and routes such boards to {@see boardTypeRoute}. If the
 * plugin goes away, existing boards of the type remain listed but unopenable
 * (flagged "Power-Up requis") until it is reinstalled — the host never
 * deletes their data.
 *
 * This capability is ADDITIVE to the SDK contract: hosts that predate it
 * simply never call it.
 */
interface ProvidesBoardType
{
    /**
     * Unique board type key (lowercase slug, e.g. 'shelf').
     * 'kanban' is reserved by the host.
     */
    public function boardTypeKey(): string;

    /** Display label, e.g. 'Shelf'. */
    public function boardTypeLabel(): string;

    /** A Phosphor icon name, e.g. 'books'. */
    public function boardTypeIcon(): string;

    /**
     * The NAME of the route opening a board of this type. The route must
     * accept the board as its single parameter — the plugin registers it
     * from its service provider (e.g. 'shelf.show').
     */
    public function boardTypeRoute(): string;
}
