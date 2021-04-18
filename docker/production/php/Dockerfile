FROM php:7.4-fpm

ARG app_folder

RUN apt-get update && \
    apt-get install -y \
        curl \
        git \
        zip \
        nano \
        unzip && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-configure bcmath --enable-bcmath && \
    docker-php-ext-install -j$(nproc) bcmath pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer --version

COPY . $app_folder

WORKDIR $app_folder

RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data storage/ bootstrap/cache/
