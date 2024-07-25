FROM php:8.2 as php 

RUN apt-get update -y \
    && apt-get install -y unzip libpq-dev libcurl4-gnutls-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

WORKDIR /var/www

COPY . .

COPY --from=composer:2.5.5 /usr/bin/composer /usr/bin/composer

ENV PORT=8000

ENTRYPOINT ["docker/entrypoint.sh"]
