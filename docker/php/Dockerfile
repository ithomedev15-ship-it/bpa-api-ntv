FROM php:8.3-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# copy composer files dulu (anti cache bug)
COPY composer.json composer.lock* ./

RUN composer install --no-dev --optimize-autoloader

COPY . .

CMD ["php-fpm"]
