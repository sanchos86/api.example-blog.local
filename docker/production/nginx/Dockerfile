FROM nginx:alpine

ARG app_folder

COPY . $app_folder

COPY docker/production/nginx/nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
EXPOSE 443
