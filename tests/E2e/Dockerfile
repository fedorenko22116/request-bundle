FROM 22116/request-bundle-ci as base
FROM composer as assets

COPY --from=base /var/www/bundle /var/www/app

WORKDIR /var/www/app/tests/E2e

RUN composer install

FROM php:7.4-fpm

COPY --chown=www-data:www-data --from=assets /var/www/app /var/www/app
