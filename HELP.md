docker compose up -d
docker exec -it laravel_app bash
php artisan serve --host=0.0.0.0 --port=8000
docker compose up -d --build

!!!
docker exec -it laravel_app php artisan migrate