# AGENTS.md — Sistema Braille Inclusivo (Monorepo)

## Única Fuente de la Verdad (SSOT)
Todos los artefactos del proyecto viven en este repositorio. Si no está en `main`, no existe. No se aceptan códigos, planos o documentos enviados por WhatsApp/Telegram.

## Estructura del Repositorio

```
software/
├── laravel_web/          # Laravel 13 — Plataforma web (AdminLTE, Blade, MySQL)
└── python_core/          # ARCHIVADO — algoritmo movido a PHP (App\Services\BrailleTranslator); conservado como respaldo
hardware/
├── cad_planos/           # FreeCAD (.FCStd) — Chasis, piezas mecánicas
├── marlin_firmware/      # Marlin 1.1.x — Configuration.h, pines, calibración
├── exportaciones_3d/     # Exportaciones STL desde CAD
└── fotos_avance/         # Fotos de progreso de ensamblaje
docs/
├── documento_pscp/       # Documento PSCP (.docx) — aplica protocolo de bloqueo
└── anexos/               # Anexos técnicos 01–11 (contexto, seguridad, revisión doc, borradores, código)
.reasonix/skills/         # Skills de proyecto (braille-gcode, laravel-conventions)
```

## Comandos

### Laravel Web (`software/laravel_web/`)
| Tarea | Comando |
|---|---|
| Instalación inicial | `composer setup` |
| Servidor de desarrollo | `composer dev` |
| Ejecutar tests | `composer test` |
| Análisis estático (PHPStan, nivel 5) | `composer analyse` |
| Formatear código PHP | `./vendor/bin/pint` |
| Compilar frontend | `npm run build` |
| Migrar (Docker) | `docker exec -it laravel_app php artisan migrate` |
| Seed de usuarios | `php artisan db:seed` |

### Python Core (ARCHIVADO — `software/python_core/`)
| Tarea | Comando |
|---|---|
| Estado | **Deprecado** por decisión de arquitectura **PHP puro** (ver `docs/anexos/11_revision_codigo_vs_documento.md` § 6). El algoritmo vive en `app/Services/BrailleTranslator.php` |
| Conservación | Mantener como respaldo por si PHP no resulta; no agregar funcionalidad nueva |

### Hardware
Sin comandos — los artefactos son archivos CAD, configuración de firmware y fotos.

## Configuración Docker (Laravel)
```bash
cd software/laravel_web
cp .env.example .env
docker compose up -d --build
docker exec -it laravel_app php artisan migrate:fresh --seed
```
MySQL expuesto en puerto **3307** (no el 3306 por defecto).

## Roles del Equipo

| Rol | Alcance | Directorio Principal |
|---|---|---|
| **Backend (PHP/Laravel)** | Base de datos, algoritmo Braille→G-Code en PHP, APIs | `software/laravel_web/app/`, `database/` |
| **Frontend/Laravel** | Vistas Blade, AdminLTE, controladores, rutas | `software/laravel_web/app/`, `resources/`, `routes/` |
| **Hardware** | Firmware Marlin, CAD, ensamblaje, calibración | `hardware/` |

## Notas de Arquitectura

### Metodología de Desarrollo (según PSCP)
- **Scrum**: sprints de 2 semanas, backlog priorizado por el equipo, reunión diaria breve y retrospectiva al final de cada sprint.
- **Kanban complementario**: tablero Trello con columnas «Por Hacer», «En Progreso», «Hecho» + «Revisión» y «Bloqueado».
- **Enfoque mixto (cuantitativo-cualitativo)** y paradigma sociocomunitario productivo: encuestas (12 docentes, 8 estudiantes), entrevistas semiestructuradas (3 especialistas IBC, 4 docentes), observación participante y análisis FODA.
- **Pruebas**: suite PHPUnit sobre el traductor (100% de casos) + pruebas de integración; calibración metrológica del hardware (regla patrón 100 mm, calibre ±0.05 mm, repetibilidad G28).

### Laravel Web
- **Versión real**: Laravel 13 / PHP ^8.3 (imagen Docker 8.4). Verificar con `php artisan --version` — no copiar versiones del documento sin comprobar.
- **Roles**: Columna `users.rol` (`Administrador`, `Solicitante`). Middleware `role` en `bootstrap/app.php`.
- **SoftDeletes + papelera**: Todos los modelos principales usan patrón trash/restore/force-delete.
- **Subida de archivos**: Disco `public` → `recursos/images`, `recursos/gcode`.
- **Exportaciones**: DomPDF + Maatwebsite Excel para cada entidad.
- **Idioma**: UI y mensajes de validación en español.
- **Rutas**: Rutas personalizadas ANTES de `Route::resource()` en `web.php`.
- **Layouts por rol**: `resources/views/layouts/app.blade.php` (público, autocontenido, sin AdminLTE) → Solicitante/catálogo/formulario; `resources/views/layouts/admin.blade.php` (wrapper de `adminlte::page`) → vistas de administración. No usar `adminlte::page` directamente en vistas nuevas.

### BrailleTranslator (PHP — decisión de arquitectura)
- **Decisión: PHP puro** (2026-08): el algoritmo vive en `app/Services/BrailleTranslator.php` como Service class de Laravel. `python_core/` quedó archivado.
- Traduce texto → Braille Grado 1 (Código Braille Español/ONCE, sin estenografía) → coordenadas G-Code.
- Métodos: `validarCaracteres()`, `traducir()`, `generarGCode()` con soporte de offset (Opción A de personalización).
- Archivos `.gcode` de salida se almacenan en Laravel (pedido → `gcode_path`) y se transfieren manualmente a la CNC vía SD/USB (air-gapped).
- Tests: `tests/Unit/BrailleTranslatorTest.php` (27 letras, dígitos, puntuación, inválidos, offset).

### Hardware
- **Cinemática**: Prusa i3 cartesiana (correas GT2 X/Y, varillas roscadas Z).
- **Controlador**: Arduino Mega + RAMPS 1.4, Marlin 1.1.x.
- **Extrusor**: MK8 directo, cama fría con adhesivo (laca/cinta azul).
- **Material**: PLA de fuentes recicladas.

## Convenciones

### Commits (Semánticos, obligatorios)
```
feat(web): agregar exportación de recursos a PDF
feat(api): implementar traductor braille-a-gcode
feat(hw): calibrar pasos del extrusor en Marlin
docs: actualizar justificación técnica del documento PSCP
fix: corregir regla de validación para gramos_pla
chore(infra): reestructurar monorepo con Git LFS
```
Prefijos: `feat`, `fix`, `docs`, `chore`, `refactor`, `test`. Alcances: `web`, `api`, `hw`, `infra`.

### Git LFS
Tipos de archivo rastreados: `*.FCStd`, `*.STEP`, `*.stl`, `*.gcode`, `*.docx`, `*.pdf`, `*.png`, `*.jpg`.
Siempre ejecutar `git lfs pull` después de `git pull`.

### Protocolo de Bloqueo de Archivo de Documento PSCP
Solo una persona edita archivos `.docx` a la vez. Anunciar en chat grupal antes de editar, hacer commit inmediatamente después, anunciar cuando termine.

### EditorConfig
Indentación de 4 espacios, finales de línea LF (2 espacios para YAML). Forzado vía `.editorconfig`.

## Testing
- **Laravel**: PHPUnit con SQLite `:memory:`, cola sincronizada. Ejecutar `composer test` desde `software/laravel_web/`.
- **BrailleTranslator**: tests unitarios en `tests/Unit/BrailleTranslatorTest.php` (cobertura 100% alfabeto Grado 1).
- **Hardware**: Validación en 3 fases — cubo de calibración XYZ (20mm) → regla geométrica → hoja de texto Braille.

## Flujo de Trabajo
1. **Explorar** (`/explore`) — investigar el código/documento antes de proponer cambios.
2. **Planear** (modo plan) — presentar plan en fases y esperar aprobación.
3. **Implementar** — cambios pequeños, commit semántico por cada paso.
4. **Revisar** (`/review`, `/security-review`) — antes del cierre de cada fase.
Documentos de referencia: `docs/anexos/09_informe_revision_documento_final.md` (hallazgos), `10_borradores_contenido_documento_final.md` (textos listos para el .docx), `11_revision_codigo_vs_documento.md` (estado código ↔ documento).

## Seguridad (Medidas Preventivas OWASP)

### Componentes de Seguridad Implementados

| Componente | Archivo | Propósito |
|---|---|---|
| SecurityHeaders Middleware | `app/Http/Middleware/SecurityHeaders.php` | Headers `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy` |
| Sanitizer (anti-XSS) | `app/Support/Sanitizer.php` | `strip_tags()` + `htmlspecialchars()` en inputs de texto |
| SafeRedirect | `app/Support/SafeRedirect.php` | Valida que redirecciones `intended()` sean al mismo dominio |
| RateLimiter login | En `AppServiceProvider::boot()` | 5 intentos/min por email+IP (throttle:login) |
| RateLimiter global | En `AppServiceProvider::boot()` | 30 req/min por IP (throttle:global) |
| Form Requests (6) | `app/Http/Requests/*Request.php` | Sanitización en `prepareForValidation()` vía `Sanitizer::cleanArray()` |
| SecurityTest suite | `tests/Feature/SecurityTest.php` | 5 tests PHPUnit (fuerza bruta, SQLi, XSS, DoS, redirects) |

### Vulnerabilidades Cubiertas

1. **SQL Injection** — Protegido por Eloquent ORM (PDO prepared statements)
2. **Fuerza Bruta** — `throttle:login` con `RateLimiter::for('login')` (5 intentos/min)
3. **XSS** — Sanitización en Form Requests + `SecurityHeaders` middleware + escapado Blade
4. **DoS/DDoS** — `throttle:global` con `RateLimiter::for('global')` (30 req/min)
5. **Redirecciones no validadas** — `SafeRedirect::intended()` con validación de dominio

### Ejecutar Tests de Seguridad
```bash
cd software/laravel_web
composer test  # Incluye tests/Feature/SecurityTest.php (5 tests)
```

### Generar Informe PDF
```bash
cd docs/anexos
pandoc 08_informe_seguridad_preventiva.md -o InformeSeguridadPreventiva.pdf --pdf-engine=pdflatex
```

- Traducción Braille: Solo Grado 1 (literal, sin estenografía).
- Sin integración de pasarela de pagos.
- Sin aplicaciones móviles nativas — solo web responsiva.
- Hardware air-gapped; sin conexión de red directa a CNC.
- npm: `.npmrc` establece `ignore-scripts=true`.
