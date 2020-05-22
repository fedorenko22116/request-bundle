ARG PHP_VERSION='7.4'

FROM php:${PHP_VERSION}-cli

ENV COMPOSER_HOME /composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PATH /composer/vendor/bin:$PATH

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y zip libzip-dev
RUN docker-php-ext-configure zip --with-libzip && docker-php-ext-install zip || docker-php-ext-install zip
RUN touch /usr/local/etc/php/php.ini
RUN composer global require phpunit/phpunit

COPY . /var/www/bundle

WORKDIR /var/www/bundle

RUN composer validate
RUN composer install
RUN composer phpcs
RUN composer phpstan
