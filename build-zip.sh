#!/usr/bin/env bash
set -euo pipefail

# Usage: ./build-zip.sh [--no-assets] [plugin-slug]
# If you omit [plugin-slug], it will use the name of the current directory.
# Pass --no-assets to skip including the assets/ folder.

# 0) Parse flags
NO_ASSETS=0
while [[ $# -gt 0 ]]; do
  case "$1" in
    --no-assets)
      NO_ASSETS=1
      shift
      ;;
    -*)
      echo "Unknown option: $1"
      exit 1
      ;;
    *)
      break
      ;;
  esac
done

# 1) Determine plugin slug & target ZIP name
PLUGIN_SLUG="${1:-$(basename "$(pwd)")}"
ZIP_FILE="${PLUGIN_SLUG}.zip"

# 2) Compile .po → .mo
if [ -d "languages" ]; then
  echo "Compiling translation files…"
  for PO in languages/*.po; do
    [ -f "$PO" ] || continue
    MO="${PO%.po}.mo"
    msgfmt "$PO" -o "$MO"
    echo "  → $PO → $MO"
  done
else
  echo "Warning: no 'languages/' directory found; skipping .mo generation."
fi

# 3) Remove any existing ZIP
if [ -f "$ZIP_FILE" ]; then
  echo "Removing old $ZIP_FILE"
  rm -f "$ZIP_FILE"
fi

# 4) Build the ZIP
echo "Creating $ZIP_FILE …"
# 4a) add root PHP files
zip -q "$ZIP_FILE" ./*.php

# 4b) add readme
[ -f "readme.txt" ] && zip -q "$ZIP_FILE" "readme.txt"

# 4c) add languages (pot, po, mo)
[ -d "languages" ] && zip -qr "$ZIP_FILE" "languages"

# 4d) add assets (unless excluded)
if [ "$NO_ASSETS" -eq 1 ]; then
  echo "Skipping assets directory as requested."
else
  [ -d "assets" ] && zip -qr "$ZIP_FILE" "assets"
fi

echo "✅ $ZIP_FILE created successfully."