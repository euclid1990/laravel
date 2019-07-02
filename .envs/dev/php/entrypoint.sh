#!/bin/bash

# Copy development environment file if not existing
if [[ ! -f /var/www/app/.env && -f /var/www/app/.env.dev.example ]]; then
  cp /var/www/app/.env.dev.example /var/www/app/.env
fi

# Generate application key if it not set
APP_KEY=$(cat .env | sed -n 's/^APP_KEY=/ /p')
if [[ -z "${APP_KEY// }" ]]; then
  php artisan key:generate
fi

vendorDir=/var/www/app/vendor
# Install composer if it not existing
if [[ ! -d $vendorDir ]]; then
  composer install
fi

vendorOwner=$(stat -c '%U' $vendorDir)
if [ "$vendorOwer" != "php-fpm" ]; then
  # Change owner of $vendorDir directory to user php-fpm
  sudo chown -R php-fpm:php-fpm $vendorDir
fi

standardPath=/var/www/app/vendor/squizlabs/php_codesniffer/src/Standards/SunOS
if [ ! -d "$standardPath" ]; then
  # Copy php code sniff to vendor
  cp -i -r .envs/dev/phpcs/SunOS/ $standardPath
fi

# Discovery new packages and generate manifest
composer dump-autoload

# Generate application routes for client
php artisan laroute:generate

# Starting Supervisor to start the queue process
sudo /etc/init.d/supervisor start

exec "$@"
