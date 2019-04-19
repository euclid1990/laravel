FROM php:7.2.17-fpm-stretch

ENV DEBIAN_FRONTEND noninteractive
ENV USERID=1001 GROUPID=1001

# Install os packages
RUN apt-get update && apt-get install -y \
    sudo \
    g++ \
    zip \
    vim \
    curl \
    telnet \
    postfix \
    mailutils \
    memcached \
    libpq-dev \
    libmemcached-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libmcrypt-dev \
    libicu-dev \
    libsqlite3-dev \
    libssl-dev \
    libcurl3-dev \
    libxml2-dev \
    libzzip-dev \
    libpcre3-dev \
    --no-install-recommends apt-utils \
    && rm -r /var/lib/apt/lists/*

# Configure GD library
RUN docker-php-ext-configure gd \
    --enable-gd-native-ttf \
    --with-jpeg-dir=/usr/lib \
    --with-freetype-dir=/usr/include/freetype2

# Install mongodb, xdebug, memcached, mcrypt
RUN pecl install mongodb xdebug memcached-3.1.3 mcrypt-1.0.2 \
    && docker-php-ext-enable mongodb xdebug memcached mcrypt

# Install extensions using the helper script provided by the base image
RUN docker-php-ext-install \
    gd \
    bcmath \
    pdo_mysql \
    pdo_pgsql \
    zip \
    mysqli

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Make composer global
RUN sudo chmod 755 /usr/local/bin/composer

WORKDIR /var/www/app

COPY php/php.ini /usr/local/etc/php/php.ini

COPY php/php-fpm.conf /usr/local/etc/php-fpm.conf

COPY php/www.conf /usr/local/etc/php-fpm.d/www.conf

COPY php/*.sh /scripts/

COPY common/wait-for-it.sh /scripts/

RUN chmod a+x /scripts/*.sh

# Create new username: php-fpm
RUN useradd -ms /bin/bash php-fpm
# Modify php-fpm user_id:group_id to current host_user_id:host_group_id
RUN usermod -u $USERID php-fpm
RUN groupmod -g $GROUPID php-fpm
# Make php-fpm user can sudo without password
RUN sudo echo "php-fpm ALL=(ALL:ALL) NOPASSWD: ALL" > /etc/sudoers.d/php-fpm
# Set user to running image
USER php-fpm

EXPOSE 9000

ENTRYPOINT ["/scripts/entrypoint.sh"]

CMD ["/scripts/command.sh"]
