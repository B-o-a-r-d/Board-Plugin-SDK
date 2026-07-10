<?php

namespace Board\PluginSdk;

/**
 * SDK contract versioning — the safety net that keeps an incompatible plugin from
 * crashing the host at boot.
 *
 * PHP raises an UNCATCHABLE fatal ("Declaration must be compatible") the moment a
 * plugin class whose method signatures no longer match a `Contracts/*` interface
 * is loaded. A try/catch around the loader cannot stop it. The only safe defense
 * is to NEVER load such a class — so the host gates plugins on an explicit integer
 * contract version instead of relying on SemVer (a signature change can slip into
 * a patch release and satisfy `^0.2`, exactly what took prod down once).
 *
 * ## Policy for SDK maintainers
 * Bump {@see self::CONTRACT_VERSION} on ANY breaking change to a `Contracts/*`
 * interface (new/removed/renamed method, changed parameter or return type). A
 * purely additive change (a NEW opt-in capability interface, a new support class)
 * is NOT breaking and must NOT bump it. List every version the host can still run
 * in {@see self::SUPPORTED_CONTRACTS} so old plugins keep working across a
 * non-breaking window; drop a version from the list only when support is removed.
 *
 * ## Policy for plugin authors
 * Declare the contract you build against in `composer.json`:
 *
 *     "extra": { "board": { "sdk_contract": 1 } }
 *
 * The marketplace records it and the loader runs your plugin only when the host
 * supports that contract; otherwise the plugin is quarantined (never loaded) and
 * flagged "update required" in the marketplace — no crash.
 */
final class Sdk
{
    /** Current plugin contract version. Bump on every breaking Contracts/* change. */
    public const CONTRACT_VERSION = 1;

    /**
     * Contract versions this SDK can host. A plugin built for one of these loads;
     * anything else is quarantined by the loader.
     *
     * @var array<int, int>
     */
    public const SUPPORTED_CONTRACTS = [1];

    public static function supportsContract(?int $pluginContract): bool
    {
        return $pluginContract !== null && in_array($pluginContract, self::SUPPORTED_CONTRACTS, true);
    }

    /**
     * The contract version a plugin declares in its composer.json
     * (`extra.board.sdk_contract`), or null when it declares none.
     *
     * @param  array<string, mixed>  $composerManifest
     */
    public static function pluginContract(array $composerManifest): ?int
    {
        $value = $composerManifest['extra']['board']['sdk_contract'] ?? null;

        return is_int($value) ? $value : (is_numeric($value) ? (int) $value : null);
    }
}
