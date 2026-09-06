#!/bin/bash
set -u
DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT="$(basename "$DIR")"

echo "============================================="
echo "EquipTrack - macOS XAMPP Launcher"
echo "============================================="
echo "Project: $DIR"
echo

for folder in uploads devices; do
  if [ ! -d "$DIR/$folder" ]; then
    mkdir -p "$DIR/$folder" 2>/dev/null || true
  fi
  chmod u+rwX "$DIR/$folder" 2>/dev/null || true
done

if [ -d "/Applications/XAMPP/manager-osx.app" ]; then
  open "/Applications/XAMPP/manager-osx.app" >/dev/null 2>&1 || true
fi

echo "Start Apache + MySQL in XAMPP if they are not running."
echo "Opening http://localhost/$PROJECT/"
open "http://localhost/$PROJECT/"
echo
read -r -p "Press Enter to close..." _
