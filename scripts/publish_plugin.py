#!/usr/bin/env python3

from __future__ import annotations

import argparse
import json
from pathlib import Path
import shutil
import subprocess
import sys


ROOT = Path(__file__).resolve().parent.parent
PACKAGES_SRC_DIR = ROOT / "packages-src"
BUILD_SCRIPT = ROOT / "scripts" / "build_registry.py"


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Publish a standalone plugin repository into the local Radaptor plugin registry."
    )
    parser.add_argument("source", help="Path to the standalone plugin repository")
    parser.add_argument(
        "--destination",
        help="Override the target directory name under packages-src/ (defaults to plugin_id)",
    )
    parser.add_argument(
        "--skip-build",
        action="store_true",
        help="Copy the source into packages-src without rebuilding registry.json and zip artifacts",
    )

    return parser.parse_args()


def load_metadata(source_root: Path) -> dict:
    metadata_path = source_root / ".registry-package.json"

    if not metadata_path.exists():
        raise RuntimeError(f"Missing .registry-package.json in {source_root}")

    with metadata_path.open("r", encoding="utf-8") as handle:
        metadata = json.load(handle)

    for required_key in ("package", "plugin_id", "version"):
        if required_key not in metadata or not isinstance(metadata[required_key], str) or metadata[required_key] == "":
            raise RuntimeError(f"Invalid or missing '{required_key}' in {metadata_path}")

    return metadata


def list_source_files(source_root: Path) -> list[Path]:
    git_dir = source_root / ".git"

    if git_dir.exists():
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

    files: list[Path] = []

    for path in sorted(source_root.rglob("*")):
        if not path.is_file():
            continue

        relative = path.relative_to(source_root)

        if ".git" in relative.parts:
            continue

        files.append(relative)

    return files


def copy_plugin_source(source_root: Path, destination_root: Path) -> int:
    tracked_files = list_source_files(source_root)

    if destination_root.exists():
        shutil.rmtree(destination_root)

    destination_root.mkdir(parents=True, exist_ok=True)

    for relative_path in tracked_files:
        source_path = source_root / relative_path
        destination_path = destination_root / relative_path
        destination_path.parent.mkdir(parents=True, exist_ok=True)
        shutil.copy2(source_path, destination_path)

    return len(tracked_files)


def rebuild_registry() -> None:
    subprocess.run([sys.executable, str(BUILD_SCRIPT)], cwd=ROOT, check=True)


def main() -> None:
    args = parse_args()
    source_root = Path(args.source).expanduser().resolve()

    if not source_root.is_dir():
        raise RuntimeError(f"Source directory does not exist: {source_root}")

    metadata = load_metadata(source_root)
    destination_name = args.destination or metadata["plugin_id"]
    destination_root = PACKAGES_SRC_DIR / destination_name
    file_count = copy_plugin_source(source_root, destination_root)

    if not args.skip_build:
        rebuild_registry()

    print(
        f"Published {metadata['package']} {metadata['version']} "
        f"from {source_root} to {destination_root} ({file_count} files)"
    )


if __name__ == "__main__":
    try:
        main()
    except Exception as exc:  # pragma: no cover - operational script
        print(f"Error: {exc}", file=sys.stderr)
        raise SystemExit(1)
