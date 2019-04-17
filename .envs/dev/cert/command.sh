#!/bin/bash

DOMAIN=${1:-localhost}

if [[ ! -f "/files/${DOMAIN}.crt" ]]; then
    /scripts/create-rootCA.sh
    /scripts/create-certs.sh $DOMAIN
fi

if [[ ! -f "/files/.htpasswd" ]]; then
    /scripts/create-htpasswd.sh
fi

