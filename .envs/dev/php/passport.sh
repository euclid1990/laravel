#!/bin/bash

if [ $(cat ./.env | grep "API_CLIENT_ID") == "API_CLIENT_ID=" ] || [ $(cat ./.env | grep "API_CLIENT_SECRET") == "API_CLIENT_SECRET=" ]; then
    echo "---GENERATING PASSPORT CLIENT ID & SECRET.---"

    passport=$(php artisan passport:client --password --no-interaction)
    clientId=$(expr match "$passport" '.*\(Client ID: [[:digit:]]*\)')
    clientSecret=$(expr match "$passport" '.*\(Client secret: [[:alnum:]]*\)')

    clientId=${clientId:11}
    clientSecret=${clientSecret:15}

    sed -i "s/API_CLIENT_ID=.*/API_CLIENT_ID=$clientId/g" .env
    sed -i "s/API_CLIENT_SECRET=.*/API_CLIENT_SECRET=$clientSecret/g" .env

    echo "---PASSPORT CLIENT ID & SECRET IS GENERATED.---"
fi
