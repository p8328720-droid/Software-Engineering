#!/bin/sh

# Create .env if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate key if missing
if ! grep -q "APP_KEY=base64" .env; then
    php artisan key:generate
fi

exec php-fpm