# 10 — Borradores de Contenido para el Documento Final PSCP

## Documento destino: `DocumentoFinalPSCP3DJulio24.docx`

Este archivo contiene los textos listos para copiar/pegar que complementan el informe de revisión (`09_informe_revision_documento_final.md`). Cada sección indica su ubicación en el documento y si es **✅ LISTO PARA PEGAR** o **⚠️ REQUIERE DATOS REALES** (marcadores `[entre corchetes]` que debes reemplazar).

**Decisión de terminología aplicada (según el autor):** el **título** usa «Recursos Táctiles… Personas No Videntes» (nuevo título verbatim); el **cuerpo** conserva «recursos educativos táctiles» y «personas con discapacidad visual», términos más precisos y actuales.

---

## 5.1 Título nuevo unificado

Título oficial (verbatim, sin modificaciones):

> **SISTEMA WEB E IMPRESORA 3D CON MATERIALES RECICLADOS PARA LA CREACIÓN DE RECURSOS TÁCTILES DESTINADOS A PERSONAS NO VIDENTES**

### Dónde aplicarlo y texto exacto

| # | Ubicación | Texto actual (resumido) | Texto nuevo |
|---|---|---|---|
| 1 | **Portada** | «SISTEMA WEB Y ELECTROMECÁNICO DE IMPRESIÓN 3D CON MATERIALES RECICLADOS PARA LA PRODUCCIÓN DE RECURSOS EDUCATIVOS TÁCTILES ORIENTADOS A PERSONAS CON DISCAPACIDAD VISUAL» | Reemplazar por el título oficial verbatim (arriba) |
| 2 | **Capítulo «TÍTULO DEL PROYECTO SOCIOCOMUNITARIO PRODUCTIVO»** (párr. 53) | No existe texto de título: el encabezado salta a «Diagnóstico y justificación» | Insertar debajo del encabezado, centrado y en negrita, el título oficial verbatim |
| 3 | **RESUMEN** (párr. 44, primera oración) | «El presente Proyecto Sociocomunitario Productivo desarrolla un sistema web y electromecánico de impresión 3D con materiales reciclados para la producción automatizada de recursos educativos táctiles en Braille, dirigido a estudiantes con discapacidad visual…» | «El presente Proyecto Sociocomunitario Productivo desarrolla un **sistema web e impresora 3D con materiales reciclados para la creación de recursos educativos táctiles** —fichas Braille, mapas, figuras geométricas y reglas—, destinados a estudiantes con discapacidad visual de instituciones de educación especial del municipio de Cochabamba, Bolivia.» (el resto del resumen se mantiene, aplicando la corrección A5: «página Braille» → «recurso educativo táctil (ficha Braille o modelo didáctico)») |
| 4 | **INTRODUCCIÓN** (párr. 49) | «…describe el desarrollo del Proyecto Sociocomunitario Productivo «Sistema Web y Electromecánico de Impresión 3D con Materiales Reciclados para la Producción de Recursos Educativos Táctiles Orientados a Personas con Discapacidad Visual», ejecutado…» | «…describe el desarrollo del Proyecto Sociocomunitario Productivo «**SISTEMA WEB E IMPRESORA 3D CON MATERIALES RECICLADOS PARA LA CREACIÓN DE RECURSOS TÁCTILES DESTINADOS A PERSONAS NO VIDENTES**», ejecutado…» |
| 5 | **Objetivo General** (párr. 217) | «Desarrollar un sistema web y electromecánico de impresión 3D con materiales reciclados para la producción automatizada de recursos educativos táctiles en Braille.» | «Desarrollar un sistema web e impresora 3D con materiales reciclados para la creación de **recursos educativos táctiles** destinados a personas no videntes.» |

> **Frase de la idea central (para RESUMEN, INTRODUCCIÓN y defensa):** «La idea principal del proyecto es **facilitar el acceso de las personas no videntes a objetos educativos tridimensionales (táctiles)** —fichas Braille, mapas, figuras geométricas y reglas— mediante un sistema web de traducción y una impresora 3D de bajo costo construida con materiales reciclados.»

> ✅ **LISTO PARA PEGAR.** Nota: el cuerpo del documento usa «recursos educativos táctiles» (decisión del autor); el título oficial no se modifica. Ver §5.11 para la justificación económica comparativa.

---

## 5.2 DEDICATORIA (párr. 1)

⚠️ **REQUIERE DATOS REALES** (reemplazar `[…]`). Tres opciones:

**Opción A — familia:**
> A mis padres, `[nombres]`, por su apoyo incondicional y por enseñarme que el esfuerzo siempre tiene recompensa; a mis hermanos, `[nombres si aplica]`, por su compañía en este camino; y a toda mi familia, por creer en mí cuando más lo necesité.

**Opción B — comunidad (recomendada para el enfoque sociocomunitario):**
> A las personas con discapacidad visual de Bolivia, cuya lucha diaria por la inclusión educativa inspiró este proyecto. Que estas páginas contribuyan, aunque sea en pequeña medida, a abrir las puertas del conocimiento a quienes más lo necesitan.

**Opción C — combinada:**
> A mis padres y familia, por su amor y sacrificio; a mis docentes, por su guía constante; y a los estudiantes con discapacidad visual de las instituciones de educación especial de Cochabamba, razón de ser de este proyecto.

---

## 5.3 AGRADECIMIENTOS (párr. 3)

⚠️ **REQUIERE DATOS REALES.**

> Agradezco al Instituto Técnico Nacional de Comercio «Federico Álvarez Plata», carrera de Sistemas Informáticos, por la formación académica recibida; al Lic. `[nombre del tutor]`, por su orientación, paciencia y revisión constante de este trabajo; al Instituto Boliviano de la Ceguera (IBC), sede Cochabamba, por su valiosa colaboración en la validación del material Braille; a las instituciones de educación especial participantes y a sus docentes y estudiantes, por abrirnos sus puertas y permitirnos aprender de su realidad; y a mis compañeros de equipo, `[nombres]`, por el trabajo colaborativo y el compromiso compartido que hicieron posible este proyecto.

---

## 5.4 Tecnologías utilizadas (párr. 512)

> El sistema se desarrolló íntegramente con tecnologías de código abierto. En el **backend** se utilizó el framework **Laravel 13** sobre **PHP 8.3**, con el patrón de arquitectura MVC y el ORM Eloquent para la gestión de la base de datos relacional **MySQL 8.0**. El **frontend** se construyó con **AdminLTE 3 y Bootstrap 5**, garantizando una interfaz responsiva y accesible conforme a las pautas **WCAG 2.1 nivel AA**. El **algoritmo de traducción texto→Braille Grado 1** (Código Braille Español de la ONCE) y la **generación de archivos G-Code** se implementan como **Service class de Laravel en PHP** (`App\Services\BrailleTranslator`), integrados al flujo de pedidos de la plataforma (decisión de arquitectura: PHP puro, ver `11_revision_codigo_vs_documento.md` § 6). El entorno de desarrollo y despliegue se gestionó con **Docker/Compose**, el control de versiones con **Git/GitHub** y la planificación con **Trello** bajo la metodología **Scrum/Kanban**. La **exportación de reportes** se realizó con **DomPDF** (PDF) y **Maatwebsite/Excel** (hojas de cálculo). En el componente de **hardware**, el control de la impresora 3D se realiza mediante el firmware **Marlin 1.1.x** sobre un **Arduino Mega 2560** con shield **RAMPS 1.4**, **cuatro drivers A4988** y **cuatro motores NEMA 17** recuperados de e-waste, con extrusor **MK8** y boquilla de **0.8 mm**.

> ✅ **LISTO PARA PEGAR** (incluye las correcciones A1, A2 y M6 del informe).

---

## 5.5 Herramientas de seguimiento (párr. 514)

> El seguimiento del proyecto se realizó mediante un **tablero Kanban en Trello** con las columnas «Por Hacer», «En Progreso», «Revisión», «Bloqueado» y «Hecho», complementado con la metodología **Scrum** mediante sprints quincenales, reuniones diarias breves y retrospectivas al cierre de cada iteración. El avance del código fuente se controló con **Git/GitHub** a través de ramas y pull requests, y la revisión del tutor quedó documentada en el repositorio del proyecto. El cumplimiento de cada fase se verificó contra los criterios de verificación definidos en el cronograma detallado (Tabla 10).

> ✅ **LISTO PARA PEGAR.**

---

## 5.6 Dificultades y soluciones aplicadas (párr. 516)

> Durante la ejecución se presentaron las siguientes dificultades, resueltas según el plan de riesgos definido:
> - **Baja adherencia del filamento PLA** sobre la cama de impresión: se calibró la distancia inicial del eje Z (boquilla a 0.1 mm), se niveló la cama y se aplicó cinta azul de pintor para mejorar la fijación de la primera capa.
> - **Desincronización de motores NEMA 17**: se ajustó individualmente la corriente de cada driver A4988 y se verificaron los parámetros steps/mm del firmware Marlin (X=80, Y=80, Z=2560, E=95).
> - **Componentes e-waste defectuosos**: se aplicaron pruebas de continuidad y medición de bobinas a todos los motores recuperados antes del ensamblaje, descartando las piezas no funcionales.
> - **Accesibilidad de la plataforma**: se realizaron pruebas iterativas de contraste y navegación por teclado para cumplir WCAG 2.1 nivel AA.
> - **Coordinación con las instituciones piloto**: se estableció coordinación anticipada con el IBC para calendarizar las sesiones de validación.

> ⚠️ **REQUIERE DATOS REALES** — completar o ajustar con las dificultades efectivamente observadas durante el ensamblaje y el pilotaje (p. ej., pandeo de la madera, ruido de motores, tiempo de impresión real). El valor `Z=2560` debe coincidir con el firmware real (`M92`).

---

## 5.7 Participación comunitaria (párr. 346)

> La participación comunitaria se materializó en tres niveles: **(1) Diagnóstico participativo:** encuestas estructuradas a 12 docentes de educación especial y 8 estudiantes con discapacidad visual (Anexos C y D), entrevistas semiestructuradas a 3 especialistas del IBC y 4 docentes, y observación participante en los centros educativos. **(2) Validación sociocomunitaria:** pruebas piloto de legibilidad táctil en dos instituciones de educación especial del municipio, con el respaldo técnico del IBC, y aplicación de formularios de satisfacción a los docentes usuarios. **(3) Socialización:** reuniones informativas con los padres de familia para la firma de consentimientos informados y presentación de resultados a los directivos institucionales.

> ⚠️ **REQUIERE DATOS REALES** — confirmar las actividades efectivamente realizadas y agregar fechas.

---

## 5.8 RESULTADOS OBTENIDOS (párr. 518–524)

### Resultados cualitativos (párr. 519)

> De forma cualitativa, el proyecto evidenció: **(a)** la aceptación favorable de docentes y estudiantes hacia el material táctil impreso en PLA, valorado por su durabilidad frente al papel punzado; **(b)** la validación de la legibilidad táctil de las fichas Braille por parte de especialistas del IBC, conforme a los estándares del Código Braille Español; **(c)** la factibilidad de construir equipamiento funcional y reproducible a partir de componentes electrónicos recuperados (e-waste); **(d)** la reducción del esfuerzo docente en la transcripción manual, al automatizarse la traducción texto→Braille; y **(e)** el fortalecimiento de la vinculación entre la formación técnica y las necesidades de la comunidad.

> ⚠️ **REQUIERE DATOS REALES** — completar con las observaciones registradas en el Anexo H (resultados de las pruebas piloto).

### Resultados cuantitativos (párr. 521)

> De forma cuantitativa, el proyecto alcanzó los siguientes indicadores: **1 impresora 3D funcional** construida con arquitectura Prusa i3, reutilizando **entre 2 y 3 kg de e-waste** por máquina; **costo de producción de Bs. 5 por modelo táctil**, frente a más de Bs. 150 por recurso equivalente producido mediante servicios comerciales de embosado (reducción superior al 93% frente a la alternativa comercial; ver §5.11); **tiempo de transcripción reducido de más de 60 minutos a menos de 5 minutos por página**; **más de 20 modelos didácticos** incorporados al catálogo digital; **presupuesto total ejecutado de ~1.400 Bs. (≈ $200 USD)**; y una **población beneficiaria estimada de 80 a 150 estudiantes** en el primer año de operación. `[Completar con los resultados reales de las encuestas del Anexo H, p. ej., porcentaje de satisfacción docente]`.

> ⚠️ **REQUIERE DATOS REALES** — reemplazar los marcadores con los datos reales del pilotaje.

### Impacto en la comunidad (párr. 523)

> El impacto proyectado del sistema abarca cuatro dimensiones: **educativa**, al mejorar el acceso a material táctil de los estudiantes con discapacidad visual y su autonomía en asignaturas como Geografía, Matemáticas y Ciencias Naturales; **económica**, al reducir el costo de producción de material didáctico en más del 90% respecto a los equipos comerciales; **ecológica**, al otorgar una segunda vida útil a componentes electrónicos en desuso mediante la economía circular; y **social**, al posicionar un modelo de inclusión educativa reproducible en otras instituciones del país mediante la publicación abierta del código y los planos.

> ⚠️ **REQUIERE DATOS REALES** — complementar con las evidencias del pilotaje (fotografías, Anexo J).

---

## 5.9 CONCLUSIONES y RECOMENDACIONES

### CONCLUSIONES (párr. 525)

> Se logró diseñar e implementar un **sistema web e impresora 3D con materiales reciclados para la creación de recursos educativos táctiles** —fichas Braille, mapas, figuras geométricas y reglas—, cumpliendo el objetivo general del proyecto. La plataforma web, desarrollada con Laravel 13 y MySQL 8.0, integra los seis módulos planificados: gestión de usuarios con roles diferenciados (Administrador y Solicitante), traducción automática texto→Braille Grado 1 según el Código Braille Español de la ONCE con previsión visual 2D, gestión de pedidos con cálculo de costos de producción, catálogo digital de recursos educativos, y reportes en PDF y Excel. La generación de archivos G-Code compatibles con Marlin 1.1.x se realiza al momento de confirmar el pedido, y su transferencia a la impresora se efectúa de forma manual y exclusiva por el Administrador, conforme a los límites definidos.
>
> El **hardware electromecánico** se construyó bajo arquitectura cartesiana Prusa i3 con componentes recuperados de e-waste (motores NEMA 17, varillas, fuente ATX) y controlado por Arduino Mega 2560 con shield RAMPS 1.4 y firmware Marlin 1.1.x, alcanzando un costo total de ~1.400 Bs. (≈ $200 USD), lo que representa una reducción del 93% frente a los equipos comerciales, manteniendo la precisión necesaria para la legibilidad táctil de los relieves Braille en PLA.
>
> La **validación sociocomunitaria**, realizada con el apoyo del Instituto Boliviano de la Ceguera (IBC) y dos instituciones de educación especial, confirmó la pertinencia pedagógica de la solución y su aceptación por parte de docentes y estudiantes, evidenciando que la articulación de tecnología, inclusión educativa y economía circular es viable en el contexto boliviano. `[Completar con el resultado final de las pruebas piloto]`

> ⚠️ **REQUIERE DATOS REALES** — la última conclusión debe completarse cuando finalice el pilotaje. Si la defensa ocurre antes, mantener la redacción proyectada y marcar `[PENDIENTE DE EJECUCIÓN FÍSICA]`.

### Recomendaciones (párr. 527)

> 1. Ampliar el módulo de traducción al **Braille Grado 2** y a un conjunto más amplio de caracteres (signos de apertura ¿ ¡, acentuadas, signos matemáticos) en una fase futura del sistema.
> 2. Formalizar el **convenio de colaboración con el IBC** para la validación continua del material producido, la expansión del catálogo educativo táctil y la capacitación de docentes usuarios.
> 3. Publicar el **código fuente, los planos de la impresora y el catálogo de modelos bajo licencias abiertas** (código: MIT/GPL-3.0; documentación y modelos: CC BY-SA 4.0) para permitir la réplica del modelo en otras instituciones del país.
> 4. Elaborar y difundir el **manual de operación y mantenimiento** de la máquina (Anexo E) con fotografías del ensamblaje real y tutoriales en video de uso básico para los docentes.
> 5. Evaluar en fases posteriores la **automatización de la transferencia de archivos G-Code** (tarjeta SD o conexión inalámbrica local), manteniendo la política de air-gapped respecto a internet.
> 6. Realizar un **estudio de costos de producción a mayor escala** que consolide el modelo económico y sustente la sostenibilidad del servicio de provisión de material didáctico.

> ✅ **LISTO PARA PEGAR** (revisar el punto 3 con la decisión D5 del informe).

---

## 5.10 FUENTES DE INFORMACIÓN Y BIBLIOGRAFÍA (párr. 530)

> Estilo sugerido: **APA 7**. Las 17 primeras entradas corresponden a las citas `(Autor, año)` presentes en el texto; las 3 últimas son fuentes mencionadas sin cita formal que conviene incorporar.

1. Arduino AG. (2023). *Arduino Mega 2560 Rev3 — Documentación técnica*. https://docs.arduino.cc/
2. Braille Authority of North America. (2013). *Braille cell dimensions*. https://www.brailleauthority.org/
3. Constitución Política del Estado Plurinacional de Bolivia. (2009). Gaceta Oficial del Estado Plurinacional de Bolivia.
4. Decreto Supremo N° 1893. (2014). *Reglamento de la Ley N° 223 — Ley General para Personas con Discapacidad*. Gaceta Oficial del Estado Plurinacional de Bolivia.
5. Ellen MacArthur Foundation. (2013). *Hacia una economía circular: razones económicas para una transición acelerada*. https://www.ellenmacarthurfoundation.org/
6. Evans, B. (2021). *Practical 3D printers: The science and art of 3D printing*. Apress. `[verificar edición utilizada]`
7. Instituto Nacional de Estadística. (2012). *Censo Nacional de Población y Vivienda 2012*. https://www.ine.gob.bo/ `[unificar la cita en el texto: usar siempre «(Instituto Nacional de Estadística, 2012)», ver hallazgo B7]`
8. Ley N° 223 — Ley General para Personas con Discapacidad. (2012). Asamblea Legislativa Plurinacional de Bolivia.
9. Marlin Contributors. (2023). *Marlin Firmware Documentation* (v1.1.x). https://marlinfw.org/
10. Mellor, C. M. (2006). *Louis Braille: A touch of genius*. National Braille Press.
11. Ministerio de Educación de Bolivia. (2018). *Guía de educación inclusiva para personas con discapacidad*. La Paz: Ministerio de Educación. `[verificar título exacto]`
12. Naciones Unidas. (2015). *Transformar nuestro mundo: la Agenda 2030 para el Desarrollo Sostenible* (ODS 4). https://www.un.org/sustainabledevelopment/es/
13. Organización Mundial de la Salud. (2023). *Informe mundial sobre la visión*. Ginebra: OMS. `[verificar: la cifra de 2.200 millones de personas con discapacidad visual proviene del World Report on Vision (OMS, 2019); si la fuente usada es de 2019, ajustar año y título]`
14. Ponce Talancón, H. (2006). La matriz FODA: una alternativa para realizar diagnósticos y determinar estrategias de intervención en las organizaciones productivas y sociales. *Contribuciones a la Economía*. `[verificar publicación exacta]`
15. Pressman, R. S., & Maxim, B. R. (2020). *Ingeniería del software: un enfoque práctico* (9.ª ed.). McGraw-Hill.
16. RepRap Community. (2005). *RepRap — Replicating Rapid Prototyper*. https://reprap.org/
17. Schwaber, K., & Sutherland, J. (2020). *La Guía de Scrum: la guía definitiva de Scrum*. https://scrumguides.org/

**Fuentes mencionadas en el texto sin cita formal (recomendado incorporarlas):**

18. Organización Nacional de Ciegos Españoles (ONCE). (s. f.). *Código Braille Español*. https://www.once.es/
19. Universidad de las Naciones Unidas. (2020). *Global E-Waste Monitor 2020*. https://ewastemonitor.info/
20. JetBrains. (2023). *Developer Ecosystem Survey 2023*. https://www.jetbrains.com/lp/devecosystem-2023/

> ✅ **LISTO PARA PEGAR**, con las verificaciones marcadas entre corchetes.

---

## 5.11 Justificación económica comparativa (datos de mercado 2026 — verificar fuentes)

> ⚠️ **REQUIERE VERIFICACIÓN DE FUENTES.** Todas las cifras de mercado deben citarse con su fuente pública (catálogos de IRIE, EmBraille, ViewPlus/Everest-D, VP Delta/Elite, Braillo; Bambu Lab, Creality) antes de incluirlas en el documento final. No presentarlas como datos propios.

**Párrafo listo para pegar (justificación económica):**

> Las embozadoras (impresoras Braille de papel) son equipos especializados de costo muy elevado: la gama de entrada parte de aproximadamente $1,495 USD y los modelos profesionales superan los $10,000 USD, llegando hasta $33,000+ USD en gama alta `[verificar fuente]`. Las impresoras 3D comerciales, por su parte, tienen un costo moderado ($200–$400 USD en gama de consumo y $500–$2,500 USD en gama profesional `[verificar fuente]`), pero no incluyen la traducción automática a Braille ni un catálogo de recursos educativos táctiles. El presente proyecto se sitúa en un punto intermedio: con un presupuesto de ~1.400 Bs. (≈ $200 USD) —gracias a la reutilización de e-waste y al software libre— ofrece una impresora 3D que **cubre parcialmente la función de una embozadora para material didáctico de bajo volumen** (fichas Braille, maquetas, señalización) e integra la plataforma web de traducción y catálogo. La embozadora más económica cuesta ≈ 7,5× el presupuesto total del proyecto; con su costo se pueden adquirir entre 3 y 7 impresoras 3D de consumo según el modelo (1.495/400 ≈ 3,7; 1.495/200 ≈ 7,5) `[verificar fuente]`; frente a la cota de $3,000 USD el ahorro es del 93% (piso conservador), frente a la gama de entrada (~$1,495) del ~87%, frente a la gama media (~$4,200–$6,000) del ~95%, y frente a la gama alta (>$10,000) superior al 98% `[cifras a verificar]`. Todos los valores en USD usan el tipo de cambio unificado de 6.96 Bs/USD (1.400 Bs = $201; $3,000 = Bs 20,880).

**Tabla comparativa:**

| Tipo de equipo | Rango de precio 2026 | Notas |
|---|---|---|
| Embozadora gama de entrada (portátil/personal) | $1,495 – $2,674 | P. ej., IRIE Braille Buddy, EmBraille `[verificar fuente]` |
| Embozadora gama media (educación/oficina) | $4,200 – $6,000 | P. ej., Everest-D V5, VP Delta `[verificar fuente]` |
| Embozadora gama alta (producción) | $10,000 – $33,488+ | P. ej., VP Elite, Braillo `[verificar fuente]` |
| Impresora 3D FDM consumo/hobby | $200 – $400 | P. ej., Bambu Lab A1 Mini, Creality K1 `[verificar fuente]` |
| Impresora 3D prosumer/profesional | $500 – $2,500 | Resina de alta resolución o FDM de gran formato `[verificar fuente]` |
| **Proyecto PSCP** | **~$200 (1.400 Bs)** | E-waste + software libre + chasis de madera; 2–3 kg de e-waste reutilizados |

**Nota de honestidad académica:** frente a una impresora 3D comercial de gama de entrada (~$200–$400) el ahorro es marginal; la ventaja frente a ellas es la integración (traductor web, catálogo, generación de G-Code), la sostenibilidad y la producción local. La gran brecha de costo se da frente a las embozadoras.

---

## Anexo de este archivo — Correcciones de una línea (para aplicar directamente en el .docx)

| Hallazgo | Acción exacta |
|---|---|
| A1 | Tabla 4 y Módulo 5 (párr. 254): «Motores NEMA 17 (×3) / Tres motores» → «×4 (X, Y, Z + extrusor)» |
| A2 | Tabla 5: «Laravel 10 / PHP 8.2» → «Laravel 13 / PHP 8.3» |
| A3 | Párr. 504: «(Figura 15)» → «(Figura 17)»; párr. 40 y 510: eliminar «\|» |
| A5 | Párr. 44, 59, 90, 96, 340 y Tabla 2: reemplazar «impresora Braille / página Braille» por «impresora 3D de recursos educativos táctiles» y «recurso educativo táctil (ficha Braille o modelo didáctico)»; párr. 340: quitar «equivalente»; párr. 278: añadir cobertura parcial para bajo volumen (detalle: Informe §11.2) |
| C4 | Párr. 127: «Tabla .» → «Tabla 3.»; agregar filas `z = ⠵` y `ñ = ⠻` en la Tabla 3 |
| M1 | Párr. 352: «11 Casos de Uso» → «10 casos de uso (UC-01 a UC-10)» |
| M4 | Párr. 148: «4000 pasos/mm (0.025 mm)» → «2560 pasos/mm (≈0.0004 mm/paso)» con husillo M8 + microstepping 1/16 `[confirmar con M92 real]` |
| M5 | Párr. 169: «26 caracteres» → «27 caracteres (a–z + ñ)» |
| M6 | Párr. 301: «0.4 mm o 0.8 mm» → «0.8 mm» |
| M7 | Tabla 12: agregar fila «Otros/imprevistos ~50 Bs (3.6%)» para cerrar en 1.400 Bs / 100% |
| M9 | Párr. 269: «Estenografía Braille de Grado 1» → «Exclusión del Braille Grado 2 (Estenografía)» |
| B1 | Párr. 314: «uso básico..» → «uso básico.» |
| B7 | Párr. 194: «INE, 2012» → «Instituto Nacional de Estadística, 2012» |
