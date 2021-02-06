FROM php:7.4-fpm

ENV APP_FOLDER=/var/www/api.web-artisan.ru

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

COPY . $APP_FOLDER

WORKDIR $APP_FOLDER

RUN composer install --optimize-autoloader --no-dev

RUN chown -R www-data:www-data $APP_FOLDER/storage $APP_FOLDER/bootstrap/cache
