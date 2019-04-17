#!/bin/bash

if [ ! -d "src/phpMyAdmin-4.8.5" ]; then
    echo "------ [BEGIN] Extract PhpMyAdmin Source Code ------"
    tar -zxf phpMyAdmin-4.8.5.tar.gz -C src/
    echo "------ [END] Extract PhpMyAdmin Source Code ------"
fi
