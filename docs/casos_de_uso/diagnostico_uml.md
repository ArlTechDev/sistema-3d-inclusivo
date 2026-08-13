# Diagnóstico UML — «Esto no es UML»

> Fecha: 2026-08 · Proyecto: Sistema Web y Electromecánico de Impresión 3D (Braille Inclusivo)
> Contexto: observación del revisor sobre los diagramas de `docs/casos_de_uso/`. Este documento audita los 17 `.puml` y registra, por archivo, qué viola la notación UML 2.5.

## Resumen

| Problema | Archivos afectados | Verificado |
|---|---|---|
| `actorStyle awesome` + colores + sombras (no académico) | Los 11 UC (UC00…UC10) | `grep -l "actorStyle awesome"` → 11 |
| `<<extend>>` con la flecha **invertida** | UC-00, UC-01, UC-03, UC-05, UC-06, UC-08 (7 archivos) | ver §2 |
| `actor "Sistema"` (el sistema no es actor) | UC-06, UC-07, UC-08 | `grep -n 'actor "Sistema"'` |
| Notas con escenarios dentro del diagrama | UC-01, UC-02, UC-03, UC-04, UC-05, UC-06, UC-08, UC-09 (8 archivos) | `grep -ln "Precondiciones\|Camino principal"` |
| Estereotipo `<<Cloud>>` en un diagrama de casos de uso | UC-00 | `grep -n "Cloud"` |
| Paquetes/empaquetado interno decorativo | UC-00 | `grep -n "package "` |

## 1. Estilo visual no académico (11 archivos)

Todos los diagramas de casos de uso usan `skinparam actorStyle awesome`, colores por actor/caso de uso, `backgroundColor #FEFEFE` y en algunos casos `shadowing`. UML académico exige actores *stick figure* clásicos, B/N y sin sombras.

## 2. Dirección de `<<extend>>` invertida (7 archivos)

En UML 2.5, la flecha `<<extend>>` va **desde el caso de uso que extiende hacia el caso de uso base** (la punta apunta a la base). En PlantUML, `A ..> B` dibuja la punta en `B`. Todos los `<<extend>>` actuales usan `base ..> extensión` (punta en la extensión) = **invertido**.

| Archivo | Línea actual | Corrección (extensión → base) |
|---|---|---|
| `UC00_diagrama_general.puml` | `UC06 ..> UC07 : <<extend>>` | `UC07 ..> UC06 : <<extend>>` |
| `UC00_diagrama_general.puml` | `UC07 ..> UC09 : <<extend>>` | `UC09 ..> UC07 : <<extend>>` |
| `UC01_autenticacion.puml` | `UC01a ..> UC01e : <<extend>>` | `UC01e ..> UC01a : <<extend>>` |
| `UC01_autenticacion.puml` | `UC01a ..> UC01d : <<extend>>` | `UC01d ..> UC01a : <<extend>>` |
| `UC03_ver_catalogo.puml` | `UC03a ..> UC03b/c/d : <<extend>>` | `UC03b/c/d ..> UC03a : <<extend>>` |
| `UC05_gestionar_usuarios.puml` | `UC05b ..> UC05k : <<extend>>` | `UC05k ..> UC05b : <<extend>>` |
| `UC06_traducir_braille.puml` | `UC06b ..> UC06e : <<extend>>` | `UC06e ..> UC06b : <<extend>>` |
| `UC08_gestionar_solicitudes.puml` | `UC08c ..> UC08d/e/f : <<extend>>` | `UC08d/e/f ..> UC08c : <<extend>>` |

Los `<<include>>` (base → incluido) están **correctos** en todos los archivos (verificado UC-01…UC-09).

## 3. «Sistema» como actor (UC-06, UC-07, UC-08)

```plantuml
actor "Sistema" as SYS #LightYellow
```

El sistema modelado no puede ser un actor de su propio diagrama. Se elimina; las interacciones con el sistema quedan implícitas en la frontera (rectángulo).

## 4. Escenarios dentro del diagrama (8 archivos)

Notas `note right of …` con «Precondiciones», «Camino principal», «Escenario alternativo», «Flujo principal» están embebidas en UC-01, UC-02, UC-03, UC-04, UC-05, UC-06, UC-08 y UC-09. Ese contenido pertenece a la **especificación escrita** (`00_indice_convenciones.md` y el documento PSCP), no al diagrama. Se extraen.

## 5. ERD y Gantt (no-UML)

`UML_base_datos_ERD.puml` y `Gantt_proyecto.puml` no son UML por definición: son Diagrama Entidad-Relación y Diagrama de Gantt. Sus `title` ya lo dicen correctamente. Se mantienen como tales (sin etiqueta «UML») y solo se les aplica el estilo B/N. Los nombres de archivo **no se renombran** para no romper referencias (anexo 14, imágenes del .docx).

## 6. Resultado esperado

17 diagramas en **UML 2.5 de estilo académico (B/N)**, con la notación corregida y el contenido de especificación fuera de los diagramas. Fuentes `.puml` corregidas en `docs/casos_de_uso/plantuml/`; imágenes PNG+SVG regeneradas en `docs/casos_de_uso/imagenes/` (UC) y `docs/diagramas/imagenes/**` (UML_*).
