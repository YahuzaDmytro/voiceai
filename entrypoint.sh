#!/usr/bin/env sh
set -e

php artisan optimize
php artisan storage:link
php artisan migrate --force

exec php artisan "$@"
