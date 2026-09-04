#!/usr/bin/env bash
# =============================================================================
#  Sistema Inclusivo — Importación del paquete de migración en Linux
# =============================================================================
#  Uso:  bash importar_linux.sh [directorio_del_paquete]
#  El directorio es donde está la carpeta salida/ de la exportación.
#  Por defecto usa el directorio actual.
# =============================================================================
set -euo pipefail

DIRECTORIO="${1:-$(pwd)}"
PAQUETE="$DIRECTORIO/salida"
VOLUMEN="laravel_web_db_data"   # fijado en docker-compose.yml

echo "══════════════════════════════════════════════════════════════════════"
echo "  Sistema Inclusivo — importación en Linux"
echo "══════════════════════════════════════════════════════════════════════"

# --- Validaciones -----------------------------------------------------------
command -v docker >/dev/null 2>&1 || { echo "✗ docker no está instalado"; exit 1; }
docker info >/dev/null 2>&1 || { echo "✗ El daemon de Docker no está corriendo"; exit 1; }
[ -d "$PAQUETE" ] || { echo "✗ No se encontró la carpeta de paquete en: $PAQUETE"; exit 1; }

# --- 1. Cargar imágenes -----------------------------------------------------
if [ -f "$PAQUETE/imagenes.tar" ]; then
    echo "→ Cargando imágenes desde imagenes.tar…"
    docker load -i "$PAQUETE/imagenes.tar"
else
    echo "  ⚠ No existe imagenes.tar — se asume que las imágenes ya están cargadas."
fi

# --- 2. Volumen de la base de datos -----------------------------------------
if [ -f "$PAQUETE/db_data.tar.gz" ]; then
    if docker volume inspect "$VOLUMEN" >/dev/null 2>&1; then
        echo "→ El volumen $VOLUMEN ya existe — se reemplaza (los datos locales se pierden)."
        docker volume rm "$VOLUMEN" >/dev/null
    fi
    echo "→ Creando y restaurando volumen $VOLUMEN…"
    docker volume create "$VOLUMEN" >/dev/null
    docker run --rm \
        -v "$VOLUMEN:/data" \
        -v "$PAQUETE:/backup" \
        laravel_web-app:latest \
        tar xzf /backup/db_data.tar.gz -C /data
else
    echo "  ⚠ Sin db_data.tar.gz — la BD arrancará vacía (luego: docker compose exec app php artisan migrate --seed)"
fi

# --- 3. Extraer el proyecto -------------------------------------------------
DESTINO="$DIRECTORIO/laravel_web"
if [ -f "$PAQUETE/proyecto_laravel_web.tar.gz" ]; then
    if [ -f "$DESTINO/docker-compose.yml" ]; then
        echo "✗ Ya existe $DESTINO con docker-compose.yml. Elige otro directorio o borra la carpeta."
        exit 1
    fi
    echo "→ Extrayendo proyecto_laravel_web.tar.gz en $DESTINO…"
    mkdir -p "$DESTINO"
    tar -xzf "$PAQUETE/proyecto_laravel_web.tar.gz" -C "$DESTINO"
else
    echo "  ⚠ Sin proyecto_laravel_web.tar.gz — se asume que el proyecto ya está en $DIRECTORIO."
    DESTINO="$DIRECTORIO"
fi

# --- 4. Levantar el entorno ------------------------------------------------
cd "$DESTINO"
echo "→ Levantando contenedores (offline, sin build)…"
docker compose up -d --no-build

echo ""
echo "→ Estado:"
docker compose ps

echo ""
echo "→ Verificando http://localhost:8000 …"
sleep 5
if curl -sf -o /dev/null --max-time 15 http://localhost:8000; then
    echo "   El sistema está en línea ✓"
else
    echo "   ⚠ No respondió aún (puede estar arrancando). Reintenta: docker compose ps"
fi

echo ""
echo "══════════════════════════════════════════════════════════════════════"
echo "  Importación finalizada."
echo "  - App:   http://localhost:8000"
echo "  - MySQL: localhost:3307 (usuario admin / password)"
echo "  - Notas: docs/anexos/12_guia_migracion_docker.md"
echo "══════════════════════════════════════════════════════════════════════"
