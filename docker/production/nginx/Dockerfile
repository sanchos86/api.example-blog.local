FROM nginx:latest

ARG app_folder

COPY . $app_folder

COPY docker/production/nginx/nginx.conf /etc/nginx/conf.d/default.conf

RUN chmod 700 $app_folder/ssh && \
    chmod 600 $app_folder/ssh/api.web-artisan.ru+1-key.pem && \
    chmod 644 $app_folder/ssh/api.web-artisan.ru+1.pem

EXPOSE 80
EXPOSE 443
