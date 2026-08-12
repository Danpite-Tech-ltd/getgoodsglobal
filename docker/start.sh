#!/bin/sh

set -e

cd /var/www/html

mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

if [ -n "$APP_KEY" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

exec /usr/bin/supervisord \
    -c /etc/supervisor/conf.d/supervisord.conf
