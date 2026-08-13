# 14 — Revisión de Consistencia de la Documentación Final

Revisión integral (2026-08) del paquete documental del proyecto para garantizar consistencia entre el documento PSCP (`DocumentoFinalPSCP3DJulio24.docx`), el código implementado y los diagramas/UML. Complementa y actualiza los anexos 09 (revisión del documento), 10 (borradores de contenido) y 11 (código ↔ documento).

## 1. Inventario de la documentación existente

| Ruta | Contenido | Estado |
|---|---|---|
| `docs/documento_pscp/DocumentoFinalPSCP3DJulio24.docx` | Documento PSCP completo (17 figuras, 13 tablas) | **Pendiente de aplicar cambios** (lo edita el autor) |
| `docs/anexos/01…08` | Contexto, arquitectura, seguridad, análisis de inconsistencias, plan de corrección, guía Word | Actualizados (02 con stack PHP corregido) |
| `docs/anexos/09_informe_revision_documento_final.md` | Hallazgos C1–C4, A1–A5, M1–M10, B1–B8 y decisiones D1–D7 | Vigente; ver §2 |
| `docs/anexos/10_borradores_contenido_documento_final.md` | Borradores listos para pegar (título, aperturas, secciones, bibliografía) | Vigente |
| `docs/anexos/11_revision_codigo_vs_documento.md` | Matriz código↔documento, decisión PHP puro, especificación objetivo | Vigente |
| `docs/anexos/12_guia_migracion_docker.md` | Migración offline Docker | Vigente |
| `docs/anexos/13_guia_pruebas_version_final.md` | Guía de pruebas + smoke test | Vigente |
| `docs/casos_de_uso/plantuml/*.puml` | 12+ fuentes PlantUML (UC-00…UC-10, UML, Gantt) | **Corregidas en esta revisión** (ver §3) |
| `docs/casos_de_uso/imagenes/*.png` | PNG de casos de uso | 3 re-renderizados (UC-00, UC-05, UC-10) |
| `docs/diagramas/imagenes/**` | UML (clases, estados, ERD, despliegue, secuencia) | 4 re-renderizados |
| `docs/diagramas/*.drawio/xml` | Diagrama de capas (drawio) | Sin Python ✓ (ya limpio) |

## 2. Estado de aplicación de los hallazgos previos (09/10/11)

| Hallazgo / cambio documentado | Estado |
|---|---|
| Título nuevo unificado (C3) | 🔴 **Pendiente en el .docx** — borradores listos en 10 §5.1 (5 ubicaciones) |
| 10 secciones «No definido» (C2) | 🔴 **Pendiente en el .docx** — borradores en 10 §5.2–§5.9 |
| Bibliografía vacía (C1) | 🔴 **Pendiente en el .docx** — 17 fuentes en 10 §5.10 |
| Tabla 3 alfabeto Braille incompleta (C4) | 🔴 **Pendiente en el .docx** — correcciones en 10 anexo |
| Tabla 5 stack: Laravel 13 / PHP 8.3, fila Python→PHP (A2) | 🔴 **Pendiente en el .docx** (verificado: código Laravel 13.6.0/PHP 8.3) |
| Motores: 3 → **4** NEMA 17 (A1) | 🟡 **Documento corregido** en puml; pendiente en el .docx (Tabla 4 / Figura 16) |
| Rol «Docente» → «Solicitante» (M8) | ✅ Código y AGENTS corregidos; el .docx ya usa «Solicitante» |
| Encuadre conceptual «impresora Braille» → «recursos táctiles» (11) | 🔴 **Pendiente en el .docx** — 9 filas con texto de reemplazo en 09 §11.2 |
| Exports admin-only | ✅ Implementado + tests |
| G-Codes en disco privado + descarga autenticada | ✅ Implementado |
| Catálogo requiere sesión (README corregido) | ✅ Coherente con el documento (UC-03) |
| Login: sin enlaces muertos AdminLTE | ✅ Implementado (2026-08) |
| `fecha_creacion` en recursos (validación sin columna) | ✅ Migración + ERD actualizado |
| Cancelación de pedidos por el Solicitante | ✅ Implementado (2026-08) + tests |
| Metodología Scrum/Kanban (PSCP) | ✅ Documentada en AGENTS.md y anexo 02; 🔴 declararla en el .docx |

## 3. Nuevos hallazgos de esta revisión (figuras y UML)

| Archivo | Problema encontrado | Corrección aplicada |
|---|---|---|
| `plantuml/UML_secuencia_UC06.puml` | Flujo construido sobre «BrailleService (Python Core)» y «previsión 2D» (no implementados) | **Reescrito** al flujo real: `App\Services\BrailleTranslator` (PHP), validación de caracteres, generación de G-Code a disco local, creación del pedido y «Mis solicitudes»; RF-08 (previsión 2D) marcada `[PENDIENTE]` |
| `plantuml/UC00_diagrama_general.puml` | Nota «MÓDULO 2: … (Python Core en backend)» | Corregido a «Service PHP: App\Services\BrailleTranslator» |
| `plantuml/UML_despliegue.puml` | Artefacto «Python 3 (planificado)» y **«Motores NEMA 17 (x3)»** | Python → Service PHP; motores → **x4** (X/Y/Z + extrusor, coherente con la decisión A1) |
| `plantuml/UML_base_datos_ERD.puml` | `recursos` sin `fecha_creacion` (existe en la migración) | Campo añadido (`fecha_creacion : date?`) |
| `plantuml/UML_estados_pedido.puml` | Transición «Error de impresión (SoftDelete)» no existe en el código | Eliminada; se mantiene «Solicitante cancela (SoftDelete)» (ahora implementado) |
| `plantuml/UC10_reportes_estadisticas.puml` | Prometía estadísticas de consumo y gráficos (no implementados) | Exports (a–h) e historial (k) = implementados; UC10i/UC10j marcadas `[PENDIENTE]` |
| `plantuml/UC05_gestionar_usuarios.puml` | «Solicitante (Docente)» | «Solicitante (Docente / Directivo / Tutor)» |

Verificación de higiene: `grep -rl "Python Core\|Python 3\|BrailleService" docs/casos_de_uso/plantuml docs/diagramas` → **0 resultados** tras la corrección.

## 4. Mapa Figura del .docx ↔ imagen embebida ↔ fuente actualizada

Las 17 figuras del documento corresponden por orden de inserción a las imágenes embebidas `word/media/imageN.png`. Para actualizar una figura en Word: **clic derecho sobre la imagen → Cambiar imagen…** y seleccionar el archivo nuevo.

| Figura del .docx | Imagen embebida | Archivo fuente actualizado (re-render) |
|---|---|---|
| Figura 1 · Árbol de problemas | image1.jpeg | *(sin cambios)* |
| Figura 2 · Gantt | image2.png | *(sin cambios)* |
| **Figura 3 · UC-00 General** | image3.png | `casos_de_uso/imagenes/UC00_Diagrama_General.png` |
| Figura 4 · UC-01 | image4.png | *(sin cambios)* |
| Figura 5 · UC-02 | image5.png | *(sin cambios)* |
| Figura 6 · UC-03 | image6.png | *(sin cambios)* |
| Figura 7 · UC-04 | image7.png | *(sin cambios)* |
| **Figura 8 · UC-05** | image8.png | `casos_de_uso/imagenes/UC05_Gestionar_Usuarios.png` |
| **Figura 9 · UC-06** | image9.png | `diagramas/imagenes/otros/UML_Secuencia_UC06.png` |
| Figura 10 · UC-07 | image10.png | *(sin cambios)* |
| Figura 11 · UC-08 | image11.png | *(sin cambios — el flujo de estados se cubre en la Figura 15)* |
| Figura 12 · UC-09 | image12.png | *(sin cambios)* |
| **Figura 13 · UC-10** | image13.png | `casos_de_uso/imagenes/UC10_Reportes_Estadisticas.png` |
| Figura 14 · Clases | image14.png | *(sin cambios — 7 clases correctas)* |
| **Figura 15 · Estados del Pedido** | image15.png | `diagramas/imagenes/modelo_dominio/UML_Estados_Pedido.png` |
| **Figura 16 · Despliegue** | image16.png | `diagramas/imagenes/otros/UML_Despliegue.png` |
| **Figura 17 · Diagrama ER** | image17.png | `diagramas/imagenes/base_datos/UML_Diagrama_ERD.png` |
| image18.png | (imagen adicional, posible logo/anexo) | *(verificar en el documento)* |

## 5. Checklist final de consistencia para la entrega

**En el .docx (edición manual del autor):**
- [ ] Aplicar el título nuevo unificado en las 5 ubicaciones (10 §5.1)
- [ ] Llenar las 10 secciones «No definido» con los borradores (10 §5.2–§5.9), marcando `[PENDIENTE DE EJECUCIÓN FÍSICA]` donde aplique
- [ ] Poner la bibliografía (10 §5.10)
- [ ] Completar la Tabla 3 (alfabeto Braille) — 27 letras + dígitos + puntuación (09 C4)
- [ ] Tabla 5: Laravel 13 / PHP 8.3, fila Python → Service PHP (09 A2)
- [ ] Tabla 4 / Figura 16: motores NEMA 17 **x4** (09 A1)
- [ ] Reemplazar las 7 figuras de la tabla del §4 por los PNG re-renderizados
- [ ] Aplicar las correcciones de encuadre conceptual «recursos táctiles» (09 §11.2, 9 filas)
- [ ] Figura 15: verificar que muestra «Solicitante cancela (SoftDelete)» y NO «Error de impresión»
- [ ] Figura 13: verificar que las estadísticas aparecen como `[PENDIENTE]`
- [ ] Declarar la metodología Scrum/Kanban (AGENTS.md §Metodología)

**En el repo (ya aplicado):**
- [ ] `git status` limpio en `main`
- [ ] `composer test` (47 tests) + `composer analyse` (0 errores) + Pint limpio
- [ ] `bash scripts/pruebas/smoke_test.sh` → 20 PASS · 0 FAIL
- [ ] `grep -rl "Python Core\|Python 3\|BrailleService" docs/` → sin resultados

## 6. Notas

- La **cancelación de solicitudes** (Solicitante, solo estado Pendiente, SoftDelete) se implementó el 2026-08 y quedó cubierta por tests; el diagrama de estados ya la refleja.
- **UC-10** queda honesto: reportes = exports PDF/Excel existentes; estadísticas de consumo/gráficos = `[PENDIENTE]` (no inventar funcionalidad inexistente en la defensa).
- **Motores**: el despliegue decía x3 y el cuerpo del documento mezclaba 3/4; el estándar del proyecto es **x4** (X, Y, Z + extrusor MK8) — verificar que Tabla 4 y el texto del .docx digan x4.
- La previsión 2D (RF-08) no está implementada: el diagrama de secuencia UC-06 la marca `[PENDIENTE]`; si el tribunal la exige, es la siguiente feature candidata.
