#!/bin/sh
set -e

echo "==> Starting School Portal deployment..."

# Go to app directory
cd /var/www/html

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "==> Generating APP_KEY..."
    php artisan key:generate --force
fi

# Clear and cache config for production
echo "==> Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# # Run database migrations
# echo "==> Running migrations..."
# php artisan migrate --force

# Run only NEW migrations — never wipes existing data
echo "==> Running migrations..."
php artisan migrate --force

# Make sure storage is linked
echo "==> Linking storage..."
php artisan storage:link || true

# Fix permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

echo "==> Starting services..."
exec supervisord -c /etc/supervisord.conf
