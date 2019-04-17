#!/bin/bash

# Create a new private key if one doesnt exist, or use the xeisting one if it does
DOMAIN=$1
NUM_OF_DAYS=999

openssl req -new -sha256 -nodes -out "/files/$DOMAIN.csr" -newkey rsa:2048 -keyout "/files/$DOMAIN.key" -config /scripts/rootCA.csr.cnf
cat /scripts/v3.ext | sed s/%%DOMAIN%%/"$DOMAIN"/g > /tmp/__v3.ext
openssl x509 -req -in "/files/$DOMAIN.csr" -CA /files/rootCA.pem -CAkey /files/rootCA.key -CAcreateserial -out "/files/$DOMAIN.crt" -days $NUM_OF_DAYS -sha256 -extfile /tmp/__v3.ext
rm /tmp/__v3.ext
