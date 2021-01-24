FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y \
        curl \
        git \
        zip \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-configure bcmath --enable-bcmath && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) bcmath pdo_mysql gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer --version

WORKDIR /var/www
