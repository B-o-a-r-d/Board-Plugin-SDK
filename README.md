# Board Plugin SDK

Contracts and value objects for building [Board](https://board.test) Kanban plugins (Power-Ups).

Plugins are ordinary Composer packages that depend on **this SDK only** — never on
the host application — which is what lets them ship and version independently
(`composer require board/plugin-github`).

## Building a plugin

1. Depend on the SDK:

   ```
   composer require board/plugin-sdk
   ```

2. Implement `Board\PluginSdk\Contracts\Plugin` plus any capability interfaces
   you support:
   - `ProvidesListSource` — feed a list with read-only virtual cards.
   - `EnrichesCards` *(roadmap)* — attach external refs to cards.
   - `ReceivesWebhooks` *(roadmap)* — react to inbound webhooks.

3. Register your plugin from a service provider and expose it via Laravel
   package auto-discovery:

   ```php
   public function boot(\Board\PluginSdk\PluginRegistry $registry): void
   {
       $registry->register(new MyPlugin());
   }
   ```

   ```json
   "extra": { "laravel": { "providers": ["My\\Plugin\\MyServiceProvider"] } }
   ```

The host app owns storage (installed instances), the configuration UI, OAuth,
list rendering, caching and realtime — plugins stay thin.

## Versioning

Semantic versioning per package. Breaking changes to these contracts bump the
SDK major version; plugins pin a range (`"board/plugin-sdk": "^1.0"`).
