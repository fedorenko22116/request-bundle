if [ "${RUN_TESTS}" = "1" ];
 then
    composer install && composer phpcs && composer phpstan;
 else
    echo 'Tests are skiped';
fi
