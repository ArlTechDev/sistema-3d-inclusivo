# =============================================================================
#  Sistema Inclusivo — Importación del paquete de migración en Windows
# =============================================================================
#  Requiere: Docker Desktop (backend WSL2) en funcionamiento.
#
#  Uso (PowerShell):
#    .\scripts\docker\importar_windows.ps1 -Directorio "C:\ruta\al\paquete"
#  Donde -Directorio es la carpeta que contiene la salida/ de la exportación
#  (o la carpeta donde ya extrajiste proyecto_laravel_web.tar.gz).
#
#  La carpeta destino puede llamarse como quieras: el compose fija image y
#  volumen por nombre, no por nombre de carpeta.
# =============================================================================
param(
    [Parameter(Mandatory = $false)]
    [string]$Directorio = (Get-Location).Path
)

$ErrorActionPreference = "Stop"
$Paquete = Join-Path $Directorio "salida"

Write-Host "============================================================"
Write-Host "  Sistema Inclusivo — importación en Windows"
Write-Host "============================================================"

# --- Validaciones -----------------------------------------------------------
if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    throw "docker no está instalado o no está en el PATH. Instala Docker Desktop."
}
docker info *> $null
if ($LASTEXITCODE -ne 0) {
    throw "El daemon de Docker no está corriendo. Abre Docker Desktop y espera a que diga 'Engine running'."
}
if (-not (Test-Path $Paquete)) {
    throw "No se encontró la carpeta de paquete en: $Paquete"
}

# --- 1. Cargar imágenes -----------------------------------------------------
$imagenesTar = Join-Path $Paquete "imagenes.tar"
if (Test-Path $imagenesTar) {
    Write-Host "-> Cargando imágenes desde imagenes.tar (puede tardar unos minutos)…"
    docker load -i $imagenesTar
    if ($LASTEXITCODE -ne 0) { throw "docker load falló." }
} else {
    Write-Warning "No existe imagenes.tar — las imágenes ya deben estar cargadas."
}

# --- 2. Volumen de la base de datos -----------------------------------------
$volumen = "laravel_web_db_data"   # fijado en docker-compose.yml
$dbTar = Join-Path $Paquete "db_data.tar.gz"

$existe = docker volume inspect $volumen *> $null; $existe = ($LASTEXITCODE -eq 0)
if (Test-Path $dbTar) {
    if ($existe) {
        Write-Host "-> El volumen $volumen ya existe — se reemplaza (los datos locales se pierden)."
        docker volume rm $volumen *> $null
    }
    Write-Host "-> Creando y restaurando volumen $volumen…"
    docker volume create $volumen *> $null
    docker run --rm `
        -v "${volumen}:/data" `
        -v "$($Paquete -replace '\\','/'):/backup" `
        laravel_web-app:latest `
        tar xzf /backup/db_data.tar.gz -C /data
    if ($LASTEXITCODE -ne 0) { throw "Restauración del volumen falló." }
} else {
    Write-Host "-> Sin db_data.tar.gz — la BD arrancará vacía (luego: docker compose exec app php artisan migrate --seed)"
}

# --- 3. Extraer el proyecto -------------------------------------------------
$proyectoTar = Join-Path $Paquete "proyecto_laravel_web.tar.gz"
if (Test-Path $proyectoTar) {
    Write-Host "-> Extrayendo proyecto_laravel_web.tar.gz en $Directorio…"
    # Si ya existe docker-compose.yml en $Directorio, extraer encima podría
    # mezclar versiones: se extrae a una subcarpeta limpia.
    $destino = Join-Path $Directorio "laravel_web"
    if (Test-Path (Join-Path $destino "docker-compose.yml")) {
        throw "Ya existe $destino con docker-compose.yml. Elige otro -Directorio o borra la carpeta."
    }
    New-Item -ItemType Directory -Force -Path $destino | Out-Null
    tar -xzf $proyectoTar -C $destino
} else {
    Write-Host "-> Sin proyecto_laravel_web.tar.gz — se asume que el proyecto ya está en $Directorio."
    $destino = $Directorio
}

# --- 4. Levantar el entorno ------------------------------------------------
Push-Location $destino
try {
    Write-Host "-> Levantando contenedores (offline, sin build)…"
    docker compose up -d --no-build
    if ($LASTEXITCODE -ne 0) { throw "docker compose up falló." }

    Write-Host "-> Estado:"
    docker compose ps

    Write-Host ""
    Write-Host "-> Verificando http://localhost:8000 …"
    Start-Sleep -Seconds 5
    try {
        $r = Invoke-WebRequest -Uri "http://localhost:8000" -UseBasicParsing -TimeoutSec 15
        Write-Host "   Respuesta HTTP $($r.StatusCode) — el sistema está en línea ✓"
    } catch {
        Write-Warning "   No respondió aún en http://localhost:8000 (puede estar arrancando). Reintenta: docker compose ps"
    }
} finally {
    Pop-Location
}

Write-Host ""
Write-Host "============================================================"
Write-Host "  Importación finalizada."
Write-Host "  - App:    http://localhost:8000"
Write-Host "  - MySQL:  localhost:3307 (usuario admin / password)"
Write-Host "  - Notas y troubleshooting: docs/anexos/12_guia_migracion_docker.md"
Write-Host "============================================================"
