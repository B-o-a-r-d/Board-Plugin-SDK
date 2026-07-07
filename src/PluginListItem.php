<?php

namespace Board\PluginSdk;

use Illuminate\Contracts\Support\Arrayable;

/**
 * A read-only "virtual card" produced by a ProvidesListSource plugin. It is
 * never persisted as a Card — the host renders it directly from the plugin's
 * live/cached result.
 *
 * @implements Arrayable<string, mixed>
 */
class PluginListItem implements Arrayable
{
    /**
     * @param  string  $externalRef  stable id from the source (sha, PR number, event id)
     * @param  string  $title  primary line (commit message, PR title…)
     * @param  string|null  $subtitle  secondary line (author + short sha, #number…)
     * @param  string|null  $url  external link opened when the item is clicked
     * @param  string|null  $badge  short status label (e.g. "open", "merged", "passed")
     * @param  string|null  $badgeColor  semantic color: green|red|amber|indigo|neutral
     * @param  string|null  $icon  phosphor icon name (without prefix)
     * @param  string|null  $timestamp  ISO-8601 timestamp for ordering/display
     */
    public function __construct(
        public string $externalRef,
        public string $title,
        public ?string $subtitle = null,
        public ?string $url = null,
        public ?string $badge = null,
        public ?string $badgeColor = null,
        public ?string $icon = null,
        public ?string $timestamp = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'external_ref' => $this->externalRef,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'url' => $this->url,
            'badge' => $this->badge,
            'badge_color' => $this->badgeColor,
            'icon' => $this->icon,
            'timestamp' => $this->timestamp,
        ];
    }
}
