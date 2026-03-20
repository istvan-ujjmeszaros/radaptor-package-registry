#!/usr/bin/env python3

from __future__ import annotations

import hashlib
import fnmatch
import json
from pathlib import Path
import shutil
import zipfile


ROOT = Path(__file__).resolve().parent.parent
PACKAGES_SRC_DIR = ROOT / "packages-src"
PACKAGES_DIR = ROOT / "packages"
REGISTRY_PATH = ROOT / "registry.json"
DEFAULT_DIST_EXCLUDE = {
    ".git",
    ".githooks",
    ".gitignore",
    ".php-cs-fixer.php",
    ".php-cs-fixer.cache",
    ".registry-package.json",
}


def load_package_metadata() -> list[tuple[Path, dict]]:
    packages: list[tuple[Path, dict]] = []

    for metadata_path in sorted(PACKAGES_SRC_DIR.glob("*/.registry-package.json")):
        with metadata_path.open("r", encoding="utf-8") as handle:
            metadata = json.load(handle)

        packages.append((metadata_path.parent, metadata))

    return packages


def should_include_in_archive(package_root: Path, path: Path, metadata: dict) -> bool:
    relative = path.relative_to(package_root).as_posix()
    parts = relative.split("/")

    if any(part in DEFAULT_DIST_EXCLUDE for part in parts):
        return False

    extra_patterns = metadata.get("dist_exclude", [])

    for pattern in extra_patterns:
        if fnmatch.fnmatch(relative, pattern):
            return False

    return True


def build_package_archive(package_root: Path, metadata: dict) -> tuple[str, str]:
    package_name = metadata["package"]
    version = metadata["version"]
    archive_relative_path = Path("packages") / package_name.replace("/", "-") / version / "plugin.zip"
    archive_path = ROOT / archive_relative_path
    archive_path.parent.mkdir(parents=True, exist_ok=True)

    if archive_path.exists():
        archive_path.unlink()

    with zipfile.ZipFile(archive_path, "w", compression=zipfile.ZIP_DEFLATED) as archive:
        for path in sorted(package_root.rglob("*")):
            if not path.is_file():
                continue

            if not should_include_in_archive(package_root, path, metadata):
                continue

            archive.write(path, path.relative_to(package_root))

    sha256 = hashlib.sha256(archive_path.read_bytes()).hexdigest()

    return archive_relative_path.as_posix(), sha256


def load_existing_registry() -> dict:
    if not REGISTRY_PATH.exists():
        return {
            "registry_version": 1,
            "name": "Local Radaptor Plugin Registry",
            "packages": {},
        }

    with REGISTRY_PATH.open("r", encoding="utf-8") as handle:
        try:
            data = json.load(handle)
        except json.JSONDecodeError:
            data = {}

    return {
        "registry_version": 1,
        "name": data.get("name", "Local Radaptor Plugin Registry"),
        "packages": data.get("packages", {}) if isinstance(data.get("packages"), dict) else {},
    }


def migrate_legacy_artifact_paths(registry: dict) -> None:
    for package_name, package_entry in registry.get("packages", {}).items():
        versions = package_entry.get("versions", {})

        if not isinstance(versions, dict):
            continue

        for version, version_entry in versions.items():
            dist = version_entry.get("dist", {})

            if not isinstance(dist, dict):
                continue

            current_url = dist.get("url")
            target_url = (Path("packages") / package_name.replace("/", "-") / version / "plugin.zip").as_posix()

            if not isinstance(current_url, str) or current_url == target_url:
                continue

            current_path = ROOT / current_url
            target_path = ROOT / target_url

            if not current_path.exists() and target_path.exists():
                dist["url"] = target_url
                continue

            if not current_path.exists():
                continue

            target_path.parent.mkdir(parents=True, exist_ok=True)
            shutil.move(str(current_path), str(target_path))
            dist["url"] = target_url


def build_registry() -> dict:
    PACKAGES_DIR.mkdir(parents=True, exist_ok=True)
    registry = load_existing_registry()
    migrate_legacy_artifact_paths(registry)

    for package_root, metadata in load_package_metadata():
        package_name = metadata["package"]
        plugin_id = metadata["plugin_id"]
        version = metadata["version"]
        archive_url, sha256 = build_package_archive(package_root, metadata)

        package_entry = registry["packages"].setdefault(package_name, {
            "latest": version,
            "versions": {},
        })
        package_entry["latest"] = version
        package_entry["versions"][version] = {
            "plugin_id": plugin_id,
            "dependencies": metadata.get("dependencies", {}),
            "dist": {
                "type": "zip",
                "url": archive_url,
                "sha256": sha256,
            },
        }

    registry["packages"] = dict(sorted(registry["packages"].items()))

    for package_entry in registry["packages"].values():
        versions = package_entry.get("versions", {})

        if isinstance(versions, dict):
            package_entry["versions"] = dict(sorted(versions.items()))

    return registry


def main() -> None:
    registry = build_registry()

    with REGISTRY_PATH.open("w", encoding="utf-8") as handle:
        json.dump(registry, handle, indent=2)
        handle.write("\n")

    print(f"Updated {REGISTRY_PATH}")


if __name__ == "__main__":
    main()
