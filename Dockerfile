FROM composer:2 AS composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

FROM node:22-alpine AS frontend

WORKDIR /app

COPY --from=composer /app/vendor /app/vendor
COPY . .

RUN yarn install --production
RUN yarn build

FROM ghcr.io/easywaresft/frankenphp:latest

COPY . /app
COPY --from=composer /app/vendor /app/vendor
COPY --from=frontend /app/public /app/public

RUN mkdir -p /app/storage/logs /app/storage/framework/cache
RUN chmod -R 755 /app/storage /app/bootstrap/cache

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer dump-autoload --optimize

RUN php artisan package:discover
RUN php artisan filament:assets

COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

CMD []
