#!/bin/sh
set -e

# Ensure storage directories exist
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Cache configuration, events, routes, and views
echo "Caching configuration..."
php artisan config:cache

echo "Caching events..."
php artisan event:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Storage link..."
php artisan storage:link

# Execute the main command
exec "$@"
