composer install --no-interaction
cp .env.example .env
php artisan key:generate
npm install
npm run build

chmod -R 777 storage bootstrap/cache

touch database/database.sqlite
php artisan migrate:fresh
php artisan db:seed
php artisan storage:link
call php artisan route:clear && php artisan route:cache

