#!/bin/bash
set -e

echo "==> Caching Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Running migrations..."
php artisan migrate --force

php-fpm82 -t -y /app/php-fpm.conf && echo "Config OK" || echo "Config ERROR"

echo "==> Starting PHP-FPM..."
php-fpm82 -D -F -y /app/php-fpm.conf

echo "==> Waiting for PHP-FPM..."
sleep 2

echo "==> Starting Caddy..."
caddy run --config /app/Caddyfile