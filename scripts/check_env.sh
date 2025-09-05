#!/usr/bin/env bash
# sanity-check required env variables and common server setup
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

required=(
  MOLLIE_KEY
  APP_KEY
  APP_URL
)

missing=()
for v in "${required[@]}"; do
  if ! grep -q "${v}=" .env 2>/dev/null; then
    missing+=("$v")
  fi
done

if [ ${#missing[@]} -gt 0 ]; then
  echo "Missing required .env variables: ${missing[*]}"
  exit 2
fi

echo ".env looks OK (basic check)."

# Check storage link
if [ ! -L public/storage ]; then
  echo "Warning: public/storage symlink missing. Run: php artisan storage:link"
fi

echo "Check completed."
