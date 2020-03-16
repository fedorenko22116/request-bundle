ARG PHP_VERSION='5.6'

FROM php:${PHP_VERSION}-cli

ENV RUN_TESTS=0

RUN apt-get update && apt-get install -y zip libzip-dev
RUN docker-php-ext-configure zip --with-libzip && docker-php-ext-install zip || docker-php-ext-install zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN touch /usr/local/etc/php/php.ini

COPY . /var/www/bundle

WORKDIR /var/www/bundle

RUN composer validate
RUN composer install --ignore-platform-reqs --no-dev

CMD ["docker-entrypoint.sh"]
