#!/bin/bash
set -e

echo "==> PHP version..."
php -v

echo "==> php-fpm82 binary check..."
which php-fpm82 || echo "NOT FOUND"

echo "==> Testing config..."
php-fpm82 -t -y /app/php-fpm.conf 2>&1 || echo "CONFIG ERROR"

echo "==> Starting PHP-FPM in FOREGROUND (debug)..."
php-fpm82 -F -y /app/php-fpm.conf &
FPM_PID=$!

echo "==> Waiting 3 seconds..."
sleep 3

echo "==> Checking if FPM is running..."
ps aux | grep php-fpm || echo "FPM NOT RUNNING"

echo "==> Checking port 9000..."
netstat -tlnp 2>/dev/null | grep 9000 || ss -tlnp | grep 9000 || echo "PORT 9000 NOT LISTENING"

echo "==> Caching Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Starting Caddy..."
caddy run --config /app/Caddyfile