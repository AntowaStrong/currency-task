#!/bin/bash

cd /application

composer install

php artisan config:cache

php artisan migrate

exec /tini -- "$@"