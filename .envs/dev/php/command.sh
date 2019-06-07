#!/bin/bash

/scripts/wait-for-it.sh mysql:3306 --timeout=300 -- echo 'Mysql service is ready!'

/scripts/passport.sh

php-fpm
