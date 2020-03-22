#!/bin/sh

until nc -z -v -w30 db 3306
do
    echo "Waiting for database connection..."
    sleep 5
done

php bin/console doctrine:migrations:migrate

docker-php-entrypoint php-fpm
