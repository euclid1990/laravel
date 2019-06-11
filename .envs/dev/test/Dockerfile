FROM bitnami/php-fpm

# Install memcached
RUN phpize && ./configure --enable-sasl && make && make install && \

EXPOSE 9001