FROM composer:2 AS laravel-src
WORKDIR /app
RUN composer create-project laravel/laravel . \
    --prefer-dist \
    --no-interaction

FROM php:8.4-fpm-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=laravel-src /app /opt/laravel

WORKDIR /var/www/html

COPY docker/php.ini /usr/local/etc/php/conf.d/laravel.ini
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
