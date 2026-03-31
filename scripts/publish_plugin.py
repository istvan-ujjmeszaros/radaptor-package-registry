#!/usr/bin/env python3

from __future__ import annotations

import argparse
import fnmatch
import hashlib
import json
from pathlib import Path
import subprocess
import sys
import zipfile


ROOT = Path(__file__).resolve().parent.parent
PACKAGES_DIR = ROOT / "packages"
REGISTRY_PATH = ROOT / "registry.json"
DEFAULT_DIST_EXCLUDE = {
    ".git",
    ".githooks",
    ".gitignore",
    ".php-cs-fixer.php",
    ".php-cs-fixer.cache",
}


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Publish a standalone package repository into the local Radaptor package registry."
    )
    parser.add_argument("source", help="Path to the standalone plugin repository")
    return parser.parse_args()


def load_metadata(source_root: Path) -> dict:
    metadata_path = source_root / ".registry-package.json"

    if not metadata_path.exists():
        raise RuntimeError(f"Missing .registry-package.json in {source_root}")

    with metadata_path.open("r", encoding="utf-8") as handle:
        metadata = json.load(handle)

    for required_key in ("package", "type", "id", "version"):
        if required_key not in metadata or not isinstance(metadata[required_key], str) or metadata[required_key] == "":
            raise RuntimeError(f"Invalid or missing '{required_key}' in {metadata_path}")

    dependencies = metadata.get("dependencies", {})
    if not isinstance(dependencies, dict):
        raise RuntimeError(f"Invalid dependencies in {metadata_path}")

    metadata["dependencies"] = {
        str(package).strip(): str(constraint).strip()
        for package, constraint in dependencies.items()
        if str(package).strip() and str(constraint).strip()
    }

    dist_exclude = metadata.get("dist_exclude", [])
    if not isinstance(dist_exclude, list):
        raise RuntimeError(f"Invalid dist_exclude in {metadata_path}")

    metadata["dist_exclude"] = [str(pattern).strip() for pattern in dist_exclude if str(pattern).strip()]

    return metadata


def list_tracked_files(source_root: Path) -> list[Path]:
    git_dir = source_root / ".git"

    if not git_dir.exists():
        raise RuntimeError(f"Source directory is not a Git repository: {source_root}")

    result = subprocess.run(
        ["git", "-C", str(source_root), "ls-files", "-z"],
        check=True,
        capture_output=True,
    )
    raw_entries = [entry for entry in result.stdout.decode("utf-8").split("\0") if entry]
    files = {Path(entry) for entry in raw_entries}
    metadata_path = source_root / ".registry-package.json"

    if metadata_path.exists():
        files.add(Path(".registry-package.json"))

    return sorted(files)


def should_include_in_archive(relative_path: str, metadata: dict) -> bool:
    parts = relative_path.split("/")

    if any(part in DEFAULT_DIST_EXCLUDE for part in parts):
        return False

    for pattern in metadata["dist_exclude"]:
        if fnmatch.fnmatch(relative_path, pattern):
            return False

    return True


def build_package_archive(source_root: Path, metadata: dict, tracked_files: list[Path]) -> tuple[str, str, int]:
    package_name = metadata["package"]
    version = metadata["version"]
    archive_relative_path = Path("packages") / package_name.replace("/", "-") / version / "plugin.zip"
    archive_path = ROOT / archive_relative_path
    archive_path.parent.mkdir(parents=True, exist_ok=True)

    if archive_path.exists():
        archive_path.unlink()

    packaged_count = 0

    with zipfile.ZipFile(archive_path, "w", compression=zipfile.ZIP_DEFLATED) as archive:
        for relative_path in tracked_files:
            relative = relative_path.as_posix().lstrip("/")

            if not should_include_in_archive(relative, metadata):
                continue

            source_path = source_root / relative_path

            if not source_path.is_file():
                continue

            archive.write(source_path, relative_path)
            packaged_count += 1

    sha256 = hashlib.sha256(archive_path.read_bytes()).hexdigest()

    return archive_relative_path.as_posix(), sha256, packaged_count


def load_existing_registry() -> dict:
    if not REGISTRY_PATH.exists():
        return {
            "registry_version": 1,
            "name": "Local Radaptor Package Registry",
            "packages": {},
        }

    with REGISTRY_PATH.open("r", encoding="utf-8") as handle:
        try:
            data = json.load(handle)
        except json.JSONDecodeError:
            data = {}

    return {
        "registry_version": 1,
        "name": data.get("name", "Local Radaptor Package Registry"),
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
            current_path.replace(target_path)
            dist["url"] = target_url


def write_registry(registry: dict) -> None:
    registry["packages"] = dict(sorted(registry["packages"].items()))

    for package_entry in registry["packages"].values():
        versions = package_entry.get("versions", {})

        if isinstance(versions, dict):
            package_entry["versions"] = dict(sorted(versions.items()))

    with REGISTRY_PATH.open("w", encoding="utf-8") as handle:
        json.dump(registry, handle, indent=2)
        handle.write("\n")


def main() -> None:
    args = parse_args()
    source_root = Path(args.source).expanduser().resolve()

    if not source_root.is_dir():
        raise RuntimeError(f"Source directory does not exist: {source_root}")

    metadata = load_metadata(source_root)
    tracked_files = list_tracked_files(source_root)

    if not tracked_files:
        raise RuntimeError(f"Source repository does not contain tracked files: {source_root}")

    PACKAGES_DIR.mkdir(parents=True, exist_ok=True)
    registry = load_existing_registry()
    migrate_legacy_artifact_paths(registry)
    archive_url, sha256, packaged_count = build_package_archive(source_root, metadata, tracked_files)

    package_entry = registry["packages"].setdefault(
        metadata["package"],
        {
            "latest": metadata["version"],
            "versions": {},
        },
    )
    package_entry["latest"] = metadata["version"]
    package_entry["versions"][metadata["version"]] = {
        "type": metadata["type"],
        "id": metadata["id"],
        "dependencies": metadata["dependencies"],
        "composer": {
            "require": metadata.get("composer", {}).get("require", {}),
        },
        "assets": {
            "public": metadata.get("assets", {}).get("public", []),
        },
        "dist": {
            "type": "zip",
            "url": archive_url,
            "sha256": sha256,
        },
    }
    write_registry(registry)

    print(
        f"Published {metadata['package']} {metadata['version']} "
        f"from {source_root} to {archive_url} ({packaged_count} files)"
    )


if __name__ == "__main__":
    try:
        main()
    except Exception as exc:  # pragma: no cover - operational script
        print(f"Error: {exc}", file=sys.stderr)
        raise SystemExit(1)
