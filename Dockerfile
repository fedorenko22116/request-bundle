ARG PHP_VERSION='8'

FROM composer:latest as composer

COPY composer.json /assets/

WORKDIR /assets

RUN composer validate
RUN composer install

FROM php:${PHP_VERSION}-cli

ENV COMPOSER_HOME /composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PATH /composer/vendor/bin:$PATH

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y zip libzip-dev git
RUN docker-php-ext-configure zip --with-libzip && docker-php-ext-install zip || docker-php-ext-install zip
RUN touch /usr/local/etc/php/php.ini
RUN composer global require phpstan/phpstan:"^0.12.25"

COPY . /var/www/bundle
COPY --from=composer /assets/vendor /var/www/bundle/vendor

WORKDIR /var/www/bundle

RUN composer require squizlabs/php_codesniffer escapestudios/symfony2-coding-standard --dev
RUN composer phpcs
RUN composer phpstan
