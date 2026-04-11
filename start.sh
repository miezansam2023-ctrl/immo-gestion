#!/bin/bash
set -e

echo "==> Caching Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Starting PHP-FPM..."
PHP_FPM_CONF=$(php-fpm82 -i 2>/dev/null | grep "Loaded Configuration" | awk '{print $NF}')
sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /etc/php82/php-fpm.d/www.conf 2>/dev/null || true
php-fpm82 -D

echo "==> Starting Caddy..."
caddy run --config /app/Caddyfile