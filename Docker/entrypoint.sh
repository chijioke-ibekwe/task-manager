#!/bin/sh

composer install --no-progress --no-interaction

cp .env.example .env

php artisan migrate
php artisan key:generate
php artisan jwt:secret

php artisan serve --port=$PORT --host=0.0.0.0 --env=.env

exec docker-php-entrypoint "$@"