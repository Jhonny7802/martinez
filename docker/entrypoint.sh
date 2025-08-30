#!/bin/bash

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
while ! mysqladmin ping -h"mysql" --silent; do
    sleep 1
done

echo "MySQL is ready!"

# Copy environment file
if [ ! -f .env ]; then
    cp .env.docker .env
fi

# Generate application key if not set
php artisan key:generate --no-interaction

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "Application setup complete!"

# Start supervisor
exec "$@"
