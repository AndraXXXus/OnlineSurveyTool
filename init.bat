call composer install --no-interaction
copy .env.example .env
call php artisan key:generate
call npm install
call npm run build

type nul>database/database.sqlite
call php artisan migrate:fresh
call php artisan db:seed
call php artisan route:clear && php artisan route:cache

call php artisan storage:link
