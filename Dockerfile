FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
    bash git curl zip unzip icu icu-dev oniguruma oniguruma-dev libzip libzip-dev

RUN docker-php-ext-configure intl \
 && docker-php-ext-install pdo_mysql mbstring bcmath zip intl pcntl

RUN docker-php-ext-install opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-scripts

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache || true

EXPOSE 9000
CMD ["php-fpm"]
