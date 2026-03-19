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

## Sample package

The registry currently includes a minimal sample package:

- `radaptor/hello-registry` version `1.0.0`

Its artifact is served from:

```text
http://localhost:8091/packages/radaptor-hello-registry-1.0.0.zip
```

The unpacked source used to build that artifact lives under:

```text
packages-src/hello-registry/
```

Before starting the registry service, rebuild the package artifacts and `registry.json`:

```bash
python3 scripts/build_registry.py
```

The generated zip files live under `packages/`, which is intentionally git-ignored.
Only the source under `packages-src/` and the generated text catalog `registry.json`
are versioned.
