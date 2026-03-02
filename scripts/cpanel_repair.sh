#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

echo "[1/8] Checking project root..."
if [ ! -f artisan ]; then
  echo "ERROR: artisan not found. Run this script from inside the HRM project."
  exit 1
fi

echo "[2/8] Installing PHP dependencies (if missing)..."
if [ ! -f vendor/autoload.php ]; then
  composer install --no-dev --optimize-autoloader --no-interaction
fi

echo "[3/8] Validating .env and APP_KEY..."
if [ ! -f .env ]; then
  cp .env.example .env
fi
if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

echo "[4/8] Fixing storage symlink..."
if [ -d public/storage ] && [ ! -L public/storage ]; then
  rm -rf public/storage
fi
if [ -L public/storage ]; then
  LINK_TARGET="$(readlink public/storage || true)"
  if [ "$LINK_TARGET" != "../storage/app/public" ] && [ "$LINK_TARGET" != "storage/app/public" ]; then
    rm -f public/storage
  fi
fi
if [ ! -L public/storage ]; then
  php artisan storage:link
fi

echo "[5/8] Clearing and rebuilding caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache || true
php artisan view:cache || true

echo "[6/8] Setting permissions..."
find storage bootstrap/cache -type d -exec chmod 775 {} \;
find storage bootstrap/cache -type f -exec chmod 664 {} \;

echo "[7/8] Creating root fallback entry files (for hosts without /public document root)..."
if [ -f public/.htaccess ]; then
  cp public/.htaccess .htaccess
fi
if [ -f public/index.php ]; then
  cp public/index.php index.php
  sed -i "s|__DIR__.'/../vendor/autoload.php'|__DIR__.'/vendor/autoload.php'|g" index.php
  sed -i "s|__DIR__.'/../bootstrap/app.php'|__DIR__.'/bootstrap/app.php'|g" index.php
fi

if [ -d public/build ]; then
  rm -rf build
  ln -s public/build build 2>/dev/null || cp -R public/build build
fi

echo "[8/8] Done."
echo "If your domain can set document root, prefer: <project>/public"
echo "Then test with: curl -I https://your-domain"
