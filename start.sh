#!/bin/bash
set -e

echo "==> Caching Laravel config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Starting PHP-FPM..."
mkdir -p /run
php-fpm82 --daemonize --fpm-config /etc/php82/php-fpm.conf

echo "==> Starting Caddy..."
caddy run --config /app/Caddyfile