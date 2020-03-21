FROM 22116/request-bundle-ci:latest as base
FROM composer as assets

COPY --from=base /var/www/bundle /var/www/app

WORKDIR /var/www/app/Tests/E2e

RUN composer install

FROM php:7.4-fpm

COPY --from=assets /var/www/bundle /var/www/app

