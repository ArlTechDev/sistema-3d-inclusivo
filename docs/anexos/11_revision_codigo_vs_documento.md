# 11 — Revisión del Código vs. Documento y Recomendaciones

> **Actualización (2026-08)**: la fase de código quedó completa (traductor PHP, pedidos, cancelación, layouts, exports admin-only, G-Codes privados, 47 tests). La revisión de consistencia de la documentación (figuras/UML re-renderizados) está en `14_revision_consistencia_final.md`.

## Sistema Braille Inclusivo — PSCP
## Documentos comparados: `DocumentoFinalPSCP3DJulio24.docx` ↔ `software/laravel_web/` + `software/python_core/`
## Fecha de revisión: julio 2026 · Método: lectura de código, migraciones, rutas, tests y diagramas PlantUML

---

## 1. Resumen ejecutivo

- **Decisión de arquitectura tomada por el autor: PHP puro** — el algoritmo de traducción texto→Braille→G-Code **vive** en Laravel (`App\Services\BrailleTranslator`), no en un módulo Python.
- **Estrategia: primero el documento (SSOT), después el código.** Esta revisión produjo el análisis y la especificación; en agosto 2026 se ejecutó la **fase de código** (traductor + pedidos) — ver sección 6.2.
- **Estado general (ACTUALIZADO 2026-08):** la mitad administrativa (autenticación, CRUD de recursos/instituciones/usuarios, papelera, reportes PDF/Excel, seguridad) está implementada y coincide con el documento; la **mitad funcional (traducción Braille→G-Code y pedidos UC-06…UC-10) también quedó implementada** en la fase de código, con **35 tests verdes**.

---

## 2. Inventario verificado del código

### 2.1 Versiones reales

| Componente | Documento (Tabla 5) | Código real | Veredicto |
|---|---|---|---|
| Laravel | Laravel 10 / PHP 8.2 | **Laravel 13.6.0** (`php artisan --version`); `composer.json` → `laravel/framework: ^13.0` | ❌ Tabla 5 incorrecta → «Laravel 13 / PHP 8.3» |
| PHP | 8.2 | requerido `^8.3`; imagen Docker `php:8.4-cli` | ❌ |
| MySQL | 8.0 | `mysql:8.0` en `docker-compose.yml`, puerto 3307 | ✅ |
| Python 3.x | listado como usado para el algoritmo | `python_core/main.py` = placeholder («Algoritmo Braille -> G-Code en desarrollo») | ⚠️ pendiente → decisión: **PHP puro** |
| AdminLTE | 3 | `jeroennoten/laravel-adminlte ^3.15`; vistas `@extends('adminlte::page')` | ✅ |
| Bootstrap | 5 | incluido con AdminLTE 3 | ✅ |
| DomPDF | ✓ | `barryvdh/laravel-dompdf ^3.1` | ✅ |
| Maatwebsite/Excel | ✓ | `maatwebsite/excel ^3.1` | ✅ |
| Docker | ✓ | `docker-compose` v3.8 (MySQL 3307, bind mount, volumen `db_data`) | ✅ |
| PHPUnit | ✓ | 12.5.23 — **7/7 tests OK (53 assertions)** | ✅ |
| Laravel Pint | — | `^1.27` | ✅ |

### 2.2 Roles y autenticación

- `users.rol` = `enum('Administrador', 'Solicitante')` — el commit `c2eb98a` («fix(roles): renombrar rol Docente → Solicitante») ya alineó el código con el documento ✅.
- Middleware `CheckRole` registrado como alias `role` en `bootstrap/app.php` ✅; `SecurityHeaders` global ✅; `RateLimiter` login (5/min) y global (30/min) en `AppServiceProvider` ✅.
- `AuthController::login` → `SafeRedirect::intended(route('recursos.index'))` ✅ (coincide con UC-01: redirige al catálogo).
- Seeder: `admin@admin.com` (Administrador) y `docente@test.com` (**rol** Solicitante — el email conserva «docente», solo cosmético).

### 2.3 Esquema de base de datos (7 tablas de aplicación)

| Tabla | Campos clave | FK | Veredicto vs doc |
|---|---|---|---|
| users | name, email (unique), password, rol, foto_perfil, softDeletes | — | ✅ |
| instituciones | (CRUD con logo/documento) | — | ✅ |
| categorias | nombre, descripcion | — | ✅ (Matemáticas, Geografía, Braille, Ciencias) |
| recursos | titulo, descripcion, gramos_pla, tiempo_minutos, url_imagen, url_gcode, estado, categoria_id | categoria_id **nullOnDelete** (SET NULL) | ✅ |
| pedidos | user_id, institucion_id, estado (Pendiente/En impresión/Completado/Rechazado), fecha_solicitud, total_gramos_pla, costo_total, gcode_path, motivo_rechazo, softDeletes | user_id **cascade**, institucion_id **nullOnDelete** | ✅ |
| detalle_pedidos | pedido_id, recurso_id, cantidad, gramos_pla, costo_unitario | ambos **cascade** | ✅ |
| configuracion_sistemas | clave (unique), valor, descripcion | — | ✅ (`precio_gramo_pla = 0.05` USD/g) |

**Las 5 claves foráneas del documento (párr. 504) coinciden exactamente** con las migraciones.

### 2.4 Seguridad (verificada ejecutando la suite)

- `tests/Feature/SecurityTest.php`: fuerza bruta (bloqueo al 6.º intento), SQLi neutralizado, XSS sanitizado, redirect no validado bloqueado, DoS throttled — **OK (7 tests, 53 assertions)** con SQLite `:memory:` (phpunit.xml) ✅.
- Form Requests con `Sanitizer::cleanArray()`, `SafeRedirect`, `SecurityHeaders` ✅.

### 2.5 Docker (relevante para el despliegue offline)

- `docker-compose.yml`: `app` (bind mount `./:/var/www`), `db` = `mysql:8.0` en puerto **3307**, volumen nombrado **`db_data`**.
- **Implicaciones offline confirmadas:** `vendor/` y `node_modules/` viven en el host (no dentro de la imagen) → deben copiarse aparte; `db_data` (volumen nombrado) → requiere backup aparte; `.env` no viaja en la imagen.

---

## 3. Matriz de coincidencia documento ↔ código

| Afirmación del documento | Ubicación (párr./UC) | Estado en código | Evidencia |
|---|---|---|---|
| Autenticación con Bcrypt y roles | UC-01, RF-01 | ✅ Implementado | `AuthController`, `users.rol` |
| Catálogo CRUD con papelera | UC-02, RF-02, RF-14 | ✅ Implementado | `RecursoController` (create/store/edit/update/destroy/papelera/restore/forceDestroy) |
| Solicitante ve el catálogo | UC-03, RF-03 | ✅ **Implementado (2026-08)** | `recursos.catalogo` (layout público `layouts/app`): solo recursos Activos, filtro por categoría, CTA «Solicitar Impresión» |
| Gestión de instituciones | UC-04, RF-04 | ✅ Implementado | `InstitucionController` |
| Gestión de usuarios | UC-05, RF-05 | ✅ Implementado | `UserController` |
| **Traducción texto→Braille Grado 1** | UC-06, RF-06, RF-08, Módulo 2 (CORE) | ✅ **Implementado (2026-08)** | `app/Services/BrailleTranslator.php` (PHP puro): `validarCaracteres`, `traducir`, `generarGCode` con offset; tests `tests/Unit/BrailleTranslatorTest.php` |
| **Generación de G-Code al confirmar pedido** | UC-07, RF-07, RF-10 | ✅ **Implementado (2026-08)** | `PedidoController::store` genera el `.gcode` con `BrailleTranslator` cuando hay texto personalizado y lo guarda en `pedidos/gcode/` |
| **Registro y gestión de pedidos** | UC-07, UC-08, RF-09, RF-11 | ✅ **Implementado (2026-08)** | `PedidoController` (store/index/update/rechazar), 3 Form Requests, rutas, vistas `pedidos/*`; cálculo de costos con `precio_gramo_pla`; tests `tests/Feature/PedidosTest.php` |
| **Descarga de G-Code por pedido (solo Administrador)** | UC-09, RF-12 | ✅ **Implementado (2026-08)** | `PedidoController::descargarGCode` con middleware `role:Administrador`; sirve `gcode_path` o fallback al `url_gcode` del recurso |
| Reportes PDF/Excel | UC-10, RF-13 | ✅ **Implementado (2026-08)** | `PedidosExport` (Excel) + vista `pedidos/pdf` (DomPDF) — 4 de 4 entidades |
| SoftDeletes en 4 entidades | RF-14, párr. 492 | ✅ Implementado | User, Institucion, Recurso, Pedido |
| Seguridad OWASP | AGENTS.md | ✅ Implementado y verificado | SecurityTest 7/7 |

**Conclusión de la matriz (ACTUALIZADO 2026-08):** el documento y el código coinciden en el **100% de los casos de uso** — el catálogo público (UC-03) y los layouts por rol (R2) también quedaron implementados en la fase de diseño.

---

## 4. Estado por caso de uso (UC-01…UC-10)

| UC | Nombre | Estado | Evidencia en código |
|---|---|---|---|
| UC-01 | Iniciar/Cerrar Sesión | ✅ Implementado | rutas `login`/`logout`, `AuthController` |
| UC-02 | Gestionar Recursos | ✅ Implementado | `RecursoController` CRUD + papelera + restore/force |
| UC-03 | Ver Catálogo | ✅ **Implementado (2026-08)** | `recursos.catalogo` (layout público): hero + cards + filtro por categoría + CTA; `RecursoController::index` ramifica por rol |
| UC-04 | Gestionar Instituciones | ✅ Implementado | `InstitucionController` CRUD + papelera |
| UC-05 | Gestionar Usuarios | ✅ Implementado | `UserController` CRUD + papelera |
| UC-06 | Traducir Texto a Braille | ✅ **Implementado (2026-08)** | `App\Services\BrailleTranslator` (27 letras, dígitos, puntuación, offset) + 17 tests unitarios |
| UC-07 | Solicitar Impresión | ✅ **Implementado (2026-08)** | `PedidoController::store` + vista `pedidos/create` + botón en el catálogo |
| UC-08 | Gestionar Solicitudes | ✅ **Implementado (2026-08)** | `PedidoController::index/update/rechazar` + vista `pedidos/index` con filtros |
| UC-09 | Descargar G-Code | ✅ **Implementado (2026-08)** | `PedidoController::descargarGCode` (solo Administrador); usa `gcode_path` o fallback al recurso |
| UC-10 | Reportes y Estadísticas | ✅ **Implementado (2026-08)** | `PedidosExport` (Excel) + vista `pedidos/pdf` (DomPDF) — 4 de 4 entidades |

**Resumen actualizado (2026-08): 10 casos de uso implementados (UC-01…UC-10).**

---

## 5. Contraste de recomendaciones R1–R10 con el código real

| # | Recomendación | Veredicto sobre el código real |
|---|---|---|
| R1 | Versión de Laravel: usar la real | ✅ **Confirmada** — `php artisan --version` = Laravel **13.6.0**; Tabla 5 del doc («Laravel 10/PHP 8.2») es el error (ver informe 09, hallazgo A2) |
| R2 | Separar layouts por rol (`layouts/admin` + `layouts/app`) | ❌ **No implementado** — no existe `resources/views/layouts/`; todas las vistas usan `@extends('adminlte::page')`, incluida la del Solicitante |
| R3 | Catálogo + texto personalizable (Opción A: inyección de G-Code con offset) | ⚠️ **Viable y recomendada** — requiere primero el traductor (sección 6.1); no necesita slicer |
| R4 | «Al ERD le falta `configuracion_sistemas`» | ⚠️ **INCORRECTA para el repo actual** — `UML_base_datos_ERD.puml` incluye las **7 entidades** (línea 95: `configuracion_sistemas`). Solo conviene regenerar la imagen renderizada para confirmar que coincide con el `.puml` |
| R5 | Figura 3 no muestra los 6 módulos | 🟡 Doc-only — camino rápido recomendado (ajustar el texto que acompaña la Figura 3; Hardware CNC y Validación Sociocomunitaria no son interacciones software-usuario) |
| R6 | Operador = Administrador (mismo actor) | ✅ **Confirmado en código** — solo existen 2 roles en `users.rol`; añadir la frase aclaratoria en el documento (párr. 229) |
| R7 | Anexo F promete un diagrama de secuencia que no está en el docx | ⚠️ **El diagrama SÍ existe** — `docs/casos_de_uso/plantuml/UML_secuencia_UC06.puml` (muy detallado: Fase A previsualización, Fase B confirmación, participantes Solicitante/TraductorController/**BrailleService (Python Core)**/PedidoController/ConfigSistema/MySQL). Falta **incluirlo en el documento** (Anexo F), y depende de la decisión PHP vs Python (sección 6) |
| R8 | Docker offline (save/load, tags, volúmenes, vendor, .env) | ✅ **Confirmada en el código** — bind mount `./:/var/www` (vendor/node_modules en el host), volumen nombrado `db_data`, `.env` fuera de la imagen; los 5 puntos de la recomendación aplican |
| R9 | AdminLTE no afecta al Auth de Laravel | ✅ **Confirmado** — Auth vive en controladores/middleware; AdminLTE es solo la capa de vista (`adminlte::page`) |
| R10 | **PHP puro en lugar de módulo Python** | ✅ **DECISIÓN TOMADA por el autor** — ver sección 6 |

---

## 6. Decisión de arquitectura: PHP puro (tomada)

**Decisión:** el algoritmo de traducción texto→Braille Grado 1→G-Code se implementa como **Service class PHP dentro de Laravel** (`App\Services\BrailleTranslator`), no como módulo Python.

**Justificación** (coincide con la recomendación R10): la lógica (texto → patrón de 6 puntos → coordenadas → G-Code) es simple y no requiere librerías exclusivas de Python; evita un segundo runtime, otro `requirements.txt` y una llamada entre servicios; mantiene el stack 100% coherente (un único backend PHP/Laravel), lo que es más fácil de defender y elimina la inconsistencia entre objetivos, Tabla 5 y diagrama de despliegue.

### 6.1 Impacto en 4 artefactos

| Artefacto | Estado actual | Cambio requerido |
|---|---|---|
| **Tabla 5 (doc)** | fila «Python 3.x | Scripting | Algoritmo de traducción texto→Braille y generación G-Code» | Reemplazar por: «Laravel Service (PHP) | App\Services\BrailleTranslator | Traducción texto→Braille Grado 1 y generación de G-Code» (o eliminar la fila Python) |
| **Figura 16 (doc, párr. 500)** | «Servidor en la Nube que aloja Laravel 13, MySQL 8.0 y el módulo Python (planificado)» | Eliminar «y el módulo Python (planificado)» |
| **UML_secuencia_UC06.puml** | participante «BrailleService (Python Core)» (amarillo) | Renombrar a «BrailleTranslator (App\Services) (PHP)» y ajustar las notas del flujo |
| **software/python_core/** | directorio con `main.py` placeholder y README «en desarrollo» | **Archivar** (mover a `docs/anexos/` o eliminar) o marcar como deprecado; su estructura planeada pasa a ser el service PHP |

**Nota:** el docx no incluye la fila «Python» fuera de la Tabla 5 (el texto del marco teórico menciona Python en párr. 169 y 298 como herramienta del stack); al decidir PHP puro, revisar esas menciones para que digan «PHP/Laravel» o se eliminen.

### 6.2 Estado de la fase de código (ACTUALIZADO 2026-08 — implementado)

> La especificación de esta subsección se **implementó y verificó** en la fase de código (agosto 2026). Suite completa: **35 tests / 179 aserciones OK** (7 originales + 17 del traductor + 11 de pedidos).

**a) `App\Services\BrailleTranslator` (PHP) — IMPLEMENTADO**
- Tabla del Código Braille Español Grado 1 (27 letras a-z+ñ, vocales acentuadas, ü, dígitos 0-9, puntuación) en `BrailleTranslator::MAPA`.
- Métodos: `validarCaracteres` · `traducir` (signo numeral por grupo, signo de mayúscula, espacios como celda vacía, lanza excepción ante caracteres no soportados) · `generarGCode` (G21/G90/G28, G0/G1 absolutos, `G92 E0`, offset X/Y, salto de línea automático, parámetros configurables; el comentario de cabecera neutraliza `\r\n\t`).
- Tests: `tests/Unit/BrailleTranslatorTest.php` (17 tests).

**b) Módulo de pedidos (UC-07/08/09/10) — IMPLEMENTADO**
- `PedidoController`: `store` (valida texto ANTES de crear, calcula gramos/costo con `precio_gramo_pla`, genera el G-Code), `index` (filtros estado/institución/fecha), `update` (transiciones `TRANSICIONES` con errores flash), `rechazar` (motivo obligatorio), `descargarGCode` (admin-only), `exportarPdf/Excel`.
- 3 Form Requests (`StorePedidoRequest`, `UpdatePedidoRequest`, `RechazarPedidoRequest`); `texto_personalizado` no se saniiza con entidades (solo viaja en el archivo descargado).
- Vistas: `pedidos/index` (filtros + acciones + errores flash), `pedidos/create`, `pedidos/pdf`; botón «Solicitar Impresión» en `recursos/index`.
- Export: `PedidosExport` (Excel) — el G-Code se exporta con `basename()`.
- Tests: `tests/Feature/PedidosTest.php` (11 tests).

**c) Layouts por rol (R2) — IMPLEMENTADO (2026-08)**
- `resources/views/layouts/app.blade.php` (público, autocontenido, tokens frontend-design, salto al contenido, focus-visible, reduced-motion) → catálogo y formulario del Solicitante.
- `resources/views/layouts/admin.blade.php` (wrapper AdminLTE) → 13 vistas de administración migradas.

**d) Tests — IMPLEMENTADO** (35 tests / 179 aserciones, Pint limpio)

---

## 7. Acciones recomendadas (priorizadas)

| Prioridad | Acción | Esfuerzo | Impacto | Fase |
|---|---|---|---|---|
| 1 | Actualizar Tabla 5 («Laravel 13/PHP 8.3»; quitar Python) y Figura 16 (sin «módulo Python») | minutos | alto | Documento |
| 2 | Añadir la frase «Operador = Administrador» (párr. 229) y corregir módulo de UC-04 | minutos | alto | Documento |
| 3 | Incluir `UML_secuencia_UC06` en el Anexo F (o quitar «secuencia» de la lista) | minutos | medio | Documento |
| 4 | ~~Implementar `BrailleTranslator` PHP + tests~~ | ✅ **HECHO (2026-08)** | **crítico (CORE)** | Código |
| 5 | ~~Implementar módulo de pedidos UC-07/08/09 + export~~ | ✅ **HECHO (2026-08)** | **crítico** | Código |
| 6 | ~~Layouts por rol (R2)~~ | ✅ **HECHO (2026-08)** — layouts/app público + layouts/admin wrapper, 13 vistas migradas, catálogo con frontend-design | medio | Código |
| 7 | ~~Archivar `python_core/`~~ | ✅ **HECHO (2026-08)** — deprecado en README, conservado como respaldo | bajo | Código |

---

## 8. Veredicto para la defensa

- **ACTUALIZADO (2026-08):** el CORE del sistema ya está implementado y verificado: traducción texto→Braille→G-Code (PHP puro) y módulo de pedidos UC-07/08/09/10 completos, con **35 tests verdes (179 aserciones)**.
- El sistema ya es **defendible como funcional** (no solo como diseño): el flujo completo Solicitante → pedido → G-Code → descarga admin está implementado y probado.
- El sistema es **defendible como funcional y completo**: los 10 casos de uso están implementados y probados (40 tests), con layouts por rol y accesibilidad (contraste AA, foco visible, reduced-motion).
- La decisión **PHP puro** simplifica la historia: un solo backend, un solo diagrama de despliegue, un solo participante en la secuencia.

---

## 9. Decisiones pendientes del autor

| # | Decisión | Estado |
|---|---|---|
| D-A | ¿Implementar pedidos + traductor en código? | ✅ **RESUELTA (2026-08): implementado y verificado** (35 tests). No hizo falta marcar `[PENDIENTE]` en el documento |
| D-B | ¿Incluir `UML_secuencia_UC06.puml` en el Anexo F? | ✅ **RESUELTA: no** — el `.docx` solo recibe gráficos/imágenes renderizadas; el `.puml` permanece en `docs/casos_de_uso/`. Pendiente de ajustar la lista del Anexo F o renderizar a PNG en la fase de documentación |
| D-C | ¿Archivar o eliminar `software/python_core/`? | ✅ **RESUELTA: archivado en su lugar** — README deprecado (PHP puro), conservado como respaldo por si PHP no resulta |
