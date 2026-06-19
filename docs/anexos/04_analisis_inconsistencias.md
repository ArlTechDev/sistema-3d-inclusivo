# 04 — Análisis de Inconsistencias y Errores

## Sistema Braille Inclusivo — PSCP
## Instituto Técnico "Federico Alvarez Plata"
## Fecha de análisis: 19 de junio de 2026

---

## 1. Metodología de Análisis

| Aspecto | Detalle |
|---|---|
| Documentos analizados | PerfilProyectojunio.docx, DocumentoFinalPSCP3DJunio.docx |
| Documentación cruzada | docs/anexos/ (3 archivos), docs/casos_de_uso/ (1 índice + 12 PlantUML) |
| Código fuente | software/laravel_web/ (7 modelos, 9 migraciones, 4 controladores, 1 seeder) |
| Python Core | software/python_core/ (placeholder sin implementación) |
| Hardware | hardware/ (estructura de carpetas, sin archivos de firmware ni CAD) |

### Criterios de severidad

| Nivel | Definición |
|---|---|
| **CRÍTICO** | Impediría la aprobación de la tesis en defensa |
| **ALTO** | Generaría preguntas serias por parte del jurado |
| **MEDIO** | Sería notado como problema de calidad |
| **BAJO** | Menor pero visible en una revisión detallada |

---

## 2. Categoría 1: Errores Críticos (5 hallazgos)

### 2.1 — 15 secciones "No definido" (~40% del DocumentoFinal vacío)

| Campo | Detalle |
|---|---|
| Documento | DocumentoFinalPSCP3DJunio.docx |
| Párrafos afectados | 1-2 (Dedicatoria), 3-4 (Agradecimientos), 370 (Actividades ejecutadas), 372 (Participación comunitaria), 374 (Desarrollo técnico del producto), 377 (Requerimientos funcionales), 379 (Requerimientos no funcionales), 387 (Tecnologías utilizadas), 389 (Herramientas de seguimiento), 391 (Dificultades y soluciones), 394 (Resultados cualitativos), 396 (Resultados cuantitativos), 398 (Impacto en la comunidad), 400 (CONCLUSIONES), 402 (Recomendaciones) |
| Texto problemático | "No definido." / "No definido aun." |
| Problema | Capítulos enteros de la tesis están vacíos, incluyendo Conclusiones y Resultados |
| Impacto | Sin contenido en estas secciones no hay tesis defendible |
| Corrección | Redactar las secciones que requieren contenido; marcar las que dependen de ejecución física como [PENDIENTE DE EJECUCIÓN FÍSICA] |

---

### 2.2 — Párrafo triple duplicado (copy-paste)

| Campo | Detalle |
|---|---|
| Documento | PerfilProyectojunio.docx |
| Párrafos afectados | 45, 46, 47 |
| Texto duplicado | "Para los estudiantes con discapacidad visual: accederán a mapas topográficos, figuras geométricas, reglas de medición y fichas de vocabulario en Braille impresos en relieve de plástico rígido (PLA)..." |
| Problema | Mismo párrafo repetido 3 veces consecutivas |
| Impacto | Error evidente que el jurado detectaría inmediatamente |
| Corrección | Eliminar párrafos 46 y 47, mantener solo el párrafo 45 |

---

### 2.3 — "Docente" vs "Solicitante" — código y documentos no coinciden

| Campo | Detalle |
|---|---|
| Código fuente | Migración users.rol: `enum('Administrador', 'Docente')->default('Docente')` |
| Documentos | "Usuario Solicitante (Docentes, Directivos o Tutores)" |
| Middleware | CheckRole verifica `rol` contra lista de roles permitidos |
| Seeder | `rol => 'Docente'` para usuario de prueba |
| Problema | El código almacena "Docente", la documentación dice "Solicitante". El middleware buscaría "Docente", no "Solicitante" |
| Impacto | Contradicción directa entre especificación e implementación |
| Corrección | Mantener "Docente" en código, documentar como "Usuario Solicitante (Docente)" en textos |

---

### 2.4 — Tabla pedidos vacía — módulo central sin implementar

| Campo | Detalle |
|---|---|
| Migración pedidos | Solo tiene `$table->id()` y `$table->timestamps()` |
| Modelo Pedido.php | Completamente vacío: sin `$fillable`, sin `SoftDeletes`, sin relaciones |
| Documentos (Módulo 3) | Describen campos detallados: solicitante, institución, recurso, fecha, cantidad, consumo PLA, costo, estado, G-Code |
| Problema | El módulo más importante de la tesis (Gestión de Pedidos y Costos) no tiene implementación |
| Impacto | Sin pedidos no hay flujo completo del sistema |
| Corrección | Implementar migración con campos reales, modelo con relaciones, crear PedidoController |

---

### 2.5 — No existe algoritmo Braille ni G-Code

| Campo | Detalle |
|---|---|
| Archivo | software/python_core/main.py |
| Contenido actual | `print("Algoritmo Braille -> G-Code en desarrollo")` |
| Documentos | Describen traducción automática y generación de G-Code como el núcleo (CORE) del sistema |
| Problema | El algoritmo central del sistema no tiene implementación |
| Impacto | Sin algoritmo no existe la funcionalidad principal de la tesis |
| Corrección | Implementar el algoritmo o documentar como [PENDIENTE DE IMPLEMENTACIÓN] |

---

## 3. Categoría 2: Errores Altos (5 hallazgos)

### 3.1 — WCAG: nivel AA vs AAA contradictorio

| Campo | Detalle |
|---|---|
| DocumentoFinal para 173 | "cumplir el nivel de conformidad **AA** de las WCAG 2.1" |
| DocumentoFinal para 294 | "no se implementará ni garantizará el cumplimiento estricto del estándar internacional de accesibilidad web WCAG 2.1 en su nivel **AAA**" |
| Problema | AA y AAA son niveles diferentes; la redacción sugiere confusión entre ambos |
| Corrección | Unificar a "pautas de accesibilidad basadas en WCAG 2.1 nivel AA, sin garantizar cumplimiento exhaustivo de nivel AAA" |

---

### 3.2 — UEB vs Código Braille Español (HALLAZGO CRÍTICO)

| Campo | Detalle |
|---|---|
| DocumentoFinal para 246 (Alcances) | "siguiendo la tabla estándar de la **Unified English Braille (UEB)**" |
| DocumentoFinal para 128 (Marco Teórico) | "**Código Braille Español** publicado por la Organización Nacional de Ciegos Españoles (ONCE)" |
| UC06_traducir_braille.puml | "Caracteres soportados (UEB Grado 1)" |
| 03_reglas_comunitarias.md | "Solo se soporta Braille Grado 1 (alfabeto alfabético, sin estenografía)" |
| Problema | UEB es el estándar INGLÉS. Para español se usa el Código Braille Español (ONCE). UEB NO incluye ñ ni vocales acentuadas (á, é, í, ó, ú) |
| Impacto | Si se implementa UEB, el sistema NO podrá traducir caracteres esenciales del español |
| Corrección | Reemplazar TODA mención de "UEB" por "Código Braille Español (ONCE)" en Alcances y documentación técnica. Mantener UEB solo como contexto internacional en Marco Teórico |

---

### 3.3 — InventarioPla existe a pesar del límite "sin inventario"

| Campo | Detalle |
|---|---|
| Código | `app/Models/InventarioPla.php` existe como modelo |
| Migración | `database/migrations/*_create_inventario_plas_table.php` crea tabla `inventario_plas` |
| Documentos (límites) | "Exclusión de Control de Inventarios: El sistema web no contemplará módulos para la gestión interna de almacén de materias primas" |
| Problema | Modelo y migración contradicen el límite declarado en ambos documentos |
| Corrección | Eliminar InventarioPla.php y su migración, o eliminar el límite de exclusión |

---

### 3.4 — Diagrama de Gantt es placeholder

| Campo | Detalle |
|---|---|
| DocumentoFinal para 346 | "[Insertar diagrama de Gantt — ver Tabla 9]" |
| Problema | Placeholder nunca reemplazado con el diagrama real |
| Corrección | Generar diagrama de Gantt con PlantUML o insertar imagen existente |

---

### 3.5 — Numeración de anexos diferente entre documentos

| Campo | Detalle |
|---|---|
| PerfilProyecto | A=Cronograma, B=Árbol de Problemas, C=Encuesta Docentes, D=Encuesta Estudiantes |
| DocumentoFinal | A=Glosario, B=Árbol de Problemas, C=Encuesta Docentes, D=Encuesta Estudiantes, E=Manual de operación, F=Diagramas UML, G=Planos, H=Resultados piloto, I=Carta IBC, J=Fotografías |
| Problema | Anexo A redefinido de "Cronograma" a "Glosario"; el Cronograma desaparece |
| Corrección | Reconciliar numeración. DocumentoFinal como autoridad. Eliminar referencia a "Anexo A: Cronograma" en PerfilProyecto |

---

## 4. Categoría 3: Errores Medios (6 hallazgos)

### 4.1 — Boquilla: "0.4mm o 0.8mm" vs "0.8mm"

| Documento | Texto |
|---|---|
| PerfilProyecto para 112 | "boquilla de **0.4 mm o 0.8 mm**" |
| DocumentoFinal para 144 | "en este proyecto, **0.8 mm**" |
| DocumentoFinal para 118 | "la impresora 3D deposita relieves de filamento PLA con boquilla de **0.8 mm**" |
| 02_arquitectura_tecnica.md línea 35 | "boquilla **0.4 mm o 0.8 mm**" |
| **Corrección** | Estandarizar a 0.8mm en todos los documentos |

---

### 4.2 — Beneficiarios: ~200 vs 80-150

| Documento | Texto |
|---|---|
| 01_contexto_sociocomunitario.md línea 43 | "~200 en Cochabamba" |
| DocumentoFinal para 222 | "entre 80 y 150 estudiantes en el primer año" |
| **Corrección** | Clarificar: "~200 total estimado, 80-150 proyectados para primer año" |

---

### 4.3 — Artículos CPE diferentes

| Documento | Artículos citados |
|---|---|
| 03_reglas_comunitarias.md | Art. 17, 61, 112 |
| DocumentoFinal para 135 | Art. 70, 71 |
| **Corrección** | Usar Art. 70 y 71 (específicos de educación). Actualizar 03_reglas_comunitarias.md |

---

### 4.4 — Python Core referenciado pero no existe

| Documento | Referencia |
|---|---|
| 02_arquitectura_tecnica.md línea 81 | "Backend Laravel + Python Core" |
| 03_reglas_comunitarias.md línea 57 | "La traducción se realiza en el backend (Python Core)" |
| software/python_core/main.py | Solo imprime texto placeholder |
| **Corrección** | Agregar "(planificado)" donde se mencione Python Core |

---

### 4.5 — "Administrador/Operador" dual naming sin estandarizar

| Contexto | Uso |
|---|---|
| Documentos | "Administrador / Operador" usado indistintamente |
| Código | Solo existe "Administrador" como rol |
| Límites | "operador" se refiere a quien físicamente opera la impresora |
| **Corrección** | Definir claramente: Administrador = rol web, Operador = persona que opera la impresora 3D |

---

### 4.6 — Menciones a "Pantalla LCD" y "Tarjeta SD" (decisión técnica eliminada)

| Documento | Párrafo | Texto problemático |
|---|---|---|
| PerfilProyecto | 158 | "pantalla LCD 20×4" en Hardware de control |
| DocumentoFinal | 315 | "pantalla LCD 20×4" en Hardware de control |
| DocumentoFinal | 108 | "transferencia... mediante memoria SD o conexión local" |
| DocumentoFinal | 286 | "archivos vía tarjeta SD o USB" |
| PerfilProyecto | 129 | "archivos vía tarjeta SD o USB" |
| Problema | Se decidió operación USB directa (Tethered Printing) sin LCD ni SD |
| **Corrección** | Eliminar menciones a LCD y SD; estandarizar a "conexión USB directa desde la PC del operador" |

---

## 5. Categoría 4: Errores Bajos (5 hallazgos)

### 5.1 — Doble punto en 2 ubicaciones
- DocumentoFinal para 4: "No definido aun**..**"
- DocumentoFinal para 330: "uso básico**..**"
- **Corrección:** Eliminar el punto duplicado

### 5.2 — "3d" vs "3D" inconsistente
- PerfilProyecto para 71: "impresión **3d**" (minúscula)
- DocumentoFinal para 228: "impresión **3D**" (mayúscula)
- **Corrección:** Estandarizar a "3D" en todos los documentos

### 5.3 — Tiempo verbal futuro vs pasado
- PerfilProyecto: futuro ("se aplicarán") — correcto para prospectus
- DocumentoFinal: pasado ("se aplicaron") — correcto para informe
- **Corrección:** No requiere corrección, solo documentar la razón

### 5.4 — Bibliografía vacía en PerfilProyecto
- PerfilProyecto para 190: "bIBLIOGRAFÍA" — sección sin contenido
- **Corrección:** Verificar si el DocumentoFinal tiene bibliografía completa

### 5.5 — Término "embozadoras" a verificar
- Ambos documentos, sección límites: "embozadoras (impresoras) de papel de impacto industrial"
- **Corrección:** Verificar si "embozadora" es regionalismo boliviano aceptado; si no, cambiar a "impresoras Braille de impacto industrial"

---

## 6. Tabla Resumen

| Categoría | Cantidad | Impacto en defensa |
|---|---|---|
| Críticos | 5 | Fallaría inmediatamente |
| Altos | 5 | Preguntas serias del jurado |
| Medios | 6 | Notados como problemas de calidad |
| Bajos | 5 | Menores pero visibles |
| **TOTAL** | **21** | — |

---

## 7. Trazabilidad: Problema → Archivo → Línea

| # | Problema | Archivo | Línea/Párrafo |
|---|---|---|---|
| 1.1 | Secciones vacías | DocumentoFinal | 1-2, 3-4, 370-402 |
| 1.2 | Párrafo triple | PerfilProyecto | 45-47 |
| 1.3 | Docente/Solicitante | Migración + Docs | users.rol + Alcances |
| 1.4 | Pedidos vacío | Pedido.php + migración | Modelo completo |
| 1.5 | Sin algoritmo | python_core/main.py | Archivo completo |
| 2.1 | WCAG AA/AAA | DocumentoFinal | 173, 294 |
| 2.2 | UEB vs Código Español | DocumentoFinal | 246, 128 |
| 2.3 | InventarioPla existe | Modelo + Límites | InventarioPla.php + límites |
| 2.4 | Gantt placeholder | DocumentoFinal | 346 |
| 2.5 | Anexos diferentes | Ambos | Sección Anexos |
| 3.1 | Boquilla ambigua | PerfilProyecto + Docs | 112, arquitectura |
| 3.2 | Beneficiarios | Contexto + DocFinal | 43, 222 |
| 3.3 | Artículos CPE | Reglas + DocFinal | 95, 135 |
| 3.4 | Python Core | Arquitectura | 81 |
| 3.5 | Admin/Operador | Ambos | Alcances |
| 3.6 | LCD/SD menciones | Ambos | 158, 315, 108, 286 |
| 4.1 | Doble punto | DocumentoFinal | 4, 330 |
| 4.2 | "3d" minúscula | PerfilProyecto | 71 |
| 4.3 | Tiempo verbal | Ambos | Metodología |
| 4.4 | Bibliografía vacía | PerfilProyecto | 190 |
| 4.5 | "Embozadoras" | Ambos | Límites |
