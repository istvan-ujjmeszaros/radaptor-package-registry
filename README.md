# Radaptor Package Registry

This repository is the source of truth for published Radaptor package artifacts.

It stores published artifacts and `registry.json`. It is not the runtime install directory for an
app.

Production public registry:

- `https://packages.radaptor.com/registry.json`

Current roles:

- `radaptor-app/radaptor.json`: desired package state for a consumer app
- `radaptor-app/radaptor.lock.json`: resolved package state for a consumer app
- `radaptor_plugin_registry/registry.json`: generated package catalog for the local registry
- `radaptor_plugin_registry/packages/...`: versioned package artifacts
- `radaptor_plugin_registry/docker-compose.yml`: simple local HTTP service for local registry testing

## Start the registry

```bash
docker compose up -d
```

The catalog is then available at:

```text
http://localhost:8091/registry.json
```

From the `radaptor-app` `php` container, use:

```text
http://host.docker.internal:8091/registry.json
```

## What needs republish

There are two different workflows:

- Dev mode:
  - the app uses `packages/dev/...` or `plugins/dev/...`
  - runtime changes come directly from the checkout
  - no republish is needed
- Registry-first validation:
  - the app installs from `registry.json` artifacts
  - republish is required whenever first-party package contents change

For non-plugin first-party packages, the source of truth lives in the active consumer app:

- `radaptor-app/packages/dev/core/framework`
- `radaptor-app/packages/dev/core/cms`
- `radaptor-app/packages/dev/themes/portal-admin`
- `radaptor-app/packages/dev/themes/so-admin`

## Maintainer republish flow

The supported maintainer workflow is Docker-only and runs through `radaptor-app`.

1. Ensure the desired package state is present in `radaptor-app/packages/dev/...`.
2. Start the app stack if needed:

   ```bash
   cd /apps/_RADAPTOR/radaptor-app
   docker compose -f docker-compose-dev.yml up -d --build
   ```

3. Republish first-party packages into this registry:

   ```bash
   cd /apps/_RADAPTOR/radaptor-app
   ./radaptor.sh package:publish-all --json
   ```

   Or publish one package:

   ```bash
   cd /apps/_RADAPTOR/radaptor-app
   ./radaptor.sh package:publish core:framework --json
   ```

4. Commit + push this `radaptor_plugin_registry` repo.
5. GitHub Actions auto-deploys `main` to `https://packages.radaptor.com/`.
6. Refresh `radaptor-app/radaptor.lock.json` against the republished artifacts.
7. Run a clean registry-first scratch proof before declaring the skeleton release state healthy.

Important:

- The local development registry uses mutable dev artifacts.
- `publish` updates the local registry checkout only; it does not create Git commits or push them for you.
- The VPS registry deploy is triggered by pushes to `main` in this repository.
- After republish, the committed skeleton lockfile must be refreshed so its pinned `dist_sha256`
  matches the newly published archives.
- The skeleton bootstrap uses the committed lockfile metadata and only rewrites the placeholder
  registry authority to the configured runtime registry URL. It does not re-resolve live `dist`
  metadata on first install.

## GitHub Actions deploy secrets

The auto-deploy workflow expects these repository secrets:

- `PACKAGES_REGISTRY_DEPLOY_HOST`
- `PACKAGES_REGISTRY_DEPLOY_USER`
- `PACKAGES_REGISTRY_DEPLOY_SSH_KEY`
- `PACKAGES_REGISTRY_DEPLOY_KNOWN_HOSTS`
- optional: `PACKAGES_REGISTRY_DEPLOY_PORT`

The remote command is:

```text
/var/www_config/packages.radaptor.com/update-repo.sh
```

## Plugin publish flow

Standalone plugins are still published separately.

Example direct publish:

```bash
python3 scripts/publish_plugin.py /apps/_RADAPTOR/radaptor-app/plugins/dev/tracker
```

Or from the app:

```bash
cd /apps/_RADAPTOR/radaptor-app
./radaptor.sh plugin:publish tracker --json
```

The source plugin repository must contain a `.registry-package.json` file with at least:

```json
{
  "package": "radaptor/plugins/tracker",
  "type": "plugin",
  "id": "tracker",
  "version": "0.1.0"
}
```

## Artifact policy

Published package zips are built from tracked repository content plus `.registry-package.json`.

Examples:

- `packages/radaptor-core-framework/0.1.0/plugin.zip`
- `packages/radaptor-core-cms/0.1.0/plugin.zip`
- `packages/radaptor-themes-portal-admin/0.1.0/plugin.zip`
- `packages/radaptor-plugins-tracker/0.1.0/plugin.zip`

`registry.json` declares which version is `latest`; there is no separate `latest/` artifact path.
