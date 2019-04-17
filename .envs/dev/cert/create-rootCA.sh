#!/bin/bash

# Create CA key and cert
openssl genrsa -out /files/rootCA.key 2048
openssl req -subj "/C=VN/ST=Hanoi/L=Hanoi/O=MyCompany/OU=Division/emailAddress=admin@example.com/CN=Localhost Certification Authority" \
    -x509 -new -nodes -key /files/rootCA.key -sha256 -days 1024 -out /files/rootCA.pem
