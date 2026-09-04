---
name: laravel-conventions
description: Convenciones del proyecto Laravel (naming, Form Requests + Sanitizer, trash/restore, rutas antes de resource, roles, UI en español, tests SQLite, commits semánticos).
---

# Skill: laravel-conventions — Convenciones del proyecto Laravel

## Propósito
Reglas que TODO código nuevo en `software/laravel_web/` debe seguir. El proyecto es Laravel 13 / PHP ^8.3 (imagen Docker 8.4), MySQL 8.0 (puerto 3307), AdminLTE 3 + Bootstrap 5.

## Convenciones obligatorias

### Estructura y naming
- Controladores: singular + sufijo `Controller` (ej. `RecursoController`), en `app/Http/Controllers/`.
- Validación: **Form Requests** en `app/Http/Requests/` (ej. `StoreRecursoRequest`, `UpdateRecursoRequest`), con sanitización en `prepareForValidation()` vía `Sanitizer::cleanArray()`.
- Modelos: singular PascalCase (ej. `Pedido`, `ConfiguracionSistema`), con `$fillable`, relaciones Eloquent y `SoftDeletes` donde aplique.
- Servicios: `App\Services\` (ej. `BrailleTranslator`).
- Exports: `app/Exports/*Export.php` (Maatwebsite/Excel) + vistas `pdf.blade.php` (DomPDF).

### Rutas (`routes/web.php`)
- Rutas personalizadas (papelera, restore, force, exportar) **ANTES** de `Route::resource()`.
- Middleware `role:Administrador` en rutas de administración (papelera, gestión, descarga de G-Code).
- Autenticación: `throttle:login` en el POST de login, `throttle:global` en el GET.

### Roles y seguridad
- Roles: solo `Administrador` y `Solicitante` (columna `users.rol`). El "Operador" es el Administrador en contexto físico (fuera del navegador) — no crear un tercer rol.
- El Solicitante NUNCA descarga G-Code ni accede a papelera.
- Seguridad OWASP: SecurityHeaders (global), RateLimiter login (5/min) y global (30/min), SafeRedirect::intended(), Sanitizer anti-XSS.

### Patrón papelera (trash/restore)
- Todas las entidades principales: SoftDeletes + `papelera()` + `restore($id)` + `forceDestroy($id)`, con vistas `index/papelera`.

### UI
- Todo en **español** (vistas, mensajes de validación, placeholders).
- **Layouts por rol**: `resources/views/layouts/app.blade.php` (público, autocontenido, tokens CSS con `:root`, celda Braille como marca, salto al contenido, `:focus-visible`, `prefers-reduced-motion`) → vistas del Solicitante (catálogo, formulario de pedido). `resources/views/layouts/admin.blade.php` (wrapper de `adminlte::page` que re-expone `title`/`content_header`/`content` vía `@yield`) → vistas de administración. Las vistas admin solo cambian el `@extends('layouts.admin')`.

### Tests y calidad
- PHPUnit con SQLite `:memory:` (phpunit.xml); cola sincronizada. Ejecutar `composer test` desde `software/laravel_web/`.
- Formatear con Pint: `./vendor/bin/pint`.
- Commits semánticos: `feat(web)`, `feat(api)`, `fix`, `docs`, `chore(infra)`, `test` — en español, mensaje imperativo.

## Fuentes
`AGENTS.md` · `docs/anexos/09_informe_revision_documento_final.md` · `docs/anexos/11_revision_codigo_vs_documento.md`.
