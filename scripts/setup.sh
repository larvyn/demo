#!/bin/bash
# Prepares (or resets) the demo: dependencies, .env, database, logs, failed jobs and a test report.
set -e
ROOT=$(cd "$(dirname "$0")/.." && pwd)
APP="$ROOT/acme-store"
cd "$APP"

composer install --no-interaction --quiet
npm install --silent

if [ ! -f .env ]; then
  cp .env.example .env
  sed -i 's/^APP_NAME=.*/APP_NAME="Acme Store"/; s#^APP_URL=.*#APP_URL=http://acme-store.test#' .env
  php artisan key:generate --force
fi
touch database/database.sqlite
php artisan migrate:fresh --seed --force
php artisan storage:link --force >/dev/null 2>&1 || true

# Log entries and failed jobs for the Logs and Queues views.
rm -f storage/logs/laravel.log
php artisan queue:flush >/dev/null
php "$ROOT/scripts/demo-seed.php"
php artisan queue:work --stop-when-empty --tries=1 >/dev/null 2>&1 || true

# A test report for the Tests view (Larvyn reads it from its home).
mkdir -p "$ROOT/.larvyn/junit"
php artisan test --log-junit="$ROOT/.larvyn/junit/acme.xml" >/dev/null 2>&1 || true

# One uncommitted change so Source control has a diff to show.
sed -i "s/->where('stock', '>', 0);/->where('stock', '>', 0)->where('is_active', true);/" app/Models/Product.php

echo "Demo ready in $ROOT"
