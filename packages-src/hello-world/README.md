# Hello World Plugin

This package is a tiny teaching plugin for the local Radaptor registry.

It intentionally demonstrates three progressively better patterns:

- a raw event that writes directly to the output
- a template-backed event that renders through a reusable `Template`
- a simple widget rendered through the CMS/component pipeline

The code is heavily commented on purpose so it can double as onboarding
material when the plugin system is reviewed later.

## Try It

1. Install or sync the plugin with `php radaptor.php plugin:sync`.
2. Import the plugin's translation seeds with `php radaptor.php plugin:seed-i18n hello-world`.
3. Hit the raw event or the template-backed event directly, or place the widget onto a page under `/learn/`.

The widget name and description use the standard `widget.<id>.*` translation
keys, while the demo content itself is shipped through plugin-local i18n seed
CSV files under `i18n/seeds/`.
