FROM node:22-alpine AS node-builder
WORKDIR /var/www
COPY package*.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources resources
COPY public public
RUN npm run build

FROM php:8.4-fpm-alpine AS php-stage
RUN apk add --no-cache git bash curl libpng-dev libxml2-dev zip unzip freetype-dev libjpeg-turbo-dev libwebp-dev zlib-dev libzip-dev oniguruma-dev postgresql-dev supervisor autoconf g++ make

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN pecl install redis && docker-php-ext-enable redis

RUN addgroup -g 1000 appuser && adduser -D -u 1000 -G appuser appuser

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

COPY . .
COPY --from=node-builder /var/www/public/build /var/www/public/build

# Configurações
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p storage/framework/{cache,sessions,views} storage/app/public public/storage bootstrap/cache storage/logs /var/log/supervisor
RUN chown -R appuser:appuser storage bootstrap/cache public storage/logs /var/log/supervisor /var/run/php

USER appuser
EXPOSE 9000
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]