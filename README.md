# Local Radaptor Plugin Registry

This directory is the local development registry for Radaptor plugins.

It is a catalog of available plugin packages, not the runtime plugin directory.
Installed plugins still live under the application repo's `plugins/` folder.

This directory is intended to live as its own Git repository. The outer workspace
repository should ignore it.

It should normally be served over HTTP in development so the main application can
exercise registry-based install/update flows without special filesystem coupling.

Current intended roles:

- `radaptor/plugins.json`: desired plugin state for an app
- `radaptor/plugins.lock.json`: resolved/installed plugin state for an app
- `radaptor_plugin_registry/registry.json`: generated package catalog for the local registry
- `radaptor_plugin_registry/docker-compose.yml`: simple local HTTP service for the registry
- `radaptor_plugin_registry/scripts/build_registry.py`: rebuild package zips + refresh `registry.json`
- `radaptor_plugin_registry/scripts/publish_plugin.py`: copy a standalone plugin repo into `packages-src/` and rebuild the registry

Initial design goals:

- test install/uninstall/update flows without needing a remote marketplace
- support both dev checkout plugins and registry-managed plugins
- keep runtime independent from registry scanning by generating `generated/__plugins__.php`

## Local usage

Start the registry service:

```bash
docker compose up -d
```

The catalog is then available at:

```text
http://localhost:8091/registry.json
```

From the `radaptor` PHP container, the same registry is intended to be reachable as:

```text
http://host.docker.internal:8091/registry.json
```

## Sample package

The registry currently includes a small teaching plugin package:

- `radaptor/hello-world` version `1.1.2`
- `radaptor/tracker` version `0.1.0`

Its artifact is served from:

```text
http://localhost:8091/packages/radaptor-hello-world-1.0.0.zip
```

The unpacked source used to build that artifact lives under:

```text
packages-src/hello-world/
```

Before starting the registry service, rebuild the package artifacts and `registry.json`:

```bash
python3 scripts/build_registry.py
```

The generated zip files live under `packages/`, which is intentionally git-ignored.
Only the source under `packages-src/` and the generated text catalog `registry.json`
are versioned.

## Publish a dev plugin repo

Standalone plugins can be developed as their own Git repositories and then
published into the local registry source mirror.

Example:

```bash
python3 scripts/publish_plugin.py /apps/_RADAPTOR/radaptor/plugins/dev/tracker
```

The same workflow is also available from the application repo:

```bash
docker compose -f /apps/_RADAPTOR/radaptor/docker-compose-dev.yml exec -T php php radaptor.php plugin:publish tracker --json
```

The source plugin repository must contain a `.registry-package.json` file with
at least:

```json
{
  "package": "radaptor/tracker",
  "plugin_id": "tracker",
  "version": "0.1.0"
}
```

What the publish script does:

- copies the tracked plugin source into `packages-src/<plugin_id>/`
- rebuilds the zip artifacts under `packages/`
- refreshes `registry.json`

The published distribution zip intentionally excludes dev-only Git/fixer files
such as `.git`, `.githooks`, `.gitignore`, `.php-cs-fixer.php`, and
`.php-cs-fixer.cache`.
