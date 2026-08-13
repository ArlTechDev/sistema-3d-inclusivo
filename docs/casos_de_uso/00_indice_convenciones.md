# Índice y Convenciones de Casos de Uso

## Sistema Web y Electromecánico de Impresión 3D
### Proyecto Sociocomunitario Productivo — Instituto Técnico "Federico Alvarez Plata"

---

## Índice de Casos de Uso

| UC | Nombre del Caso de Uso | Módulo Tesis | Actor Principal | Archivo PlantUML |
|---|---|---|---|---|
| UC-00 | Diagrama General del Sistema | — | Ambos | `UC00_diagrama_general.puml` |
| UC-01 | Iniciar / Cerrar Sesión | Módulo 1 | Ambos | `UC01_autenticacion.puml` |
| UC-02 | Gestionar Catálogo de Recursos | Módulo 4 | Administrador | `UC02_gestionar_recursos.puml` |
| UC-03 | Ver Catálogo de Recursos | Módulo 4 | Solicitante | `UC03_ver_catalogo.puml` |
| UC-04 | Gestionar Instituciones | Módulo 4 | Administrador | `UC04_gestionar_instituciones.puml` |
| UC-05 | Gestionar Usuarios | Módulo 1 | Administrador | `UC05_gestionar_usuarios.puml` |
| UC-06 | Traducir Texto a Braille | Módulo 2 | Solicitante | `UC06_traducir_braille.puml` |
| UC-07 | Solicitar Impresión | Módulo 3 | Solicitante | `UC07_solicitar_impresion.puml` |
| UC-08 | Gestionar Solicitudes | Módulo 3 | Administrador | `UC08_gestionar_solicitudes.puml` |
| UC-09 | Descargar G-Code | Módulo 3/4 | Administrador | `UC09_descargar_gcode.puml` |
| UC-10 | Generar Reportes y Estadísticas | Módulo 3 | Administrador | `UC10_reportes_estadisticas.puml` |

---

## Trazabilidad con Objetivos Específicos de la Tesis

| Objetivo Específico | UC asociados |
|---|---|
| OE-1: Módulo de gestión de usuarios (roles diferenciados) | UC-01, UC-05 |
| OE-2: Módulo de traducción texto→Braille y generación de Código G | UC-06, UC-09 |
| OE-3: Módulo de gestión de pedidos y costos de producción | UC-07, UC-08, UC-10 |
| OE-4: Catálogo digital de producción educativa táctil | UC-02, UC-03, UC-04 |
| OE-5: Hardware CNC electromecánico | No aplica (hardware físico) |
| OE-6: Validación sociocomunitaria | No aplica (pruebas de campo) |

---

## Trazabilidad con Módulos del Sistema

| Módulo | Descripción | UC asociados |
|---|---|---|
| Módulo 1 | Gestión de Usuarios | UC-01, UC-05 |
| Módulo 2 | Traducción Automática Texto→Braille y Generación de Código G | UC-06 |
| Módulo 3 | Gestión de Pedidos y Costos de Producción | UC-07, UC-08, UC-10 |
| Módulo 4 | Catálogo Digital de Producción Educativa Táctil | UC-02, UC-03, UC-04 |
| Módulo 5 | Hardware CNC Electromecánico | — (no es software) |
| Módulo 6 | Validación Sociocomunitaria | — (pruebas de campo) |

---

## Convenciones de Documentación

### Actores

| Actor | Descripción | Icono UML |
|---|---|---|
| Solicitante | Docente, directivo o tutor de institución educativa | Monigote humano |
| Administrador | Operador del sistema (equipo de desarrollo) | Monigote humano |
| Sistema | Procesamiento automático del backend | Actor secundario |

### Estados de Pedido

```
[Pendiente] ──► [En impresión] ──► [Completado]
     │
     └──► [Rechazado]
```

### Tipos de Relaciones UML

| Relación | Símbolo | Uso |
|---|---|---|
| Asociación | Línea sólida | Actor → Caso de Uso |
| Include | `<<include>>` | Paso obligatorio dentro del CU |
| Extend | `<<extend>>` | Paso opcional / condicional |
| Generalización | Flecha vacía | Herencia entre actores |

### Precondiciones Comunes

1. El usuario ha iniciado sesión (excepto UC-01).
2. El sistema dispone de conexión a internet.
3. El usuario tiene el rol adecuado para la operación.

### Archivos

- Cada UC tiene su archivo `.puml` en `docs/casos_de_uso/plantuml/`.
- Las imágenes exportadas se guardan en `docs/casos_de_uso/imagenes/`.
- Los diagramas se generan con PlantUML: `java -jar plantuml.jar *.puml`

---

## Convenciones UML 2.5 (diagramas académicos, B/N)

> Aplicadas el 2026-08 tras la observación «esto no es UML» (ver `diagnostico_uml.md`).

| Elemento | Notación exigida |
|---|---|
| Actor | Monigote clásico (*stick figure*), sin `actorStyle awesome`, sin colores |
| Caso de uso | Óvalo con nombre en frase verbal |
| Frontera del sistema | Rectángulo simple «Sistema Braille Inclusivo» |
| Asociación actor → CU | Línea sólida |
| `<<include>>` | Línea discontinua **desde el CU base hacia el CU incluido** |
| `<<extend>>` | Línea discontinua **desde el CU que extiende hacia el CU base** (punta en la base) |
| Generalización | Línea sólida con triángulo vacío hacia el elemento general |
| Sistema como actor | Prohibido |
| Escenarios/notas | Fuera del diagrama → van en la especificación escrita de este índice |
| Estilo | B/N (`skinparam monochrome true`), sin sombras, sin paquetes decorativos |

Reglas verificadas en todos los `.puml`:
- Dirección de `<<extend>>` corregida (antes invertida en 7 archivos).
- `actor "Sistema"` eliminado de UC-06/07/08.
- Notas con «Precondiciones/Camino principal/Flujo» extraídas de 8 diagramas.
- ERD y Gantt se etiquetan como Diagrama Entidad-Relación y Diagrama de Gantt (no-UML).
- Imágenes: PNG + SVG regenerados con `plantuml -tpng/-tsvg`.

### Generación de imágenes

```bash
cd docs/casos_de_uso/plantuml
plantuml -tpng -o <destino> *.puml   # o -tsvg
```
