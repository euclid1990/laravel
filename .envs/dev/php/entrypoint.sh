#!/bin/bash

# Copy development environment file if not existing
if [[ ! -f .env && -f .env.dev.example ]]; then
    cp .env.dev.example .env
fi

# Generate application key if it not set
APP_KEY=$(cat .env | sed -n 's/^APP_KEY=/ /p')
if [[ -z "${APP_KEY// }" ]]; then
    php artisan key:generate
fi

# Install composer if it not existing
if [[ ! -d "/var/www/app/vendor" ]]; then
    composer install
fi

exec "$@"
