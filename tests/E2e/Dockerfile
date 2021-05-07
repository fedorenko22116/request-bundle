ARG PHP_VERSION='8'

FROM request-bundle-ci as base
FROM composer as assets

COPY --from=base /var/www/bundle /var/www/app

WORKDIR /var/www/app/tests/E2e

RUN composer install

FROM php:${PHP_VERSION}-fpm

RUN apt-get update && apt-get install -y netcat

RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY --chown=www-data:www-data --from=assets /var/www/app /var/www/app
COPY docker-entrypoint.sh /usr/local/bin/app-docker-entrypoint.sh

RUN chmod 755 /usr/local/bin/app-docker-entrypoint.sh

WORKDIR /var/www/app/tests/E2e

ENTRYPOINT ["app-docker-entrypoint.sh"]
