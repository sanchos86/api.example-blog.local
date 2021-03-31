FROM nginx:latest

ARG app_folder

COPY . $app_folder

COPY docker/production/nginx/nginx.conf /etc/nginx/conf.d/default.conf

RUN chmod 700 $app_folder/ssh && \
    chmod 600 $app_folder/ssh/_wildcard.web-artisan.ru-key.pem && \
    chmod 644 $app_folder/ssh/_wildcard.web-artisan.ru.pem

EXPOSE 80
EXPOSE 443
