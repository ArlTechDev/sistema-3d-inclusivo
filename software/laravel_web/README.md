# Sistema Web + Impresora 3D con Materiales Reciclados — Recursos Táctiles para Personas con Discapacidad Visual

Plataforma web (Laravel) que gestiona recursos educativos táctiles impresos en 3D con filamento PLA reciclado, traduce texto a Braille Grado 1 y genera el código G-Code para su impresión en una impresora Prusa i3 construida con e-waste.

Documentación general del monorepo: [`AGENTS.md`](../../AGENTS.md)

## Requisitos

- PHP ≥ 8.3
- Composer 2
- Node.js ≥ 20 y npm (frontend)
- MySQL 8.0 (o Docker Compose, puerto **3307**)
- Extensiones PHP: `pdo_mysql`, `gd`, `zip`, `dom`

## Instalación

```bash
# 1. Dependencias y entorno
composer install
cp .env.example .env
# editar .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD (puerto 3307 si usas Docker)

# 2. Clave, migraciones y datos iniciales
php artisan key:generate
php artisan migrate --seed

# 3. Frontend (AdminLTE, Vite)
npm install
npm run build

# 4. Servidor de desarrollo
php artisan serve
# o: composer dev
```

### Con Docker

```bash
docker compose up -d --build
docker exec -it laravel_app php artisan migrate:fresh --seed
# MySQL disponible en localhost:3307
```

## Comandos útiles

| Tarea | Comando |
|---|---|
| Servidor de desarrollo | `composer dev` |
| Ejecutar tests | `composer test` |
| Formatear código PHP | `./vendor/bin/pint` |
| Compilar frontend | `npm run build` |
| Usuario de prueba (seed) | `php artisan db:seed` — crea `Administrador` y `Docente` |

## Roles

- **Administrador**: gestión completa (recursos, instituciones, usuarios, pedidos, papelera, exportaciones PDF/Excel).
- **Docente / Solicitante**: explora el catálogo público de recursos táctiles, solicita impresiones (con texto personalizado opcional → G-Code) y sigue el estado de sus pedidos.

## Módulos principales

- **Traductor Braille → G-Code** (`app/Services/BrailleTranslator.php`): Código Braille Español Grado 1 (27 letras, dígitos, puntuación) + generación de G-Code con dimensiones BANA. Sin estenografía (Grado 2).
- **Módulo de pedidos** (`app/Http/Controllers/PedidoController.php`): solicitud, cálculo de costos por gramos de PLA, estados (Pendiente → En impresión → Completado / Rechazado) y descarga del `.gcode` generado.
- **Catálogo público** (`/catalogo`): recursos táctiles visibles sin sesión, con búsqueda por nombre/descripción.
- **Seguridad OWASP**: sanitización de entradas (`app/Support/Sanitizer.php`), headers de seguridad, rate limiting (login y global) y Form Requests.

## Testing

```bash
composer test   # PHPUnit con SQLite :memory:, cola sincronizada
```

La suite cubre: autenticación, CRUD con papelera (trash/restore/force-delete), traductor (27 letras + casos límite), flujo de pedidos (costos, estados, rechazo), catálogo público y seguridad (5 tests).

## Salida del G-Code

Los archivos `.gcode` se generan y almacenan en el disco público (`storage/app/public/pedidos/gcode/`). La impresión es **air-gapped**: el G-Code se transfiere manualmente a la impresora vía SD/USB — la máquina CNC no tiene conexión de red.
