# Changelog

Todos los cambios notables de este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/),
y este proyecto sigue [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- README.md completo en la raíz del repositorio
- Diagrama de Gantt del proyecto (9 fases, mayo-septiembre 2026)
- Análisis de inconsistencias (`04_analisis_inconsistencias.md`)
- Plan de corrección detallado (`05_plan_correccion.md`)
- Anexos de contexto sociocomunitario, arquitectura técnica y reglas del sistema
- 11 diagramas de casos de uso (UC-00 a UC-10) con archivos PlantUML
- Diagrama de Clases del Dominio
- Diagrama de Secuencia UC-06/UC-07 (Fase A y Fase B)
- Templates de Issues y Pull Requests en `.github/`

### Changed
- Diagrama de Gantt corregido: 6 fases → 9 fases con fechas exactas
- DocumentoFinal corregido: UEB → Código Braille Español, boquilla 0.8mm, sin LCD/SD
- `.gitignore` actualizado para excluir archivos temporales de Word y vendor

### Removed
- Archivos de `lang/vendor/adminlte/` del tracking (regenerados por Composer)
- Archivos de `public/vendor/` del tracking (regenerados por Artisan)

## [0.1.0] - 2026-05-01

### Added
- Estructura inicial del monorepo (software, hardware, docs)
- Laravel 13 con módulos de Usuarios, Recursos e Instituciones
- Python Core (placeholder para algoritmo Braille)
- Estructura de hardware (CAD, firmware, fotos)
- Documentos Word de tesis (Perfil y Documento Final)
