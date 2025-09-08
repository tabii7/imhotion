#!/usr/bin/env bash
set -euo pipefail

ROOT="/var/www/imhotion.easyred.com/public/easyred"
ZIP="$ROOT/deploy/easyred-website.zip"
TMP="$ROOT/.tmp_deploy_$$"
KEEP=("deploy" "deploy_easyred.sh")  # keep these when cleaning webroot

echo "==> Checking zip..."
if [ ! -f "$ZIP" ]; then
  echo "❌ ZIP not found at $ZIP"
  exit 1
fi

echo "==> Preparing temp dir..."
rm -rf "$TMP"
mkdir -p "$TMP"

echo "==> Unzipping..."
unzip -q "$ZIP" -d "$TMP"

# If the zip contains a single top-level folder, step into it
if [ "$(find "$TMP" -mindepth 1 -maxdepth 1 -type d | wc -l)" -eq 1 ] && \
   [ "$(find "$TMP" -mindepth 1 -maxdepth 1 | wc -l)" -eq 1 ]; then
  INNER="$(find "$TMP" -mindepth 1 -maxdepth 1 -type d)"
  TMP="$INNER"
  echo "==> Entered inner folder: $TMP"
fi

has_file() { test -f "$TMP/$1"; }
has_dir()  { test -d "$TMP/$1"; }

echo "==> Detecting project type..."
TYPE="static"

if has_file "package.json"; then
  TYPE="source"
fi

echo "   Detected: $TYPE"

# Ensure Node toolchain if we need it
enable_corepack() {
  if ! command -v corepack >/dev/null 2>&1; then
    echo "==> Installing corepack..."
    npm i -g corepack >/dev/null 2>&1 || true
  fi
  corepack enable || true
}

pnpm_cmd() {
  # approve-builds first time only; ignore errors if no approvals needed
  pnpm approve-builds || true
  pnpm install
}

build_next() {
  echo "==> Building Next (static export)..."
  # Ensure basePath + export in next config
  if has_file "next.config.mjs"; then
    cat > next.config.mjs <<'EON'
/** @type {import('next').NextConfig} */
const nextConfig = {
  output: 'export',
  basePath: '/easyred',
  images: { unoptimized: true },
  eslint: { ignoreDuringBuilds: true },
  typescript: { ignoreBuildErrors: true },
};
export default nextConfig;
EON
  elif has_file "next.config.js"; then
    cat > next.config.js <<'EON'
/** @type {import('next').NextConfig} */
const nextConfig = {
  output: 'export',
  basePath: '/easyred',
  images: { unoptimized: true },
  eslint: { ignoreDuringBuilds: true },
  typescript: { ignoreBuildErrors: true },
};
module.exports = nextConfig;
EON
  fi

  pnpm_cmd
  pnpm build
  # Next puts static export into ./out
  if [ ! -d "out" ]; then
    npx next export
  fi
  if [ ! -d "out" ]; then
    echo "❌ Next export failed (no ./out dir)."
    exit 1
  fi
  DEPLOY_SRC="$TMP/out"
}

patch_vite_base() {
  # Add/force base: '/easyred' to vite config
  if has_file "vite.config.ts" || has_file "vite.config.js" || has_file "vite.config.mjs"; then
    CFG="$( (ls vite.config.* 2>/dev/null || true) | head -n1 )"
    echo "==> Patching $CFG for base: /easyred"
    # If base already present, replace it; else insert one
    if grep -q "base:" "$CFG"; then
      sed -i "s|base: *['\"][^'\"]*['\"]|base: '/easyred'|g" "$CFG"
    else
      # Insert after first defineConfig or export default
      sed -i "0,/defineConfig\s*(\s*{/{s//defineConfig({\n  base: '\\/easyred',/}" "$CFG" || true
      sed -i "0,/export default\s*{/{s//export default {\n  base: '\\/easyred',/}" "$CFG" || true
    fi
  fi
}

build_vite_or_laravel_vite() {
  echo "==> Building Vite/Laravel-Vite..."
  pnpm_cmd
  # Try to detect Vite config
  if ls vite.config.* >/dev/null 2>&1; then
    patch_vite_base
    pnpm run build || pnpm build || npm run build || true
    if [ -d "dist" ]; then
      DEPLOY_SRC="$TMP/dist"
      return
    fi
  fi

  # Laravel-Vite style: public/build gets created
  # Some projects place index.html under public/
  pnpm run build || true
  if [ -d "public/build" ]; then
    echo "==> Detected Laravel-Vite build in public/build"
    # Prepare a temp static dir to deploy
    WORK="$TMP/.static_collect"
    rm -rf "$WORK"
    mkdir -p "$WORK/build" "$WORK/fonts" "$WORK/images"
    # index.html may be in public/
    if [ -f "public/index.html" ]; then
      cp "public/index.html" "$WORK/index.html"
    else
      # If an app entry exists (e.g., resources/views), try to find an index
      # Fallback: create a minimal index that loads built assets (user can replace later)
      echo "<!doctype html><html><head><meta charset='utf-8'><title>Easyred</title><link rel='stylesheet' href='/easyred/build/assets/app.css'></head><body><div id='app'></div><script src='/easyred/build/assets/app.js' type='module'></script></body></html>" > "$WORK/index.html"
    fi
    cp -r public/build/* "$WORK/build/" || true
    # runtime assets if present
    [ -d public/fonts ] && cp -r public/fonts/* "$WORK/fonts/" || true
    [ -d public/images ] && cp -r public/images/* "$WORK/images/" || true

    # Fix absolute paths in index.html to include /easyred
    sed -i \
      -e 's|href="/build|href="/easyred/build|g' \
      -e 's|src="/build|src="/easyred/build|g' \
      -e 's|href="/fonts|href="/easyred/fonts|g' \
      -e 's|src="/fonts|src="/easyred/fonts|g' \
      -e 's|href="/images|href="/easyred/images|g' \
      -e 's|src="/images|src="/easyred/images|g' \
      "$WORK/index.html"

    DEPLOY_SRC="$WORK"
    return
  fi

  # Plain Vite default output (dist)
  if [ -d "dist" ]; then
    DEPLOY_SRC="$TMP/dist"
    return
  fi

  echo "❌ Could not find a Vite build output (dist or public/build)."
  exit 1
}

deploy_static_src() {
  local SRC="$1"
  echo "==> Deploying static from: $SRC"

  # Clean webroot except KEEP items
  shopt -s dotglob
  for p in "$ROOT"/*; do
    base="$(basename "$p")"
    skip=false
    for k in "${KEEP[@]}"; do
      if [ "$base" = "$k" ]; then skip=true; break; fi
    done
    $skip && continue
    rm -rf "$p"
  done
  shopt -u dotglob

  # Copy new site
  cp -a "$SRC"/* "$ROOT"/

  # Permissions
  chown -R www-data:www-data "$ROOT"
  chmod -R 755 "$ROOT"

  echo "✅ Deployed to $ROOT"
  echo "🔗 Open: https://imhotion.easyred.com/easyred"
}

if [ "$TYPE" = "static" ]; then
  echo "==> Static package detected (no package.json)."
  # Try common static roots
  if [ -f "$TMP/index.html" ]; then
    DEPLOY_SRC="$TMP"
  elif [ -d "$TMP/dist" ]; then
    DEPLOY_SRC="$TMP/dist"
  elif [ -d "$TMP/build" ]; then
    DEPLOY_SRC="$TMP/build"
  elif [ -f "$TMP/public/index.html" ]; then
    # Static in public
    # Fix paths in index for /easyred base
    sed -i \
      -e 's|href="/build|href="/easyred/build|g' \
      -e 's|src="/build|src="/easyred/build|g' \
      -e 's|href="/fonts|href="/easyred/fonts|g' \
      -e 's|src="/fonts|src="/easyred/fonts|g' \
      -e 's|href="/images|href="/easyred/images|g' \
      -e 's|src="/images|src="/easyred/images|g' \
      "$TMP/public/index.html"
    mkdir -p "$TMP/.static_collect/build" "$TMP/.static_collect/fonts" "$TMP/.static_collect/images"
    cp "$TMP/public/index.html" "$TMP/.static_collect/index.html"
    [ -d "$TMP/public/build" ]  && cp -r "$TMP/public/build/"*  "$TMP/.static_collect/build/"  || true
    [ -d "$TMP/public/fonts" ]  && cp -r "$TMP/public/fonts/"*  "$TMP/.static_collect/fonts/"  || true
    [ -d "$TMP/public/images" ] && cp -r "$TMP/public/images/"* "$TMP/.static_collect/images/" || true
    DEPLOY_SRC="$TMP/.static_collect"
  else
    echo "❌ No static index.html found."
    exit 1
  fi

  deploy_static_src "$DEPLOY_SRC"
  rm -rf "$TMP"
  exit 0
fi

# SOURCE project
enable_corepack

# Detect framework
if has_file "next.config.mjs" || has_file "next.config.js"; then
  echo "==> Next.js project detected."
  build_next
  deploy_static_src "$DEPLOY_SRC"
  rm -rf "$TMP"
  exit 0
fi

# Assume Vite / Laravel-Vite
if has_file "vite.config.ts" || has_file "vite.config.mjs" || has_file "vite.config.js" || has_dir "public"; then
  echo "==> Vite/Laravel-Vite project detected."
  build_vite_or_laravel_vite
  deploy_static_src "$DEPLOY_SRC"
  rm -rf "$TMP"
  exit 0
fi

echo "❌ Unknown project type. Please share the zip contents."
exit 1
