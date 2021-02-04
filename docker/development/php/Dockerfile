FROM php:7.4-fpm

ARG user
ARG uid

RUN apt-get update && \
    apt-get install -y \
        curl \
        git \
        zip \
        unzip && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-configure bcmath --enable-bcmath && \
    docker-php-ext-install -j$(nproc) bcmath pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer --version

RUN useradd -G www-data,root -u $uid $user

USER $user
