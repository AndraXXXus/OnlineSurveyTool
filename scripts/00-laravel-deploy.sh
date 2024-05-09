#!/usr/bin/env bash
# echo "Running composer"
# composer install --no-dev --working-dir=/var/www/html

# echo "Running npm"
# npm i
# npm run build

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


