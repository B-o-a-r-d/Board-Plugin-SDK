<?php

namespace Board\PluginSdk\Support;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

/**
 * Reads and writes a plugin's INSTANCE-level settings (declared via
 * {@see \Board\PluginSdk\Contracts\ProvidesSettings}). Values are persisted as a
 * single encrypted JSON blob in the host's shared `settings` key/value table,
 * under `plugin.<key>` — so a plugin (and the marketplace UI) can read/write them
 * without depending on the host's models. Encryption matches the at-rest posture
 * of per-board plugin config; any secret setting is therefore safe by default.
 *
 * Usage in a plugin: `PluginSettings::for(self::key())->get('allowed_hosts')`.
 */
final class PluginSettings
{
    public function __construct(private readonly string $pluginKey) {}

    public static function for(string $pluginKey): self
    {
        return new self($pluginKey);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        try {
            $raw = DB::table('settings')->where('key', $this->storageKey())->value('value');
        } catch (\Throwable) {
            // No settings table yet (fresh install / isolated context) → no values.
            return [];
        }

        if ($raw === null) {
            return [];
        }

        try {
            $cipher = json_decode((string) $raw, true);
            $decoded = json_decode(Crypt::decryptString((string) $cipher), true);
        } catch (\Throwable) {
            return [];
        }

        return is_array($decoded) ? $decoded : [];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->all()[$key] ?? null;

        return $value ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    /**
     * Replace the whole settings blob for this plugin.
     *
     * @param  array<string, mixed>  $values
     */
    public function put(array $values): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => $this->storageKey()],
            ['value' => json_encode(Crypt::encryptString(json_encode($values)))],
        );
    }

    public function forget(): void
    {
        DB::table('settings')->where('key', $this->storageKey())->delete();
    }

    private function storageKey(): string
    {
        return 'plugin.'.$this->pluginKey;
    }
}
