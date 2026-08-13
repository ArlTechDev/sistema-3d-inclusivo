#!/usr/bin/env bash
# Inicia servidor Laravel con BD dedicada para capturas
set -e

cd "$(dirname "$0")/../software/laravel_web"

export DB_CONNECTION=sqlite
export DB_DATABASE="$(pwd)/database/capturas.sqlite"

php artisan config:clear --ansi
php artisan route:clear --ansi

echo "=============================================="
echo "  Servidor iniciado en http://127.0.0.1:8000"
echo "  Para detener: Ctrl+C"
echo "=============================================="

php artisan serve --host=127.0.0.1 --port=8000
