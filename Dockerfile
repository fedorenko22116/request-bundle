ARG PHP_VERSION='5.6'
ARG RUN_TESTS='0'

FROM php:${PHP_VERSION}-cli

RUN apt-get update && apt-get install -y zip libzip-dev
RUN docker-php-ext-configure zip --with-libzip && docker-php-ext-install zip || docker-php-ext-install zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN touch /usr/local/etc/php/php.ini

COPY . /var/www/bundle

WORKDIR /var/www/bundle

RUN composer validate
RUN composer install --ignore-platform-reqs --no-dev
RUN if [ "${RUN_TESTS}" = "1" ]; then composer install && composer phpcs && composer phpstan; else echo 'Tests are skiped'; fi
