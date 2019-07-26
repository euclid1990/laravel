#!/bin/bash

yarn install

# Sleep untils backend api endpoint existing
while [ ! -f /var/www/app/resources/js/endpoint.js ]
do
  sleep 3
done

yarn run dev

yarn run documents

sleep infinity
