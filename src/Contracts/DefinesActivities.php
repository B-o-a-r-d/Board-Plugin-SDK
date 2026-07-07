<?php

namespace Board\PluginSdk\Contracts;

/**
 * A plugin capability: emit activity-log entries and own a dedicated tab in the
 * host's activity slide-over. The host shows the tab only when the board
 * actually has at least one activity of this plugin's types.
 */
interface DefinesActivities
{
    /**
     * The slide-over tab for this plugin's activities.
     *
     * @return array{key: string, label: string}
     */
    public function activityTab(): array;

    /**
     * The activity `type` values this plugin emits (e.g. 'github.commit_linked').
     *
     * @return array<int, string>
     */
    public function activityTypes(): array;

    /**
     * A localized sentence describing one of this plugin's activities (the actor
     * name is rendered separately by the host). Return null to let the host fall
     * back to its generic rendering.
     *
     * @param  array<string, mixed>  $properties
     */
    public function describeActivity(string $type, array $properties): ?string;
}
