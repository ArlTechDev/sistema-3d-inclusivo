# Changelog

Todos los cambios notables de este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/),
y este proyecto sigue [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-09-03

### Added
- Workflow automatizado de CI en GitHub Actions ([`.github/workflows/ci.yml`](.github/workflows/ci.yml)) con ejecución de tests PHPUnit y análisis estático Larastan.
- 26 referencias bibliográficas rigurosas en formato APA 7 integradas en el documento oficial `DocumentoFinalPSCP3DAgosto17.docx`.
- Inserción de citas metodológicas, normativas y técnicas en el texto del documento (Hernández-Sampieri, Gibson/ISO 52900, OWASP, Ley 070, RM 0487/2023, W3C).
- Estado intermedio `Aprobado` en el ciclo de vida del pedido (`Pedido::TRANSICIONES`: `Pendiente` → `Aprobado` → `En impresión` → `Completado` / `Rechazado`) reflejado en documentación, requisitos y diagramas.
- Script de correcciones deterministas v2 ([`scripts/docx/aplicar_correcciones_v2.py`](scripts/docx/aplicar_correcciones_v2.py)) con garantía de deriva vertical cero (compensated line budgeting).

### Changed
- Presupuesto total del proyecto estandarizado en **~700 Bs. (≈ $100 USD)** de forma coherente en todo el repositorio (README, Tablas 10 y 11 del documento docx, Anexos).
- Relación comparativa frente a equipos comerciales corregida a **≈ 15× más costosa** ($1.495 USD vs $100 USD).
- Diagrama de Despliegue (Figura 16, `UML_despliegue.puml`) actualizado: invocación interna en PHP puro sin nodo intermediario Python.
- Diagrama de Estados (Figura 15, `UML_estados_pedido.puml`) actualizado con estado `Aprobado` y maquetación vertical para preservar proporciones en Word.
- Diagrama ERD (`UML_base_datos_ERD.puml`) actualizado con campos 3D y enum de estados completo.
- Tablas de presupuesto (Tablas 10 y 11) armonizadas para cerrar exactamente en 700 Bs. (100%), incorporando consumibles y reserva operativa sin alterar costos e-waste.
- Referencia cruzada en P[503] corregida de "(Figura 15)" a "(Figura 17)".

### Fixed
- Eliminado contenedor SDT Zotero con ~35 referencias redundantes y duplicadas en el `.docx`.
- Eliminadas 18 ocurrencias de dobles paréntesis tipográficos `((Autor, Año))` → `(Autor, Año)`.
- Corregida cita narrativa mal formada de la OMS en P[135].
- Verificada presencia de la carátula institucional oficial en el contenedor SDT 0 del documento.

## [0.2.0] - 2026-08-15

### Added
- Implementación del algoritmo de traducción texto → Braille Grado 1 (Código Braille Español ONCE) en PHP puro (`App\Services\BrailleTranslator`).
- Suite de 85 pruebas automatizadas (368 aserciones) en PHPUnit con cobertura total del alfabeto Grado 1 y máquina de estados.
- Endurecimiento de seguridad OWASP Top 10: middleware `SecurityHeaders`, `Sanitizer` anti-XSS, `SafeRedirect` y rate limiting en `AppServiceProvider`.
- Arquitectura de vistas dual: `layouts/app.blade.php` (público accesible) y `layouts/admin.blade.php` (panel AdminLTE).
- Módulo de Pedidos con flujo de estados, cálculo automático de consumo de filamento PLA y costos de producción.
- Soporte de campos 3D para previsualización en catálogo digital (`archivo_stl`, `archivo_glb`).

### Changed
- Deprecado `software/python_core/` archivándolo como respaldo; lógica central consolidada en Laravel.
- Configuración de base de datos MySQL en puerto 3307 para entorno Docker.

## [0.1.0] - 2026-05-01

### Added
- Estructura inicial del monorepo (software, hardware, docs).
- Laravel 13 con módulos de Usuarios, Recursos e Instituciones.
- Python Core (placeholder inicial para algoritmo Braille).
- Estructura de hardware (CAD, firmware Marlin 1.1.x, fotos).
- Documentos Word preliminares de proyecto (Perfil y Documento Final).
