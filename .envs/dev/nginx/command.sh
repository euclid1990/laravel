#!/bin/bash

until [[ -f /etc/nginx/certs/localhost.crt && -f /etc/nginx/certs/localhost.key && -f /etc/nginx/certs/.htpasswd ]]; do
  echo "Waiting for self-certificates existing..."
  sleep 1
done

/scripts/wait-for-it.sh php:9000 --timeout=300 -- echo 'PHP service is ready!'

nginx -g 'daemon off;'
