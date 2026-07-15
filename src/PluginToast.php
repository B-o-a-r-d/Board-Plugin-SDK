<?php

namespace Board\PluginSdk;

/**
 * A toast the host shows to the acting user after a plugin automation action
 * ran ({@see Contracts\ProvidesAutomationActions::runAutomationAction()}).
 *
 * The host owns rendering and transport (websocket push to the actor's
 * browser); the plugin only describes the content. Action URLs open in a new
 * tab — the host drops any action whose URL is not http(s).
 */
final class PluginToast
{
    /**
     * @param  string  $message  short headline ("GitHub issue created")
     * @param  string  $description  optional secondary line ("owner/repo#42")
     * @param  string  $type  success | info | warning | danger | default
     * @param  int|null  $duration  display time in ms, null = host default
     * @param  array<int, array{label: string, url: string}>  $actions  link buttons, opened in a new tab
     */
    public function __construct(
        public readonly string $message,
        public readonly string $description = '',
        public readonly string $type = 'success',
        public readonly ?int $duration = null,
        public readonly array $actions = [],
    ) {}

    /**
     * @return array{message: string, description: string, type: string, duration: int|null, actions: array<int, array{label: string, url: string}>}
     */
    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'description' => $this->description,
            'type' => $this->type,
            'duration' => $this->duration,
            'actions' => $this->actions,
        ];
    }
}
