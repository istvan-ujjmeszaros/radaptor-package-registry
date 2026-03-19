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
- `radaptor_plugin_registry/registry.json`: available packages in the local registry
- `radaptor_plugin_registry/docker-compose.yml`: simple local HTTP service for the registry

Initial design goals:

- test install/uninstall/update flows without needing a remote marketplace
- support both in-repo workspace plugins and registry-managed plugins
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
