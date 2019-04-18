#!/bin/bash

/scripts/wait-for-it.sh mysql:3306 --timeout=300 -- echo 'Mysql service is ready!'


standardPath=vendor/squizlabs/php_codesniffer/src/Standards/SunOS

if [ ! -d "$standardPath" ]; then
  cp -i -r .envs/dev/php_cs/SunOS/ $standardPath
fi

php-fpm
