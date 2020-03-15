ARG PHP_VERSION
ARG RUN_TESTS

FROM php:${PHP_VERSION}-cli

RUN apt-get update && apt-get install -y zip libzip-dev
RUN docker-php-ext-configure zip --with-libzip && docker-php-ext-install zip || docker-php-ext-install zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN touch /usr/local/etc/php/php.ini

COPY . /var/www/bundle

WORKDIR /var/www/bundle

RUN composer validate
RUN composer install

RUN if [ "x$RUN_TESTS" = "x" ] ; then echo 'Tests are skiped'; else composer phpcs && composer phpstan ; fi
