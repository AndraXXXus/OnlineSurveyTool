#!/usr/bin/env bash


echo 'Caching config...'
php artisan config:cache

echo 'Caching routes...'
php artisan route:cache

echo 'Running migrations...'
# touch database/database.sqlite
# sudo chmod -R 775 database
php artisan migrate --force
php artisan db:seed --force

echo 'Linking storage...'
php artisan storage:link
echo 'Linking storage done...'


