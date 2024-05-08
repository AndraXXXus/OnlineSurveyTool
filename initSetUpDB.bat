type nul > database/database.sqlite
call php artisan migrate:fresh
call php artisan db:seed
call php artisan route:clear && php artisan route:cache
