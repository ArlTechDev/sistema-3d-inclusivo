# 09 — Informe de Revisión del Documento Final PSCP

> **Actualización (2026-08)**: los hallazgos de figuras/diagramas (motores x4, Python→PHP, UC-10, estados, ERD) se revisaron y corrigieron en las fuentes PlantUML/PNG — ver `14_revision_consistencia_final.md`.

## Sistema Braille Inclusivo — PSCP
## Instituto Técnico «Federico Álvarez Plata» — Sistemas Informáticos
## Documento revisado: `DocumentoFinalPSCP3DJulio24.docx`
## Fecha de revisión: julio 2026

---

## 1. Resumen ejecutivo

Se revisó íntegramente el documento final del Proyecto Sociocomunitario Productivo (541 párrafos, 13 tablas, 17 figuras, portada y tabla de contenidos), con el nuevo título propuesto:

> **SISTEMA WEB E IMPRESORA 3D CON MATERIALES RECICLADOS PARA LA CREACIÓN DE RECURSOS TÁCTILES DESTINADOS A PERSONAS NO VIDENTES**

Se identificaron **26 hallazgos**: 4 críticos, 5 altos, 9 medios y 8 bajos. El documento tiene una base teórica sólida y bien escrita (marco teórico, presupuesto, casos de uso), pero presenta secciones obligatorias vacías (Conclusiones, Resultados, Bibliografía), el título nuevo no está aplicado en ningún lugar y existen contradicciones técnicas que un jurado de Sistemas/Electrónica detectaría.

**Prioridad de acción:**
1. Aplicar el título nuevo (portada, capítulo de título, RESUMEN, INTRODUCCIÓN, Objetivo General).
2. Llenar las secciones «No definido» y la bibliografía (borradores listos en el archivo `10_borradores_contenido_documento_final.md`).
3. Corregir las contradicciones técnicas (motores, Laravel, eje Z, tabla Braille).
4. Corregir detalles editoriales menores.

---

## 2. Metodología de análisis

| Aspecto | Detalle |
|---|---|
| Documento analizado | `docs/documento_pscp/DocumentoFinalPSCP3DJulio24.docx` (541 párrafos, 13 tablas, 17 figuras) |
| Verificación de código | `software/laravel_web/composer.json` (versiones reales de Laravel/PHP) |
| Verificación matemática | Cálculo de pasos/mm (GT2, husillo M8, microstepping), sumas del presupuesto, porcentajes, tipo de cambio |
| Verificación de citas | Extracción automática de todas las citas `(Autor, año)` del texto (18 citas → 17 fuentes únicas) |
| Alcance | Solo el documento final (el perfil quedó excluido por decisión del autor) |

### Criterios de severidad

| Nivel | Definición |
|---|---|
| **CRÍTICO** | Impediría la aprobación de la tesis en defensa |
| **ALTO** | Generaría preguntas serias del jurado |
| **MEDIO** | Sería notado como problema de calidad |
| **BAJO** | Menor pero visible en una revisión detallada |

**Convención de ubicación:** los números de párrafo corresponden al orden del documento (el párrafo 0 es «DEDICATORIA»). Las tablas se nombran con la numeración del propio documento (Tabla 1 = FODA … Tabla 13 = Casos de Uso).

---

## 3. Hallazgos CRÍTICOS (4)

### C1 — Sección «FUENTES DE INFORMACIÓN Y BIBLIOGRAFÍA» completamente vacía

| Campo | Detalle |
|---|---|
| Ubicación | Capítulo 11, párr. 530 → 531 (no hay ningún párrafo entre el encabezado y «ANEXOS») |
| Texto problemático | El encabezado existe pero no hay ninguna referencia listada |
| Problema | El texto cita ~17 fuentes con formato `(Autor, año)` pero no existe el listado bibliográfico. Una tesis sin bibliografía es un defecto inmediato |
| Corrección | Pegar la bibliografía completa redactada en `10_borradores_contenido_documento_final.md` (§ 5.10). Estilo sugerido: APA 7 (coincide con las citas del texto) |

### C2 — Diez secciones «No definido» (capítulos obligatorios vacíos)

| # | Sección | Párr. |
|---|---|---|
| 1 | Participación comunitaria | 346 |
| 2 | Desarrollo técnico del producto (intro) | 349 |
| 3 | Tecnologías utilizadas | 512 |
| 4 | Herramientas de seguimiento | 514 |
| 5 | Dificultades y soluciones aplicadas | 516 |
| 6 | Resultados cualitativos | 519 |
| 7 | Resultados cuantitativos | 521 |
| 8 | Impacto en la comunidad | 523 |
| 9 | CONCLUSIONES | 525 |
| 10 | Recomendaciones | 527 |

| Campo | Detalle |
|---|---|
| Texto problemático | «No definido.» |
| Problema | Son capítulos obligatorios del formato PSCP. Además, «Desarrollo técnico del producto» dice «No definido» (párr. 349) y **debajo tiene contenido** (Análisis de requerimientos, párr. 350+), lo que es contradictorio |
| Corrección | Reemplazar cada «No definido.» por los borradores de `10_borradores_contenido_documento_final.md`. Las secciones que dependan de la ejecución física deben marcarse `[PENDIENTE DE EJECUCIÓN FÍSICA — …]` como ya se hizo en DEDICATORIA/AGRADECIMIENTOS (párr. 1, 3), no con «No definido» |

### C3 — El título nuevo no está aplicado en ningún lugar

| Campo | Detalle |
|---|---|
| Ubicación | Portada (sección inicial del documento), capítulo «TÍTULO DEL PROYECTO SOCIOCOMUNITARIO PRODUCTIVO» (párr. 53), RESUMEN (44), INTRODUCCIÓN (49), Objetivo General (217) |
| Texto problemático | Portada: «SISTEMA WEB Y ELECTROMECÁNICO DE IMPRESIÓN 3D CON MATERIALES RECICLADOS PARA LA PRODUCCIÓN DE RECURSOS EDUCATIVOS TÁCTILES ORIENTADOS A PERSONAS CON DISCAPACIDAD VISUAL». El capítulo del título (párr. 53) no tiene texto de título: salta directo a «Diagnóstico y justificación» |
| Problema | El título es el primer dato que evalúa el jurado; la portada no coincide con el título aprobado |
| Corrección | Aplicar el título nuevo verbatim en portada y capítulo de título; ajustar RESUMEN, INTRODUCCIÓN y Objetivo General con las redacciones listas en `10_borradores_contenido_documento_final.md` (§ 5.1) |

### C4 — Tabla 3 (alfabeto Braille) incompleta y sin número

| Campo | Detalle |
|---|---|
| Ubicación | Párr. 127 (epígrafe «Tabla .»), contenido en la Tabla 3 |
| Texto problemático | La tabla lista solo 25 letras (a–y): **faltan la «z» y la «ñ»**, pese a que el epígrafe promete «las correspondencias del alfabeto en Braille Grado 1 para el español». Además el número de tabla está vacío: «Tabla .» |
| Problema | El español tiene 27 letras (a–z + ñ). Un especialista en Braille o el jurado detectará de inmediato la ausencia de «z». El índice de tablas (párr. 11) sí la numera como «Tabla 3» |
| Corrección | 1) Epígrafe → «Tabla 3. Correspondencias del alfabeto en Braille Grado 1 (español).» 2) Agregar las filas: `z = ⠵` (puntos 1-3-5-6) y `ñ = ⠻` (puntos 1-2-4-5-6, Código Braille Español/ONCE). 3) Opcional recomendado: añadir una segunda tabla con números (0–9 con signo numeral) y signos de puntuación, porque el texto (párr. 126) describe que el Grado 1 cubre letras, dígitos y puntuación |

---

## 4. Hallazgos ALTOS (5)

### A1 — Motores NEMA 17: el documento dice «3» y «4» a la vez

| Lugar | Dice |
|---|---|
| Tabla 4 (componentes de control) | «Motores NEMA 17 (**×3**) … Un motor por eje (X, Y, Z)» |
| Módulo 5, párr. 254 | «**Tres** motores NEMA 17 recuperados» |
| Recursos, párr. 300 | «**4×** motores NEMA 17 (recuperados)» |
| Tabla 11 (presupuesto) | «Motores NEMA 17 (**×4**), varillas ø8mm, fuente ATX» |
| Tabla 4 y Módulo 5 (párr. 253) | «Drivers A4988 (**×4**)» |

| Campo | Detalle |
|---|---|
| Problema | En una Prusa i3 el extrusor MK8 directo necesita su propio motor paso a paso: son **4 motores** (X, Y, Z y extrusor E). Decir ×3 y «un motor por eje» contradice el ×4 de Recursos/presupuesto y deja al extrusor sin motor; con 3 motores tampoco se justifican los 4 drivers |
| Corrección | Unificar en **4 motores NEMA 17 (X, Y, Z + extrusor)** y **4 drivers A4988** en Tabla 4, Módulo 5 (párr. 254) y en el marco teórico si menciona el conteo. Texto sugerido para párr. 254: «Sistema de tracción: cuatro motores NEMA 17 recuperados de basura tecnológica (e-waste) —uno por eje (X, Y, Z) y uno para el extrusor—, utilizando correa dentada GT2 y poleas para X e Y y varillas roscadas para Z» |

### A2 — Versión de Laravel contradictoria (verificado contra el código real)

| Lugar | Dice |
|---|---|
| Tabla 5 (stack tecnológico) | «Laravel 10 / PHP 8.2» |
| Figura 16 (despliegue), párr. 500 | «Servidor en la Nube que aloja Laravel 13» |

| Campo | Detalle |
|---|---|
| Verificación | `php artisan --version` → **Laravel Framework 13.6.0**; `composer.json`: `laravel/framework: ^13.0`, `php: ^8.3`; imagen Docker `php:8.4-cli` |
| Problema | La Tabla 5 contradice tanto al propio documento (Figura 16) como al código real |
| Corrección | Tabla 5 → «Laravel 13 / PHP 8.3». (Revisar también si en el texto hay otras menciones de la versión) |
| Detalle completo | Ver `docs/anexos/11_revision_codigo_vs_documento.md` (§ 2.1 y § 5, R1) — incluye el impacto de la decisión **PHP puro** en la fila Python de la Tabla 5 |

### A3 — Referencia de figura equivocada y epígrafe con carácter sobrante

| Lugar | Dice |
|---|---|
| Párr. 504 | «El Diagrama Entidad-Relación de Base de Datos (**Figura 15**) refleja exactamente las 7 tablas…» |
| Párr. 510 e índice (párr. 40) | «Figura 17. Diagrama ER de Base de Datos**\|**» |

| Campo | Detalle |
|---|---|
| Problema | La Figura 15 es «Diagrama de Estados del Pedido»; el ERD es la **Figura 17**. Además el epígrafe tiene un «\|» sobrante en dos lugares (índice y cuerpo) |
| Corrección | Párr. 504 → «(Figura 17)». Eliminar el «\|» en párr. 510 y en el índice (párr. 40) |

### A4 — Los anexos saltan de «H» a «J» (falta el Anexo I)

| Campo | Detalle |
|---|---|
| Ubicación | Párr. 539–540 |
| Texto problemático | Anexo H (resultados de pruebas piloto) → Anexo J (fotografías). No existe Anexo I |
| Problema | Numeración incompleta; el jurado lo nota como descuido |
| Corrección | Opción A (recomendada): agregar «Anexo I: Carta de solicitud/respaldo y convenio con el Instituto Boliviano de la Ceguera (IBC)» y mantener J. Opción B: renumerar J → I. Decisión del autor (ver § 7) |

---

### A5 — Posicionamiento conceptual: «impresora Braille» vs «impresora 3D de recursos educativos táctiles»

| Campo | Detalle |
|---|---|
| Encuadre correcto (función real) | Párr. 106, 108, 124, 222–223, 247–249, 261–265, 278, 354, 408: la máquina es una **impresora 3D de recursos educativos táctiles** (mapas, figuras geométricas, reglas y fichas Braille) |
| Encuadre contradictorio | RESUMEN (párr. 44), diagnóstico (59), justificación (90, 96), Tabla 2 y presupuesto (340): el sistema se describe como **«impresora Braille»** que produce **«páginas Braille»** y se compara contra **«impresoras Braille comerciales»** |
| Contradicción principal | Párr. 44/340: «reducción del 93% respecto al costo de **impresoras Braille comerciales**» vs párr. 278: «**no sustituye ni compite con las embozadoras**». El término «impresora Braille comercial **equivalente**» (párr. 340) es incorrecto: una impresora 3D no es equivalente a una embozadora |
| Impacto | El jurado puede preguntar «¿su máquina es una impresora Braille?» y el documento responde «sí» en el resumen y «no» en los límites |
| Corrección (idea central) | Enmarcar siempre la máquina como **impresora 3D de recursos educativos táctiles** (las fichas Braille son uno de sus productos, no el único) y usar la **comparativa de tres niveles** (embozadoras / impresoras 3D comerciales / este proyecto). Inventario completo con corrección por pasaje: sección 11 |
| Límite (párr. 278) | Mantener la exclusión de producción masiva en papel, pero añadir la cobertura parcial para material didáctico de bajo volumen (texto en la sección 11.2) |
| Verificación económica | El 93% es correcto como piso (1.400/20.700 = 6.8% → ahorro 93.2%) frente a la cota conservadora de $3.000 USD. Con datos de mercado 2026 `[verificar fuente]`: ~87% vs gama de entrada (~$1.495); ~95% vs gama media ($4.200–$6.000); >98% vs gama alta ($10.000–$33.488+). Unificar el tipo de cambio en 6.96 (1.400 Bs = $201; $3.000 = Bs 20.880) |

---

## 5. Hallazgos MEDIOS (9)

### M1 — «11 Casos de Uso» vs «10 casos de uso individuales»

| Lugar | Dice |
|---|---|
| Párr. 352 | «Con base en los **11 Casos de Uso** definidos en el diagrama UML…» |
| Párr. 384 | «agrupa los **10 casos de uso individuales (UC-01 a UC-10)**» |
| Tabla 13 | Lista 11 filas: UC-00 (vista general) + UC-01…UC-10 |

**Problema:** el conteo depende de si UC-00 (diagrama general, no ejecutable) se incluye. **Corrección:** unificar la redacción, p. ej. párr. 352 → «Con base en los 10 casos de uso definidos en el diagrama UML (UC-01 a UC-10)…». Si se desea conservar UC-00, decir «10 casos de uso (UC-01 a UC-10) más el diagrama general UC-00».

### M2 — UC-04 «Gestionar Instituciones» asignado a dos módulos distintos

| Lugar | Dice |
|---|---|
| Cuerpo, párr. 420 | «Módulo: **1** — Gestión de Usuarios (sección instituciones beneficiarias)» |
| Tabla 13 | UC-04 → «Módulo **4**» |

**Problema:** contradicción entre el cuerpo y la Tabla 13. **Sugerencia:** unificar en Módulo 1 (el registro de instituciones beneficiarias es parte de la gestión de usuarios/institución; el Módulo 4 es el catálogo de recursos). Ajustar la fila de la Tabla 13. (Decisión del autor, § 7.)

### M3 — Marco teórico: «cinco ejes» y numeración «1.4.x» que colisionan

| Lugar | Dice |
|---|---|
| Párr. 116 | «Se organiza en **cinco ejes temáticos** complementarios: (1.4.1) … (1.4.2) … (1.4.3) … (1.4.4) … (1.4.5) …» |
| Párr. 117, 139, 154 | Solo existen **3 subsecciones** H3 bajo Marco teórico |
| Enfoque metodológico (párr. 171) | Es la sección «1.4» del capítulo 1 |

**Problema:** (a) se anuncian 5 ejes pero el texto los agrupa en 3 subsecciones; (b) la numeración 1.4.x asignada a los ejes colisiona con «1.4 Enfoque metodológico» (la numeración es manual en unos párrafos y automática en otros, ver M11). **Corrección sugerida:** párr. 116 → «Se organiza en **tres ejes temáticos**: (1.3.1) el sistema Braille y la discapacidad visual; (1.3.2) la tecnología de impresión 3D y control numérico; (1.3.3) las plataformas web, la economía circular y las metodologías de desarrollo.» (ver M11 sobre la numeración).

### M4 — Eje Z: «4000 pasos/mm (0.025 mm de resolución)» es matemáticamente contradictorio

| Campo | Detalle |
|---|---|
| Ubicación | Párr. 148 |
| Texto problemático | «Eje Z (vertical): el extrusor asciende y desciende mediante un husillo M8 con paso de rosca de 1.25 mm/vuelta, con resolución de **4000 pasos/mm (0.025 mm de resolución)**» |
| Verificación | 4000 pasos/mm → 0.00025 mm/paso (no 0.025). Husillo M8 (1.25 mm) + 200 pasos/rev + microstepping 1/16 → 3200 ÷ 1.25 = **2560 pasos/mm** (0.00039 mm/paso). Con 1/8 → 1280; con 1/32 → 5120. Ninguna configuración estándar da 4000 |
| Corrección | Si el firmware usa microstepping 1/16 con husillo M8: «con resolución de 2560 pasos/mm (≈0.0004 mm/paso)». Si el valor real del firmware es otro, hacer consistentes las tres cifras (pasos/mm, mm/paso y configuración mecánica). Verificar con `M92` real del Marlin |

### M5 — «26 caracteres del alfabeto español» (son 27)

| Campo | Detalle |
|---|---|
| Ubicación | Párr. 169 |
| Texto problemático | «pruebas unitarias automatizadas con PHPUnit para verificar … los **26 caracteres del alfabeto español**, los 10 dígitos y los 15 signos de puntuación» |
| Problema | El alfabeto español tiene **27 letras (a–z + ñ)**. El propio documento (párr. 442) enumera «letras A-Z, Ñ» |
| Corrección | «…los 27 caracteres del alfabeto español (a–z + ñ)…». Revisar también el Anexo B/glosario si repite la cifra |

### M6 — Boquilla del extrusor: «0.4 mm o 0.8 mm» vs «0.8 mm»

| Lugar | Dice |
|---|---|
| Párr. 301 (Recursos) | «Extrusor MK8, boquilla de **0.4 mm o 0.8 mm**» |
| Párr. 111, 124, 144, 255 | «boquilla de **0.8 mm**» |

**Corrección:** unificar a «boquilla de 0.8 mm» en párr. 301 (el resto del documento y el diseño del relieve dependen de esa medida).

### M7 — Tabla 12 (distribución del presupuesto): las sumas no cierran

| Campo | Detalle |
|---|---|
| Tabla 12 | Hardware electrónico ~550 Bs (39.3%) · Consumibles ~280 Bs (20.0%) · Estructura mecánica ~350 Bs (25.0%) · Validación y contingencia ~170 Bs (12.1%) · E-waste y software 0 Bs · **TOTAL ~1.400 Bs (100%)** |
| Verificación | 550 + 280 + 350 + 170 = **1.350 Bs ≠ 1.400** · 39.3 + 20.0 + 25.0 + 12.1 = **96.4% ≠ 100%** |
| Problema | El total declarado (1.400) no coincide con los subtotales; los porcentajes no suman 100%. La Tabla 11 (presupuesto detallado) sí suma exactamente 1.400 |
| Corrección | Opción A: añadir fila «Otros/imprevistos — ~50 Bs — 3.6%» para cerrar en 1.400/100%. Opción B: ajustar los subtotales. Además conviene que las categorías de la Tabla 12 se correspondan 1:1 con los ítems de la Tabla 11 (p. ej., la boquilla aparece dentro de «transmisión» en la Tabla 11 pero se cuenta como consumible en la Tabla 12) |

### M8 — Licencias de publicación inconsistentes

| Lugar | Dice |
|---|---|
| Párr. 103 | «se publicarán bajo **licencia Creative Commons**» |
| Tabla 7 (beneficiarios indirectos) | «réplica **CC BY-SA 4.0**» |
| Párr. 169 | código fuente «se alojó en GitHub bajo una **licencia Creative Commons**» |
| Párr. 341 | software «se distribuye bajo licencias de código abierto (**GPL, MIT, Apache**)» |

**Problema:** para software, CC BY-SA no es una licencia adecuada (es para obras creativas/documentación); además conviene un único criterio. **Corrección sugerida:** código fuente del sistema → licencia **MIT o GPL-3.0**; planos, modelos 3D, G-Code y documentación → **CC BY-SA 4.0**. Redactar el párr. 103/169 con esa distinción.

### M9 — Título del límite «Estenografía Braille de Grado 1» confuso

| Campo | Detalle |
|---|---|
| Ubicación | Párr. 269 |
| Texto problemático | El título del límite dice «**Estenografía Braille de Grado 1**» pero el contenido explica que el módulo se limita a Grado 1 y que el Grado 2 queda fuera |
| Problema | El título sugiere que el límite ES la estenografía de Grado 1, cuando el límite real es «no implementar Grado 2» |
| Corrección | «**Exclusión del Braille Grado 2 (Estenografía)**» |

---

## 6. Hallazgos BAJOS (8)

| # | Ubicación | Hallazgo | Corrección |
|---|---|---|---|
| B1 | Párr. 314 | «tutoriales en video de uso básico**..**» (doble punto) | «…uso básico.» |
| B2 | Párr. 40 y 510 | «Figura 17. Diagrama ER de Base de Datos**\|**» | Eliminar «\|» (ver A3) |
| B3 | Párr. 127 | «Tabla **.**» sin número | «Tabla 3.» (ver C4) |
| B4 | Párr. 10–12, 17 (índice) | «Tabla 2.», «Tabla 3.», «Tabla 8.» sin espacio tras el punto | «Tabla 2. », «Tabla 3. », «Tabla 8. » |
| B5 | Párr. 278 | «embozadoras» (regionalismo) | Primera mención: «impresoras Braille de impacto (embozadoras)» para aclarar el término |
| B6 | Párr. 9–40 | Índices de tablas y figuras con números de página escritos a mano | En Word: seleccionar la tabla de contenidos/índices y actualizar campos (F9) al final |
| B7 | Párr. 134 vs 194 | «Instituto Nacional de Estadística, 2012» vs «INE, 2012» (misma fuente, dos claves de cita) | Unificar: usar «(Instituto Nacional de Estadística, 2012)» y en la bibliografía una sola entrada |
| B8 | Párr. 61 vs 182 | El diagnóstico menciona entrevistas a «directivos institucionales» pero la cuantificación solo dice «3 especialistas del IBC y 4 docentes» | Añadir los directivos a la cuantificación (p. ej., «3 especialistas del IBC, 4 docentes y 2 directivos») o eliminar su mención |

---

## 7. Puntos de decisión que quedan en manos del autor

| # | Decisión | Opciones |
|---|---|---|
| D1 | Anexo I faltante | (a) Agregar «Anexo I: Carta de respaldo/convenio con el IBC» — recomendado; (b) renumerar J → I |
| D2 | UC-04 en qué módulo | (a) Módulo 1 (recomendado, coincide con el cuerpo); (b) Módulo 4 (coincide con la Tabla 13) |
| D3 | Pasos/mm del eje Z | Confirmar el valor real del firmware Marlin (`M92`) y corregir las 3 cifras. Sugerido: 2560 pasos/mm con husillo M8 + 1/16 |
| D4 | Terminología «embozadoras» | Mantener con aclaración en la primera mención (recomendado) o reemplazar por «impresoras Braille de impacto» |
| D5 | Licencias | Código → MIT/GPL-3.0; planos/modelos/documentación → CC BY-SA 4.0 (recomendado) |
| D6 | Estilo bibliográfico | APA 7 (recomendado, coincide con las citas del texto) |
| D7 | Contenido dependiente de ejecución física | Los borradores marcados como `[PENDIENTE DE EJECUCIÓN FÍSICA]` deben completarse con datos reales (resultados del pilotaje, nombres de la dedicatoria) |

---

## 8. Tabla resumen y trazabilidad

| # | Severidad | Hallazgo | Párr. / Tabla |
|---|---|---|---|
| C1 | CRÍTICO | Bibliografía vacía | 530–531 |
| C2 | CRÍTICO | 10 secciones «No definido» | 346, 349, 512, 514, 516, 519, 521, 523, 525, 527 |
| C3 | CRÍTICO | Título nuevo no aplicado | Portada, 53, 44, 49, 217 |
| C4 | CRÍTICO | Tabla Braille sin z/ñ y sin número | 127, Tabla 3 |
| A1 | ALTO | Motores NEMA 17 ×3 vs ×4 | 254, 300, Tablas 4 y 11 |
| A2 | ALTO | Laravel 10/PHP 8.2 vs Laravel 13 | 160 (Tabla 5) vs 500 |
| A3 | ALTO | «Figura 15» por 17 + «\|» sobrante | 504, 510, 40 |
| A4 | ALTO | Falta Anexo I | 539–540 |
| A5 | ALTO | Posicionamiento conceptual y comparación económica | 44, 59, 90, 96, 278, 340, Tabla 2 |
| M1 | MEDIO | 11 vs 10 casos de uso | 352 vs 384 |
| M2 | MEDIO | UC-04 en dos módulos | 420 vs Tabla 13 |
| M3 | MEDIO | «Cinco ejes» y numeración 1.4.x | 116, 117, 139, 154 |
| M4 | MEDIO | Eje Z 4000 pasos/mm incorrecto | 148 |
| M5 | MEDIO | «26 caracteres» (son 27) | 169 |
| M6 | MEDIO | Boquilla 0.4/0.8 vs 0.8 | 301 vs 111, 124, 144, 255 |
| M7 | MEDIO | Tabla 12 no cierra (1.350≠1.400; 96.4%≠100%) | Tabla 12 |
| M8 | MEDIO | Licencias inconsistentes | 103, 169, 341, Tabla 7 |
| M9 | MEDIO | Título de límite confuso | 269 |
| B1 | BAJO | Doble punto | 314 |
| B2 | BAJO | «\|» en epígrafe | 40, 510 |
| B3 | BAJO | «Tabla .» sin número | 127 |
| B4 | BAJO | Sin espacio tras «Tabla N.» en índice | 10–12, 17 |
| B5 | BAJO | «Embozadoras» sin aclarar | 278 |
| B6 | BAJO | Índices con páginas estáticas | 9–40 |
| B7 | BAJO | Cita INE duplicada con 2 claves | 134, 194 |
| B8 | BAJO | Directivos ausentes en cuantificación | 61, 182 |

**Total: 26 hallazgos (4 críticos, 5 altos, 9 medios, 8 bajos).**

---

## 9. Puntos fuertes verificados (no tocar)

Durante la revisión se confirmaron y validaron los siguientes contenidos, que están correctos y coherentes:

- **Matemática del eje X/Y:** 80 pasos/mm correcto para GT2 (20 dientes × 2 mm = 40 mm/vuelta; 200 × 16 / 40 = 80).
- **Presupuesto (Tabla 11):** los ítems suman exactamente 1.400 Bs; la conversión ≈ $200 USD es correcta (1.400/6.96).
- **Reducción del 93%:** 1.400 / 20.700 = 6.8% → ahorro del 93.2%, consistente con la Tabla 2 («más del 90%»).
- **Datos del marco teórico:** cifras OMS 2023 (2.200 millones, ≥1.000 millones evitables), INE 2012 (87.320 personas con discapacidad visual), Global E-Waste Monitor 2020 (53.6 Mt, 17.4%, 74.7 Mt en 2030), especificaciones del Arduino Mega 2560, NEMA 17, PLA (170–220 °C, módulo de Young ≈ 3.5 GPa), fechas históricas del Braille y del RepRap.
- **Coherencia tras revisión anterior:** UEB → Código Braille Español (ONCE) ya corregido; la generación de G-Code quedó correctamente reasignada a UC-07/Módulo 3 (párr. 238, 446); WCAG unificada en nivel AA (párr. 165 vs 279).
- **Consistencia de fechas:** período mayo–septiembre 2026 en RESUMEN, INTRODUCCIÓN, cronogramas y presupuesto.

---

## 10. Guía de aplicación — orden de cambios en el .docx

> Todos los textos listos para pegar están en `10_borradores_contenido_documento_final.md` (referencias entre paréntesis).

| Orden | Acción | Hallazgo | Referencia |
|---|---|---|---|
| 1 | Aplicar el título nuevo: portada, capítulo «TÍTULO DEL PROYECTO», RESUMEN, INTRODUCCIÓN, Objetivo General | C3 | Borradores §5.1 |
| 2 | Reencuadre conceptual: aplicar las correcciones «impresora Braille/página Braille» (párr. 44, 59, 90, 96, 340 y Tabla 2), ajustar el límite (párr. 278) y pegar la comparativa económica de tres niveles | A5 | Informe §11 / Borradores §5.11 |
| 3 | Corregir la Tabla 3: número «Tabla 3.» + filas `z = ⠵` y `ñ = ⠻` | C4 | Borradores Anexo |
| 4 | Llenar las 10 secciones «No definido» (tecnologías, seguimiento, dificultades, participación, resultados, conclusiones, recomendaciones) y marcar lo dependiente de ejecución física como `[PENDIENTE DE EJECUCIÓN FÍSICA]` | C2 | Borradores §5.4–§5.9 |
| 5 | Pegar la bibliografía completa (17 fuentes + 3 recomendadas) | C1 | Borradores §5.10 |
| 6 | Correcciones técnicas de una línea: motores ×4 (Tabla 4 y Módulo 5), Laravel 13/PHP 8.3 (Tabla 5), «(Figura 17)» y quitar «\|», Z=2560, «27 caracteres», boquilla 0.8 mm, Tabla 12 +50 Bs (3.6%) | A1, A2, A3, M4, M5, M6, M7 | Borradores Anexo |
| 7 | Completar DEDICATORIA y AGRADECIMIENTOS con datos personales | — | Borradores §5.2–§5.3 |
| 8 | Correcciones de redacción/media: «10 casos de uso», módulo de UC-04, «tres ejes» 1.3.x, licencias, título del límite | M1, M2, M3, M8, M9 | Informe §5 |
| 9 | Detalles menores: doble punto, «\|» del epígrafe, espacios en índice, «embozadoras», cita INE unificada, directivos en entrevistas | B1–B8 | Informe §6 |
| 10 | Resolver las decisiones del autor (D1–D7): Anexo I, módulo UC-04, pasos/mm reales, terminología, licencias, estilo bibliográfico | D1–D7 | Informe §7 |
| 11 | En Word: actualizar campos de la tabla de contenidos e índices (seleccionar + F9) y revisar la numeración de páginas | B6 | Informe §6 |
| 12 | Commit con mensaje semántico, p. ej. `docs: revisión final del Documento PSCP — 26 hallazgos y borradores de contenido` | — | AGENTS.md |

---

## 11. Revisión de posicionamiento conceptual (inventario completo)

> Complementa el hallazgo **A5**. El documento describe la máquina con dos encuadres contradictorios; esta sección inventaría todos los pasajes y da la corrección lista para pegar por cada uno.

### 11.1 Encuadre correcto — la máquina es una impresora 3D de recursos educativos táctiles

Estos pasajes ya describen correctamente la función principal (no requieren corrección):

| Párr. | Contenido |
|---|---|
| 106 | «…producción automatizada de recursos didácticos tridimensionales y placas con relieves Braille…» |
| 108 | «…capaz de fabricar objetos didácticos y placas de PLA con relieves Braille duraderos…» |
| 124 | «…la impresora 3D deposita relieves de filamento PLA…» |
| 222–223 | Objetivos específicos: catálogo de «modelos didácticos tridimensionales» y hardware «en la creación de relieves y objetos didácticos duraderos» |
| 247–249 | Catálogo: alfabeto y palabras en Braille, mapas, figuras geométricas, reglas táctiles |
| 261–265 | Módulo 6: cubo de calibración (20 mm), regla táctil, ficha Braille |
| 278 | Límite: «…manufactura de recursos didácticos tridimensionales, fichas de vocabulario y señalización rígida» |
| 354, 408 | RF-02 / UC-02: «recursos educativos táctiles» |

### 11.2 Encuadre contradictorio — «impresora Braille / página Braille» (corrección por pasaje)

| Ubicación | Texto actual | Corrección lista para pegar |
|---|---|---|
| Párr. 44 (RESUMEN) | «reduce el costo de producción de una **página Braille** a aproximadamente Bs. 5» | «reduce el costo de producción de un **recurso educativo táctil (ficha Braille o modelo didáctico)** a aproximadamente Bs. 5» |
| Párr. 44 (RESUMEN) | «representando una reducción del 93% respecto al costo de **impresoras Braille comerciales**» | «representando una reducción del 93% frente a la **alternativa comercial de producción táctil (embozadoras)**» |
| Párr. 44 (RESUMEN) | «los **equipos comerciales** cuestan más de $3,000 USD» | «las **embozadoras comerciales** cuestan más de $3,000 USD (los modelos profesionales superan los $10,000 USD `[verificar fuente]`)» |
| Párr. 59 (diagnóstico) | «los costos actuales de **impresoras Braille comerciales** (que superan los $3,000 USD)» | «los costos actuales de las **embozadoras y equipos comerciales de producción táctil** (que superan los $3,000 USD; los modelos de gama alta alcanzan los $33,000+ `[verificar fuente]`)» |
| Párr. 90 (justificación) | «los **equipos comerciales de impresión Braille** tienen costos superiores a los $3,000 USD» | «las **embozadoras (impresoras Braille de papel) y los equipos comerciales de producción táctil** tienen costos superiores a los $3,000 USD» |
| Párr. 96 (justificación) | «más de Bs. 150 que cuesta una **página Braille** producida por impresoras comerciales» | «más de Bs. 150 que cuesta producir un recurso equivalente mediante **servicios comerciales de embosado**» |
| Tabla 2 (Económico) | «equipos de impresión táctil (de $3,000 USD a ~$200 USD)» | «equipos comerciales de producción de material táctil —embozadoras desde ~$1,495 `[verificar fuente]`— frente al proyecto (~$200)» |
| Párr. 340 (presupuesto) | «respecto al costo de una **impresora Braille comercial equivalente** ($3,000 USD ≈ Bs. 20,700)» | «respecto al costo de las **embozadoras comerciales** (la de gama de entrada cuesta ~$1,495, ≈ 7,5× el presupuesto total del proyecto `[verificar fuente]`); frente a la cota conservadora de $3,000 USD el ahorro es del 93%» |
| Párr. 278 (límite) | «no sustituye ni compite con las embozadoras…» | Mantener, y añadir: «Sin embargo, para **material didáctico de bajo volumen** (fichas, maquetas, señalización rígida) el sistema cubre parcialmente la función de una embozadora a una fracción de su costo.» |

### 11.3 Comparativa económica de tres niveles (datos de mercado 2026)

> ⚠️ **Todas las cifras de esta tabla son referencias de mercado que deben verificarse y citarse con su fuente pública** (catálogos de IRIE, EmBraille, ViewPlus/Everest-D, VP Delta/Elite, Braillo; fabricantes de impresoras 3D como Bambu Lab y Creality). No deben presentarse como datos propios del autor.

| Tipo de equipo | Rango de precio 2026 | Notas |
|---|---|---|
| Embozadora gama de entrada (portátil/personal) | $1,495 – $2,674 | P. ej., IRIE Braille Buddy, EmBraille. Lentas, impresión a una cara `[verificar fuente]` |
| Embozadora gama media (educación/oficina) | $4,200 – $6,000 | P. ej., Everest-D V5, VP Delta `[verificar fuente]` |
| Embozadora gama alta (producción) | $10,000 – $33,488+ | P. ej., VP Elite, Braillo `[verificar fuente]` |
| Impresora 3D FDM consumo/hobby | $200 – $400 | P. ej., Bambu Lab A1 Mini, Creality K1 `[verificar fuente]` |
| Impresora 3D prosumer/profesional | $500 – $2,500 | Resina de alta resolución o FDM de gran formato `[verificar fuente]` |
| **Proyecto PSCP (este documento)** | **~$200 (1.400 Bs)** | E-waste + software libre + chasis de madera; reutiliza 2–3 kg de e-waste por máquina |

**Cifras derivadas (matemática verificada, con el proyecto a ~$200):**

- La embozadora más económica (~$1,495) cuesta **≈ 7,5× el presupuesto total del proyecto** (1.495/201 ≈ 7,4).
- Ahorro vs. cota conservadora de **$3,000** (la usada hoy en el documento): **93%** — se mantiene como piso.
- Ahorro vs. gama de entrada (~$1,495): **≈ 87%** (1 − 200/1.495 = 86,6%).
- Ahorro vs. gama media ($4,200–$6,000): **≈ 95%** (95,2%–96,7%).
- Ahorro vs. gama alta ($10,000–$33,488): **> 98%** (98,0%–99,4%).
- Con el costo de una embozadora de entrada (~$1.495) se pueden adquirir **entre 3 y 7 impresoras 3D de consumo** ($200–$400), según el modelo (1.495/400 ≈ 3,7; 1.495/200 ≈ 7,5). Si la fuente consultada afirma «5 a 7», ajustar el rango al dato verificado `[verificar fuente]`.

**Nota de honestidad académica (importante):** frente a una **impresora 3D comercial de gama de entrada** (~$200–$400) el ahorro del proyecto es marginal, porque el precio es similar. La ventaja del proyecto frente a ellas no es el precio, sino: (1) la **integración del sistema** (traductor web texto→Braille, catálogo y generación de G-Code); (2) la **sostenibilidad** (reutilización de e-waste y software libre); y (3) la **producción local y reproducible**. La gran brecha de costo se da frente a las **embozadoras**, que son la alternativa comercial para producción táctil en Braille. Esta distinción debe quedar explícita en la justificación para evitar una pregunta del jurado.

### 11.4 Idea central (frase norte para RESUMEN e INTRODUCCIÓN)

> «La idea principal del proyecto es **facilitar el acceso de las personas no videntes a objetos educativos tridimensionales (táctiles)** —fichas Braille, mapas, figuras geométricas y reglas— mediante un sistema web de traducción y una impresora 3D de bajo costo construida con materiales reciclados.»
