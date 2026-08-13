#!/usr/bin/env bash
# =============================================================================
#  Sistema Inclusivo — Exportación para migración offline (origen: Arch/Linux)
# =============================================================================
#  Genera en scripts/docker/salida/:
#    imagenes.tar                → imágenes Docker (app + mysql + base de build)
#    db_data.tar.gz              → snapshot del volumen MySQL (laravel_web_db_data)
#    proyecto_laravel_web.tar.gz → proyecto completo (incluye vendor/, node_modules/, .env)
#    volumen.txt                 → nombre del volumen exportado
#
#  Uso:  bash exportar_proyecto.sh
#  Destino:  Windows → importar_windows.ps1   |   Linux → importar_linux.sh
# =============================================================================
set -euo pipefail

RAIZ="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
PROYECTO="$RAIZ/software/laravel_web"
SALIDA="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/salida"

# Imágenes a exportar (deben coincidir con docker-compose.yml y Dockerfile)
IMAGENES=(laravel_web-app:latest mysql:8.0 php:8.4-cli composer:latest)
VOLUMEN="laravel_web_db_data"   # nombre fijado en docker-compose.yml

echo "══════════════════════════════════════════════════════════════════════"
echo "  Sistema Inclusivo — exportación para migración offline"
echo "══════════════════════════════════════════════════════════════════════"

# --- Validaciones -----------------------------------------------------------
[ -d "$PROYECTO" ] || { echo "✗ No se encontró el proyecto en: $PROYECTO"; exit 1; }
command -v docker >/dev/null 2>&1 || { echo "✗ docker no está instalado o no está en el PATH"; exit 1; }
docker info >/dev/null 2>&1 || { echo "✗ El daemon de Docker no está corriendo (systemctl start docker)"; exit 1; }

mkdir -p "$SALIDA"

# --- 1. Imágenes Docker -----------------------------------------------------
PRESENTES=()
for img in "${IMAGENES[@]}"; do
    docker image inspect "$img" >/dev/null 2>&1 && PRESENTES+=("$img") \
        || echo "  ⚠ imagen no encontrada, se omite: $img"
done

if [ "${#PRESENTES[@]}" -gt 0 ]; then
    echo "→ Guardando imágenes: ${PRESENTES[*]}"
    docker save -o "$SALIDA/imagenes.tar" "${PRESENTES[@]}"
else
    echo "✗ No hay ninguna imagen para exportar. Construye primero: docker compose build"
    exit 1
fi

# --- 2. Volumen de la base de datos -----------------------------------------
if docker volume inspect "$VOLUMEN" >/dev/null 2>&1; then
    DETUVO=0
    if docker ps --format '{{.Names}}' | grep -qx 'laravel_db'; then
        echo "→ Deteniendo laravel_db para un snapshot consistente…"
        docker stop laravel_db >/dev/null
        DETUVO=1
    fi

    echo "→ Exportando volumen $VOLUMEN…"
    # Se reutiliza la imagen de la app (ya tiene tar) para no depender de busybox
    docker run --rm \
        -v "$VOLUMEN:/data" \
        -v "$SALIDA:/backup" \
        laravel_web-app:latest \
        tar czf /backup/db_data.tar.gz -C /data .

    if [ "$DETUVO" = "1" ]; then
        docker start laravel_db >/dev/null
        echo "→ laravel_db reiniciado"
    fi
    echo "$VOLUMEN" > "$SALIDA/volumen.txt"
else
    echo "  ⚠ volumen $VOLUMEN no existe — no se exporta BD."
    echo "    En el destino se arrancará vacía: docker compose exec app php artisan migrate --seed"
fi

# --- 3. Proyecto (bind mount ./:/var/www → vendor/ y node_modules viajan) ----
echo "→ Empaquetando el proyecto (incluye vendor/, node_modules/ y .env)…"
tar czf "$SALIDA/proyecto_laravel_web.tar.gz" \
    --exclude='./storage/logs' \
    --exclude='./storage/framework/cache' \
    --exclude='./storage/framework/sessions' \
    --exclude='./storage/framework/views' \
    --exclude='./database/*.sqlite*' \
    --exclude='./.env.backup' \
    --exclude='./.env.production' \
    -C "$PROYECTO" .

# --- 4. Resumen -------------------------------------------------------------
echo ""
echo "══════════════════════════════════════════════════════════════════════"
echo "  Exportación completada en: $SALIDA"
echo "══════════════════════════════════════════════════════════════════════"
du -sh "$SALIDA"/* 2>/dev/null | sed 's/^/  /'
echo ""
echo "  SHA-256 (verificar integridad en destino):"
(cd "$SALIDA" && sha256sum imagenes.tar db_data.tar.gz proyecto_laravel_web.tar.gz 2>/dev/null) | sed 's/^/    /'
echo ""
echo "  Pasos siguientes:"
echo "    1. Copia la carpeta salida/ a la otra PC (USB, disco, red…)."
echo "    2. En Windows:  PowerShell → .\\scripts\\docker\\importar_windows.ps1 -Directorio <ruta>"
echo "    3. En Linux:    bash scripts/docker/importar_linux.sh"
echo "    4. Detalles: docs/anexos/12_guia_migracion_docker.md"
echo "══════════════════════════════════════════════════════════════════════"
