<?php

namespace Board\PluginSdk\Support;

/**
 * SSRF guard for server-side outbound URLs that originate from users or plugin
 * configuration (a self-hosted instance URL, an "attach from URL" target, …).
 *
 * A URL is considered safe when it uses the http/https scheme AND its host is
 * either explicitly allow-listed or resolves only to public IP addresses. This
 * blocks loopback, link-local (cloud metadata at 169.254.169.254), private and
 * otherwise reserved ranges — the classic SSRF pivot targets.
 *
 * Self-hosted services that legitimately live on a private network can be
 * permitted per host via the allow-list (e.g. an internal GitLab).
 */
final class SafeUrl
{
    /**
     * @param  array<int, string>  $allowedHosts  hostnames that bypass the private/reserved IP check
     */
    public static function isSafe(string $url, array $allowedHosts = []): bool
    {
        return self::safeConnection($url, $allowedHosts) !== null;
    }

    /**
     * Validate a URL and resolve it to a single connection target, or null when
     * unsafe. Returning the resolved IP lets a caller PIN the socket to the exact
     * address that was checked (via curl's RESOLVE) — defeating DNS rebinding
     * between the safety check and the actual connect.
     *
     * @param  array<int, string>  $allowedHosts  hostnames that bypass the private/reserved IP check
     * @return array{host: string, ip: string, port: int}|null
     */
    public static function safeConnection(string $url, array $allowedHosts = []): ?array
    {
        $parts = parse_url($url);

        if ($parts === false || ! isset($parts['host'], $parts['scheme'])) {
            return null;
        }

        $scheme = strtolower($parts['scheme']);

        if (! in_array($scheme, ['http', 'https'], true)) {
            return null;
        }

        $host = $parts['host'];
        $port = (int) ($parts['port'] ?? ($scheme === 'https' ? 443 : 80));

        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : (gethostbynamel($host) ?: []);

        if ($ips === []) {
            return null;
        }

        $allowListed = in_array(strtolower($host), array_map('strtolower', $allowedHosts), true);

        if (! $allowListed) {
            foreach ($ips as $ip) {
                if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return null;
                }
            }
        }

        return ['host' => $host, 'ip' => $ips[0], 'port' => $port];
    }

    /**
     * Parse a comma-separated allow-list (typically an env var) into hostnames.
     *
     * @return array<int, string>
     */
    public static function parseHostList(?string $csv): array
    {
        if ($csv === null || trim($csv) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $csv))));
    }
}
