<h1 align="center">Board Plugin SDK</h1>

<p align="center">
  Contracts and value objects for building <a href="https://github.com/B-o-a-r-d/board">Board</a>
  Kanban plugins (Power-Ups).
</p>

<p align="center">
  <img alt="PHP 8.3" src="https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white">
  <img alt="License MIT" src="https://img.shields.io/badge/License-MIT-22c55e">
</p>

---

A Board plugin is an **ordinary Composer package** that depends on **this SDK only**
— never on the host application. That's what lets plugins ship and version
independently: `composer require board/plugin-github`, and Laravel package
auto-discovery makes the Power-Up available with no core changes.

The host owns storage, the configuration UI, OAuth, list rendering, caching,
realtime and the MCP server. Plugins stay thin: they describe themselves and
implement the capabilities they support.

## Install

```bash
composer require board/plugin-sdk
```

## Capabilities

Every plugin implements `Plugin`. Capabilities are **opt-in** interfaces — the host
inspects `instanceof` to know what a plugin can do.

| Interface | What the plugin can do |
|-----------|------------------------|
| `Contracts\Plugin` | Identity, icon, config fields, OAuth requirement (required) |
| `Contracts\ProvidesListSource` | Feed a list with read-only "virtual cards" (commits, PRs, pipelines…) |
| `Contracts\EnrichesCards` | Attach external refs to a card and resolve them into a status widget |
| `Contracts\DefinesActivities` | Log activities and get a dedicated tab in the activity slide-over |
| `Contracts\ProvidesMcpTools` | Contribute tools to the host's MCP server |
| `Contracts\ProvidesOAuth` | Declare a provider's OAuth endpoints; the host drives the flow |
| `Contracts\ProvidesCardFields` | Inject custom fields into cards (type, options, sidebar/content placement) |
| `Contracts\ProvidesAutomationActions` | Contribute actions to the automation builder, run in the host's pipeline sandbox |
| `Contracts\PluginContext` | *(host-bound)* let decoupled plugin code read board state safely |

Value objects: `PluginListItem` — a read-only virtual card (`title`, `subtitle`,
`url`, `badge`, `badgeColor`, `icon`, `timestamp`) — and `PluginToast` — a toast
an automation action returns for the host to push to the acting user (`message`,
`description`, `type`, `duration`, link `actions` opened in a new tab).

## Building a plugin

1. **Implement `Plugin`** (plus any capabilities):

```php
use Board\PluginSdk\Contracts\Plugin;
use Board\PluginSdk\Contracts\ProvidesListSource;
use Board\PluginSdk\PluginListItem;
use Illuminate\Support\Collection;

class HelloPlugin implements Plugin, ProvidesListSource
{
    public static function key(): string { return 'hello'; }
    public function label(): string { return 'Hello'; }
    public function description(): string { return __('hello::messages.description'); }
    public function icon(): string { return 'hand-waving'; }      // phosphor icon name
    public function requiresOAuth(): bool { return false; }
    public function oauthProvider(): ?string { return null; }
    public function configFields(array $config = []): array { return []; }

    public function sourceModes(): array
    {
        return [['key' => 'greetings', 'label' => __('hello::messages.greetings')]];
    }

    public function listConfigFields(array $config = []): array { return []; }

    public function items(array $config, string $mode, array $sourceConfig): Collection
    {
        return collect(['Bonjour', 'Hello', 'Hola'])->map(fn ($g, $i) => new PluginListItem(
            externalRef: (string) $i,
            title: $g,
            icon: 'hand-waving',
        ));
    }
}
```

2. **Register it from a service provider** by extending the SDK base — it wires the
   registry *and* loads your package's translations under the `<key>::` namespace:

```php
use Board\PluginSdk\Contracts\Plugin;
use Board\PluginSdk\PluginServiceProvider;

class HelloServiceProvider extends PluginServiceProvider
{
    protected function plugin(): Plugin { return new HelloPlugin; }
}
```

3. **Expose the provider** for auto-discovery in your `composer.json`:

```json
{
    "require": { "board/plugin-sdk": "^0.2" },
    "extra": { "laravel": { "providers": ["Vendor\\Hello\\HelloServiceProvider"] } },
    "autoload": { "psr-4": { "Vendor\\Hello\\": "src/" } }
}
```

That's it — `composer require vendor/hello` and the Power-Up shows up in **Board →
Power-Ups**.

## Translations

Plugins ship their own strings as files (never in the host core). The base
`PluginServiceProvider` loads `lang/` under the plugin key, so use
`__('hello::messages.some.key')`. Provide `lang/{en,fr,es}/messages.php`.

## MCP tools

Implement `ProvidesMcpTools::mcpTools()` returning your tool class-strings (each
extending Laravel MCP's `Tool`). Require `laravel/mcp` in your package. Tools that
need host state (a board's stored config) resolve it through the host-bound
`Contracts\PluginContext` — so your tool never depends on the app.

## Versioning

Semantic versioning per package. Breaking changes to these contracts bump the SDK
**major**; plugins pin a range (`"board/plugin-sdk": "^0.2"`).

## License

MIT. See the [Board](https://github.com/B-o-a-r-d/board) app and the
[GitHub plugin](https://github.com/B-o-a-r-d/Github-Plugin) for full examples.
