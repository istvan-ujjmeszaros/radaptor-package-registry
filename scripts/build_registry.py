#!/usr/bin/env python3

from __future__ import annotations

import hashlib
import json
from pathlib import Path
import zipfile


ROOT = Path(__file__).resolve().parent.parent
PACKAGES_SRC_DIR = ROOT / "packages-src"
PACKAGES_DIR = ROOT / "packages"
REGISTRY_PATH = ROOT / "registry.json"


def load_package_metadata() -> list[tuple[Path, dict]]:
    packages: list[tuple[Path, dict]] = []

    for metadata_path in sorted(PACKAGES_SRC_DIR.glob("*/.registry-package.json")):
        with metadata_path.open("r", encoding="utf-8") as handle:
            metadata = json.load(handle)

        packages.append((metadata_path.parent, metadata))

    return packages


def build_package_archive(package_root: Path, package_name: str, version: str) -> tuple[str, str]:
    archive_name = f"{package_name.replace('/', '-')}-{version}.zip"
    archive_path = PACKAGES_DIR / archive_name

    with zipfile.ZipFile(archive_path, "w", compression=zipfile.ZIP_DEFLATED) as archive:
        for path in sorted(package_root.rglob("*")):
            if not path.is_file():
                continue

            if path.name == ".registry-package.json":
                continue

            archive.write(path, path.relative_to(package_root))

    sha256 = hashlib.sha256(archive_path.read_bytes()).hexdigest()

    return archive_name, sha256


def build_registry() -> dict:
    PACKAGES_DIR.mkdir(parents=True, exist_ok=True)

    registry: dict = {
        "registry_version": 1,
        "name": "Local Radaptor Plugin Registry",
        "packages": {},
    }

    for package_root, metadata in load_package_metadata():
        package_name = metadata["package"]
        plugin_id = metadata["plugin_id"]
        version = metadata["version"]
        archive_name, sha256 = build_package_archive(package_root, package_name, version)

        package_entry = registry["packages"].setdefault(package_name, {
            "latest": version,
            "versions": {},
        })
        package_entry["latest"] = version
        package_entry["versions"][version] = {
            "plugin_id": plugin_id,
            "dist": {
                "type": "zip",
                "url": f"packages/{archive_name}",
                "sha256": sha256,
            },
        }

    return registry


def main() -> None:
    registry = build_registry()

    with REGISTRY_PATH.open("w", encoding="utf-8") as handle:
        json.dump(registry, handle, indent=2)
        handle.write("\n")

    print(f"Updated {REGISTRY_PATH}")


if __name__ == "__main__":
    main()
