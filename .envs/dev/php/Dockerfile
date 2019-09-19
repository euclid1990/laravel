FROM php:7.2.17-fpm-stretch

ARG TZ=UTC
ARG USERID=1001
ARG GROUPID=1001
ENV DEBIAN_FRONTEND noninteractive
ENV TZ=${TZ} USERID=${USERID} GROUPID=${GROUPID}

# Change container timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install os packages
RUN apt-get update && apt-get install -y \
    sudo \
    g++ \
    zip \
    vim \
    curl \
    procps \
    telnet \
    postfix \
    mailutils \
    logrotate \
    supervisor \
    sasl2-bin \
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
    libzip-dev \
    liblz4-dev \
    libevent-dev \
    libsasl2-2 \
    libsasl2-dev \
    libsasl2-modules \
    --no-install-recommends apt-utils \
    && rm -r /var/lib/apt/lists/*

# Configure GD library
RUN docker-php-ext-configure gd \
    --enable-gd-native-ttf \
    --with-jpeg-dir=/usr/lib \
    --with-freetype-dir=/usr/include/freetype2

# Install mongodb, xdebug, igbinary, msgpack, mcrypt
RUN pecl install mongodb xdebug memcached-3.1.3 mcrypt-1.0.2 igbinary-3.0.1 msgpack-2.0.3 \
    && docker-php-ext-enable mongodb xdebug mcrypt igbinary msgpack

# Install memcached with sasl
RUN mkdir -p mkdir -p /usr/src/php/ext/memcached \
    && tar -C /usr/src/php/ext/memcached -zxvf "$(pecl config-get download_dir)/memcached-3.1.3.tgz" --strip 1 \
    && docker-php-ext-configure memcached \
    --with-php-config=/usr/local/bin/php-config \
    --with-libmemcached-dir \
    --with-zlib-dir \
    --with-system-fastlz=no \
    --enable-sasl=yes \
    --enable-memcached-igbinary=yes \
    --enable-memcached-msgpack=yes \
    --enable-memcached-json=yes \
    --enable-memcached-protocol=no \
    --enable-memcached-sasl=yes \
    --enable-memcached-session=yes \
    && docker-php-ext-install memcached

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

COPY php/php-worker /etc/logrotate.d/php-worker

COPY php/crontab/cron.minutely.schedule /etc/cron.minutely/schedule

COPY php/crontab/cron.daily.logrotate /etc/cron.daily/logrotate

COPY php/crontab/crontab /etc/crontab

COPY php/php-worker.conf /etc/supervisor/conf.d/php-worker.conf

COPY php/*.sh /scripts/

COPY common/wait-for-it.sh /scripts/

RUN chmod a+x /scripts/*.sh

# Create new username: php-fpm
RUN useradd -ms /bin/bash php-fpm --no-log-init
# Modify php-fpm user_id:group_id to current host_user_id:host_group_id
RUN usermod -u $USERID php-fpm
RUN groupmod -g $GROUPID php-fpm || exit 0
# Make php-fpm user can sudo without password
RUN sudo echo "php-fpm ALL=(ALL:ALL) NOPASSWD: ALL" > /etc/sudoers.d/php-fpm
# Set user to running image
USER php-fpm

EXPOSE 9000

ENTRYPOINT ["/scripts/entrypoint.sh"]

CMD ["/scripts/command.sh"]
