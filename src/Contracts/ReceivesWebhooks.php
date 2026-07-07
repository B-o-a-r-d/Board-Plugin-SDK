<?php

namespace Board\PluginSdk\Contracts;

/**
 * A plugin capability (roadmap): receive inbound webhooks on a per-instance
 * signed URL and react (refresh a list, post to a card, broadcast realtime).
 *
 * Declared now to fix the contract; the host app wires the public webhook
 * endpoint and signature verification in a later phase.
 */
interface ReceivesWebhooks
{
    /**
     * Verify an inbound request signature against the instance config/secret.
     *
     * @param  array<string, mixed>  $config
     * @param  array<string, string>  $headers
     */
    public function verifyWebhook(array $config, array $headers, string $rawBody): bool;

    /**
     * Handle a verified webhook payload. Return a normalized result the host
     * can act on (e.g. which list to refresh).
     *
     * @param  array<string, mixed>  $config
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function handleWebhook(array $config, array $payload): array;
}
