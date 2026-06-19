# AGENTS.md — Sistema Braille Inclusivo (Monorepo)

## Única Fuente de la Verdad (SSOT)
Todos los artefactos del proyecto viven en este repositorio. Si no está en `main`, no existe. No se aceptan códigos, planos o documentos enviados por WhatsApp/Telegram.

## Estructura del Repositorio

```
software/
├── laravel_web/          # Laravel 13 — Plataforma web (AdminLTE, Blade, MySQL)
└── python_core/          # Python 3 — Algoritmo de generación de G-Code
hardware/
├── cad_planos/           # FreeCAD (.FCStd) — Chasis, piezas mecánicas
├── marlin_firmware/      # Marlin 1.1.x — Configuration.h, pines, calibración
├── exportaciones_3d/     # Exportaciones STL desde CAD
└── fotos_avance/         # Fotos de progreso de ensamblaje
docs/
├── documento_pscp/       # Documento PSCP (.docx) — aplica protocolo de bloqueo
└── anexos/               # Anexos técnicos, CONTEXTO_TECNICO.md
```

## Comandos

### Laravel Web (`software/laravel_web/`)
| Tarea | Comando |
|---|---|
| Instalación inicial | `composer setup` |
| Servidor de desarrollo | `composer dev` |
| Ejecutar tests | `composer test` |
| Formatear código PHP | `./vendor/bin/pint` |
| Compilar frontend | `npm run build` |
| Migrar (Docker) | `docker exec -it laravel_app php artisan migrate` |
| Seed de usuarios | `php artisan db:seed` |

### Python Core (`software/python_core/`)
| Tarea | Comando |
|---|---|
| Crear entorno virtual | `python -m venv venv && source venv/bin/activate` |
| Instalar dependencias | `pip install -r requirements.txt` |
| Ejecutar algoritmo | `python main.py` (placeholder — actualizar cuando se implemente) |

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
| **Backend/Python** | Base de datos, algoritmo Python G-Code, APIs | `software/python_core/`, `software/laravel_web/database/` |
| **Frontend/Laravel** | Vistas Blade, AdminLTE, controladores, rutas | `software/laravel_web/app/`, `resources/`, `routes/` |
| **Hardware** | Firmware Marlin, CAD, ensamblaje, calibración | `hardware/` |

## Notas de Arquitectura

### Laravel Web
- **Roles**: Columna `users.rol` (`Administrador`, `Docente`). Middleware `role` en `bootstrap/app.php`.
- **SoftDeletes + papelera**: Todos los modelos principales usan patrón trash/restore/force-delete.
- **Subida de archivos**: Disco `public` → `recursos/images`, `recursos/gcode`.
- **Exportaciones**: DomPDF + Maatwebsite Excel para cada entidad.
- **Idioma**: UI y mensajes de validación en español.
- **Rutas**: Rutas personalizadas ANTES de `Route::resource()` en `web.php`.

### Python Core (planificado)
- Traduce texto → Braille Grado 1 → coordenadas G-Code.
- Archivos `.gcode` de salida se almacenan en Laravel vía subida o ruta compartida.
- Ejecución air-gapped: G-Code transferido manualmente a CNC vía SD/USB.

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
- **Python**: Agregar tests `pytest` en `software/python_core/tests/` cuando se implemente el algoritmo.
- **Hardware**: Validación en 3 fases — cubo de calibración XYZ (20mm) → regla geométrica → hoja de texto Braille.

## Restricciones Clave
- Traducción Braille: Solo Grado 1 (literal, sin estenografía).
- Sin integración de pasarela de pagos.
- Sin aplicaciones móviles nativas — solo web responsiva.
- Hardware air-gapped; sin conexión de red directa a CNC.
- npm: `.npmrc` establece `ignore-scripts=true`.
