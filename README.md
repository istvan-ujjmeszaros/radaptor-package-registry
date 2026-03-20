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
- `radaptor_plugin_registry/scripts/publish_plugin.py`: publish a standalone plugin repo directly into the versioned artifact store and refresh `registry.json`
- `/apps/_RADAPTOR/plugin-origins/*.git`: local bare origins for standalone plugin repositories
- `radaptor/plugins/dev/<plugin-id>/`: local development checkouts that point at those bare origins

Initial design goals:

- test install/uninstall/update flows without needing a remote marketplace
- support both dev checkout plugins and registry-managed plugins
- keep runtime independent from registry scanning by generating `generated/__plugins__.php`
- keep plugin source history in the plugin repositories, not in the registry repository

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

## Published packages

The registry currently includes a small teaching plugin package:

- `radaptor/hello-world` version `1.1.2`
- `radaptor/tracker` version `0.1.0`

Example artifact URL:

```text
http://localhost:8091/packages/radaptor-hello-world/1.1.2/plugin.zip
```

The generated artifact files live under versioned paths in `packages/`, for example
`packages/radaptor-tracker/0.1.0/plugin.zip`. `registry.json` is the source that declares which
version is `latest`; there is no separate `latest/` artifact directory.

## Publish a dev plugin repo

Standalone plugins can be developed as their own Git repositories and then
published into the local registry artifact store.

Recommended local structure:

```text
/apps/_RADAPTOR/
├── plugin-origins/
│   ├── blog.git
│   ├── hello-world.git
│   └── tracker.git
├── radaptor/
│   └── plugins/
│       └── dev/
│           ├── blog/
│           ├── hello-world/
│           └── tracker/
└── radaptor_plugin_registry/
    ├── registry.json
    └── packages/
```

The registry repository stores published artifacts and metadata only. The plugin
source of truth lives in the standalone plugin repositories and their dev
checkouts.

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

- reads the tracked files from the plugin Git repository
- builds a versioned distribution zip under `packages/<package>/<version>/plugin.zip`
- updates `registry.json`

The published distribution zip intentionally excludes dev-only Git/fixer files
such as `.git`, `.githooks`, `.gitignore`, `.php-cs-fixer.php`, and
`.php-cs-fixer.cache`.
