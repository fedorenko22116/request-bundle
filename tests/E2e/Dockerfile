ARG PHP_VERSION='8'

FROM php:${PHP_VERSION}-fpm

RUN apt-get update && apt-get install -y netcat zip libzip-dev git
RUN docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip || docker-php-ext-install zip pdo pdo_mysql mysqli
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY docker-entrypoint.sh /usr/local/bin/app-docker-entrypoint.sh

RUN chmod 755 /usr/local/bin/app-docker-entrypoint.sh

WORKDIR /var/www/app/tests/E2e

ENTRYPOINT ["app-docker-entrypoint.sh"]
