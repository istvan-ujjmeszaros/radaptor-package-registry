# Radaptor Package Registry

This repository is the source of truth for published Radaptor package artifacts.

It stores published artifacts and `registry.json`. It is not the runtime install directory for an
app.

Production public registry:

- `https://packages.radaptor.com/registry.json`

Current roles:

- `radaptor-app/radaptor.json`: desired package state for a consumer app
- `radaptor-app/radaptor.lock.json`: resolved package state for a consumer app
- `radaptor_plugin_registry/registry.json`: generated package catalog for this registry checkout
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

## What needs release

There are two different workflows:

- Dev mode:
  - the app uses `packages/dev/...` or `plugins/dev/...`
  - runtime changes come directly from the checkout
  - no release/publish is needed
- Registry-first validation:
  - the app installs from `registry.json` artifacts
  - an immutable first-party package release is required whenever package contents change

For non-plugin first-party packages, the source of truth lives in workspace-level package repos:

- `/apps/_RADAPTOR/packages-dev/core/framework`
- `/apps/_RADAPTOR/packages-dev/core/cms`
- `/apps/_RADAPTOR/packages-dev/themes/portal-admin`
- `/apps/_RADAPTOR/packages-dev/themes/so-admin`

## Maintainer release flow

The supported maintainer workflow is Docker-only and runs through the workspace package-dev
`radaptor-app` runtime. Do not run host PHP, host Composer, or host Radaptor CLI for release work.

1. Ensure the desired package state is merged to `main` in the package repo under
   `/apps/_RADAPTOR/packages-dev/...`.
2. Start the package-dev app stack if needed:

   ```bash
   cd /apps/_RADAPTOR
   ./bin/docker-compose-packages-dev.sh radaptor-app up -d --build
   ```

3. Release the changed first-party package into this registry:

   ```bash
   cd /apps/_RADAPTOR
   ./bin/docker-compose-packages-dev.sh radaptor-app exec -T php bash -lc \
     'cd /app && php radaptor.php package:release core:framework --json'
   ```

   Or create a prerelease:

   ```bash
   cd /apps/_RADAPTOR
   ./bin/docker-compose-packages-dev.sh radaptor-app exec -T php bash -lc \
     'cd /app && php radaptor.php package:prerelease core:framework --channel alpha --json'
   ```

4. Commit the bumped `.registry-package.json` in the package repo.
5. Commit + push this `radaptor_plugin_registry` repo.
6. GitHub Actions auto-deploys `main` to `https://packages.radaptor.com/`.
7. Only after the deploy finishes, refresh the consumer app in registry-first mode with
   `./radaptor.sh update --ignore-local-overrides --json`.
8. If the consumer has local package overrides, refresh `radaptor.local.lock.json` afterwards with
   `./radaptor.sh local-lock:refresh --json`.
9. Run `build:all` and a browser/admin smoke test before declaring the release state healthy.

Package keys:

- `core:framework`
- `core:cms`
- `theme:portal-admin`
- `theme:so-admin`

Important:

- Package version entries in this registry are immutable.
- `package:release` / `package:prerelease` create the next version and update the local registry checkout only; they do not create Git commits or push them for you.
- The low-level `package:publish` / `package:publish-all` commands remain available for bootstrap/internal cases, but they abort if the target version already exists.
- The VPS registry deploy is triggered by pushes to `main` in this repository.
- After release and deploy, the consumer app lockfile/runtime must be refreshed so its pinned
  `dist_sha256` and installed package versions match the newly published archives.
- The skeleton bootstrap uses the committed lockfile metadata and only rewrites the placeholder
  registry authority to the configured runtime registry URL. It does not re-resolve live `dist`
  metadata on first install.

## PR and review gate

Package artifacts should be released only after the package PR has been reviewed and merged:

1. Open one PR in the package repo.
2. Comment exactly `@codex review`.
3. Address actionable review threads using thread-aware review data; outdated comments and
   unresolved threads are not the same thing.
4. Wait for repo checks and the latest relevant Codex review to be clean.
5. Squash-merge the package PR and fast-forward local package `main`.
6. Release from that clean `main`.

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

## License

This repository is distributed under the proprietary evaluation license in
[LICENSE](./LICENSE).
Evaluation-only: no production/commercial/distribution/derivative use without
a separate license agreement.

## Contact

istvan@radaptor.com
