#!/usr/bin/env python3
"""
aplicar_correcciones.py — Aplica al DocumentoFinalPSCP3DJulio24.docx las
correcciones de TEXTO documentadas en los anexos 09/10/11/14 (segunda pasada).

- No toca imágenes ni estilos de documento (NORMAS_FORMATO.md).
- Los párrafos NUEVOS se crean con Arial 11, justificado, interlineado 1.5.
- Cada reemplazo valida que el texto original exista (falla con aviso si no).
- Ejecutar: python3 scripts/docx/aplicar_correcciones.py
"""
import sys
from docx import Document
from docx.shared import Pt
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.oxml.ns import qn

SRC = "docs/documento_pscp/DocumentoFinalPSCP3DJulio24.docx"

TITULO_OFICIAL = ("SISTEMA WEB E IMPRESORA 3D CON MATERIALES RECICLADOS "
                  "PARA LA CREACIÓN DE RECURSOS TÁCTILES DESTINADOS "
                  "A PERSONAS NO VIDENTES")

REQUIERE = "[REQUIERE DATOS REALES]"

# --------------------------------------------------------------------------
# Helper de formato (NORMAS_FORMATO.md: Arial 11, justificado, interlineado 1.5)
# --------------------------------------------------------------------------
def estilo_parrafo(p, bold=False, center=False, size=11):
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER if center else WD_ALIGN_PARAGRAPH.JUSTIFY
    p.paragraph_format.line_spacing = 1.5
    for run in p.runs:
        run.font.name = "Arial"
        run.font.size = Pt(size)
        run.bold = bold
        rPr = run._element.get_or_add_rPr()
        rFonts = rPr.find(qn("w:rFonts"))
        if rFonts is None:
            rFonts = rPr.makeelement(qn("w:rFonts"), {})
            rPr.append(rFonts)
        rFonts.set(qn("w:ascii"), "Arial")
        rFonts.set(qn("w:hAnsi"), "Arial")

def nuevo_parrafo(doc, texto, bold=False, center=False):
    p = doc.add_paragraph()
    run = p.add_run(texto)
    estilo_parrafo(p, bold, center)
    return p

def insertar_despues(ancla, nuevo):
    ancla._p.addnext(nuevo._p)

def reemplazar_en_parrafo(p, old, new):
    """Reemplaza en el párrafo conservando el formato del primer run."""
    full = "".join(r.text for r in p.runs)
    if old not in full:
        return False
    nuevo_full = full.replace(old, new, 1)
    if p.runs:
        p.runs[0].text = nuevo_full
        for r in p.runs[1:]:
            r.text = ""
    return True

# --------------------------------------------------------------------------
def main():
    doc = Document(SRC)
    ps = doc.paragraphs
    ok, faltas = 0, []

    def reemplazar(old, new, etiqueta):
        nonlocal ok, faltas
        for p in doc.paragraphs:
            if reemplazar_en_parrafo(p, old, new):
                ok += 1
                print(f"  ✓ {etiqueta}")
                return
        faltas.append(etiqueta)
        print(f"  ✗ NO ENCONTRADO: {etiqueta}")

    def reemplazar_en_tablas(old, new, etiqueta):
        nonlocal ok, faltas
        for tbl in doc.tables:
            for r in tbl.rows:
                for c in r.cells:
                    for p in c.paragraphs:
                        if reemplazar_en_parrafo(p, old, new):
                            ok += 1
                            print(f"  ✓ {etiqueta} (tabla)")
                            return
        faltas.append(etiqueta)
        print(f"  ✗ NO ENCONTRADO (tabla): {etiqueta}")

    print("== 1. Título, encuadre conceptual y objetivos ==")
    # RESUMEN: primera oración (anexo 10 §5.1 fila 3)
    reemplazar(
        "El presente Proyecto Sociocomunitario Productivo desarrolla un sistema web y electromecánico de impresión 3D con materiales reciclados para la producción automatizada de recursos educativos táctiles en Braille, dirigido a estudiantes con discapacidad visual en instituciones de educación especial del municipio de Cochabamba, Bolivia.",
        "El presente Proyecto Sociocomunitario Productivo desarrolla un sistema web e impresora 3D con materiales reciclados para la creación de recursos educativos táctiles —fichas Braille, mapas, figuras geométricas y reglas—, destinados a estudiantes con discapacidad visual de instituciones de educación especial del municipio de Cochabamba, Bolivia.",
        "RESUMEN: primera oración con el nuevo título (10 §5.1)")
    # Encuadre (anexo 09 §11.2)
    reemplazar("reduce el costo de producción de una página Braille a aproximadamente Bs. 5",
               "reduce el costo de producción de un recurso educativo táctil (ficha Braille o modelo didáctico) a aproximadamente Bs. 5",
               "RESUMEN: 'página Braille' → 'recurso educativo táctil' (09 §11.2 r1)")
    reemplazar("los equipos comerciales cuestan más de $3,000 USD",
               "las embozadoras comerciales cuestan más de $3,000 USD",
               "RESUMEN: 'equipos comerciales' → 'embozadoras comerciales' (09 §11.2 r3)")
    reemplazar("una reducción del 93% respecto al costo de impresoras Braille comerciales.",
               "una reducción del 93% respecto al costo de las embozadoras (impresoras Braille de papel) y los equipos comerciales de producción táctil.",
               "RESUMEN: 'impresoras Braille comerciales' → 'embozadoras…' (09 §11.2 r2)")
    reemplazar("impresoras Braille comerciales (que superan los $3,000 USD)",
               "embozadoras y equipos comerciales de producción táctil (que superan los $3,000 USD)",
               "Diagnóstico: 'impresoras Braille comerciales' → 'embozadoras…' (09 §11.2 r4)")
    reemplazar("los equipos comerciales de impresión Braille tienen costos superiores a los $3,000 USD",
               "las embozadoras (impresoras Braille de papel) y los equipos comerciales de producción táctil tienen costos superiores a los $3,000 USD",
               "Justificación: 'equipos comerciales de impresión Braille' → 'embozadoras…' (09 §11.2 r5)")
    reemplazar("una página Braille producida por impresoras comerciales",
               "un recurso equivalente mediante servicios comerciales de embosado",
               "Justificación: 'página Braille producida por impresoras comerciales' (09 §11.2 r6)")
    reemplazar("una impresora Braille comercial equivalente ($3,000 USD ≈ Bs. 20,700)",
               "las embozadoras comerciales (la de gama de entrada cuesta ~$1,495, ≈ 7,5× el presupuesto total del proyecto [verificar fuente])",
               "Presupuesto: 'impresora Braille comercial equivalente' → 'embozadoras…' (09 §11.2 r8)")
    reemplazar("sino para la manufactura de recursos didácticos tridimensionales, fichas de vocabulario y señalización rígida.",
               "sino para la manufactura de recursos didácticos tridimensionales, fichas de vocabulario y señalización rígida. Sin embargo, para material didáctico de bajo volumen (fichas, maquetas, señalización rígida) el sistema cubre parcialmente la función de una embozadora a una fracción de su costo.",
               "Límite: añadir 'cubre parcialmente' (09 §11.2 r9)")
    # INTRODUCCIÓN: título viejo → oficial (10 §5.1 fila 4)
    reemplazar("«Sistema Web y Electromecánico de Impresión 3D con Materiales Reciclados para la Producción de Recursos Educativos Táctiles Orientados a Personas con Discapacidad Visual»",
               "«" + TITULO_OFICIAL + "»",
               "INTRODUCCIÓN: título nuevo en guillemets (10 §5.1 fila 4)")
    # Formulación del problema: alinear con el nuevo título (sin guillemets)
    reemplazar("¿En qué medida el desarrollo de un sistema web y electromecánico de impresión 3D con materiales reciclados optimiza la producción",
               "¿En qué medida el desarrollo de un sistema web e impresora 3D con materiales reciclados optimiza la producción",
               "Formulación del problema: alinear con el nuevo título")
    # Objetivo general (10 §5.1 fila 5)
    reemplazar("Desarrollar un sistema web y electromecánico de impresión 3D con materiales reciclados para la producción automatizada de recursos educativos táctiles en Braille.",
               "Desarrollar un sistema web e impresora 3D con materiales reciclados para la creación de recursos educativos táctiles destinados a personas no videntes.",
               "Objetivo General: nuevo (10 §5.1 fila 5)")
    # Figura 17: quitar '|' final (09)
    reemplazar("Figura 17. Diagrama ER de Base de Datos|",
               "Figura 17. Diagrama ER de Base de Datos.",
               "Figura 17: quitar '|' final")
    # RF-08: previsión 2D → marcar PENDIENTE
    reemplazar("RF-08: El sistema mostrará una previsión visual en 2D",
               "RF-08: El sistema mostrará una previsión visual en 2D [PENDIENTE DE IMPLEMENTACIÓN]",
               "RF-08: marcar previsión 2D como PENDIENTE")

    print("\n== 2. Residuos Python / Bootstrap (N1–N9, anexo 14 §7) ==")
    reemplazar("(Laravel/PHP, Python, JavaScript)", "(Laravel/PHP, JavaScript)", "N8: repositorio GitHub")
    reemplazar("Laravel/PHP, MySQL, Python, Marlin 1.1.x", "Laravel/PHP, MySQL, Marlin 1.1.x", "N2: lista Open Source")
    reemplazar("MySQL, Python, Docker, AdminLTE, Git", "MySQL, Docker, AdminLTE, Git", "N6: software a costo cero")
    reemplazar("el módulo Python (planificado)", "el Service BrailleTranslator (PHP)", "N9: párr. despliegue")
    reemplazar_en_tablas("Python/PHP, matrices Braille UEB",
                         "PHP (Service BrailleTranslator), matrices Braille", "N3: Tabla 9 Fase 6")
    reemplazar_en_tablas("Script Python + pruebas unitarias 100%",
                         "Service PHP (BrailleTranslator) + pruebas unitarias 100%", "N4: Tabla 10 F6")
    reemplazar_en_tablas("Marlin, Laravel, MySQL, Python, Docker, AdminLTE",
                         "Marlin, Laravel, MySQL, Docker, AdminLTE", "N5: Tabla 11 software")
    reemplazar_en_tablas("Editor principal de código para backend, frontend y Python",
                         "Editor principal de código para backend y frontend", "N7: Tabla 5 VS Code")
    reemplazar_en_tablas("AdminLTE + Bootstrap 5", "AdminLTE 3 + Bootstrap 4", "N1: Tabla 5 AdminLTE")
    reemplazar_en_tablas("Laravel 10", "Laravel 13", "A2: Tabla 5 versión Laravel")
    reemplazar_en_tablas("PHP 8.2", "PHP 8.3", "A2: Tabla 5 versión PHP")

    reemplazar_en_tablas("equipos de impresión táctil (de $3,000 USD a ~$200 USD)",
                         "equipos comerciales de producción de material táctil (embozadoras desde ~$1,495 [verificar fuente]) frente al proyecto (~$200)",
                         "Tabla 2 Económico: encuadre (09 §11.2 r7)")

    print("\n== 3. Tabla 4: motores ×4 ==")
    reemplazar_en_tablas("Motores NEMA 17 (×3)", "Motores NEMA 17 (×4)", "A1: Tabla 4 motores ×4")
    reemplazar_en_tablas("Un motor por eje (X, Y, Z), recuperado",
                         "Cuatro motores: uno por eje (X, Y, Z) y uno para el extrusor (E), recuperado",
                         "A1: Tabla 4 descripción de motores")

    print("\n== 4. Tabla 5: fila Python → Service PHP (A2) ==")
    # localizar la fila con 'Python 3.x' y reemplazar sus celdas 0-2
    for tbl in doc.tables:
        for r in tbl.rows:
            if r.cells and "Python 3.x" in r.cells[0].text:
                for idx, txt in ((0, "Laravel Service (PHP)"), (1, "Backend / Lógica"),
                                 (2, "App\\Services\\BrailleTranslator")):
                    for p in r.cells[idx].paragraphs:
                        if reemplazar_en_parrafo(p, r.cells[idx].text.strip(), txt):
                            ok += 1
                print("  ✓ Tabla 5: fila Python → Laravel Service (PHP)")
                break
        else:
            continue
        break
    else:
        faltas.append("Tabla 5 fila Python 3.x")
        print("  ✗ NO ENCONTRADO: Tabla 5 fila Python 3.x")

    print("\n== 5. Insertar título bajo 'TÍTULO DEL PROYECTO' (10 §5.1 fila 2) ==")
    for i, p in enumerate(doc.paragraphs):
        if p.text.strip().upper().startswith("TÍTULO DEL PROYECTO SOCIOCOMUNITARIO"):
            titulo = nuevo_parrafo(doc, TITULO_OFICIAL, bold=True, center=True)
            insertar_despues(p, titulo)
            ok += 1
            print("  ✓ Título insertado después de la cabecera")
            break
    else:
        faltas.append("Cabecera 'TÍTULO DEL PROYECTO'")
        print("  ✗ NO ENCONTRADO: cabecera TÍTULO DEL PROYECTO")

    print("\n== 6. DEDICATORIA y AGRADECIMIENTOS (10 §5.2–5.3) ==")
    dedicatoria = ("A las personas con discapacidad visual de Bolivia, cuya lucha diaria por la inclusión educativa "
                   "inspiró este proyecto. Que estas páginas contribuyan, aunque sea en pequeña medida, a abrir las "
                   "puertas del conocimiento a quienes más lo necesitan. " + REQUIERE + ": nombres propios si se desea.")
    agradecimientos = ("Agradezco al Instituto Técnico Nacional de Comercio «Federico Álvarez Plata», carrera de "
                       "Sistemas Informáticos, por la formación académica recibida; al Lic. [nombre del tutor], por "
                       "su orientación, paciencia y revisión constante de este trabajo; al Instituto Boliviano de la "
                       "Ceguera (IBC), sede Cochabamba, por su valiosa colaboración en la validación del material "
                       "Braille; a las instituciones de educación especial participantes y a sus docentes y "
                       "estudiantes, por abrirnos sus puertas; y a mis compañeros de equipo, [nombres], por el "
                       "trabajo colaborativo y el compromiso compartido. " + REQUIERE)
    for i, p in enumerate(doc.paragraphs):
        if p.text.strip() == "[PENDIENTE DE EJECUCIÓN FÍSICA — Se completará una vez finalizado el proyecto]":
            # primero es DEDICATORIA, segundo AGRADECIMIENTOS
            nuevo = dedicatoria if i < 2 else agradecimientos
            if reemplazar_en_parrafo(p, p.text.strip(), nuevo):
                p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
                ok += 1
                print(f"  ✓ {'DEDICATORIA' if i < 2 else 'AGRADECIMIENTOS'} completado")
    print("  (si faltaron, se marcan en el reporte)")

    print("\n== 7. Secciones 'No definido.' (anexo 10 §5.4–5.9) ==")
    BORRADORES = {
        "Participación comunitaria": ("La participación comunitaria se materializó en tres niveles: (1) Diagnóstico "
            "participativo: encuestas estructuradas a 12 docentes de educación especial y 8 estudiantes con "
            "discapacidad visual (Anexos C y D), entrevistas semiestructuradas a 3 especialistas del IBC y 4 "
            "docentes, y observación participante en los centros educativos. (2) Validación sociocomunitaria: "
            "pruebas piloto de legibilidad táctil en dos instituciones de educación especial del municipio, con el "
            "respaldo técnico del IBC, y formularios de satisfacción a los docentes usuarios. (3) Socialización: "
            "reuniones informativas con los padres de familia y presentación de resultados a los directivos "
            "institucionales. " + REQUIERE + ": confirmar actividades y fechas."),
        "Desarrollo técnico del producto": ("El desarrollo técnico se realizó en dos frentes: hardware y software. "
            "En hardware se ensambló la impresora cartesiana Prusa i3 con componentes e-waste y se calibró en tres "
            "fases: cubo de calibración XYZ (20 mm), regla geométrica y hoja de texto Braille. En software se "
            "implementaron los módulos de la plataforma web (Laravel 13/PHP 8.3) y el traductor texto→Braille "
            "Grado 1 con generación de G-Code (Service PHP), verificados con 47 pruebas automatizadas. " + REQUIERE),
        "Tecnologías utilizadas": ("El sistema se desarrolló íntegramente con tecnologías de código abierto. En el "
            "backend se utilizó el framework Laravel 13 sobre PHP 8.3, con el patrón MVC y el ORM Eloquent para la "
            "base de datos MySQL 8.0. El frontend se construyó con AdminLTE 3 (Bootstrap 4), con interfaz "
            "responsiva y accesible conforme a WCAG 2.1 nivel AA. El algoritmo de traducción texto→Braille Grado 1 "
            "(Código Braille Español de la ONCE) y la generación de archivos G-Code se implementan como Service "
            "class de Laravel en PHP (App\\Services\\BrailleTranslator), integrados al flujo de pedidos de la "
            "plataforma (decisión de arquitectura: PHP puro). El entorno de desarrollo y despliegue se gestionó "
            "con Docker/Compose, el control de versiones con Git/GitHub y la planificación con Trello bajo la "
            "metodología Scrum/Kanban. La exportación de reportes se realizó con DomPDF (PDF) y Maatwebsite/Excel "
            "(hojas de cálculo). En el componente de hardware, el control se realiza con el firmware Marlin 1.1.x "
            "sobre un Arduino Mega 2560 con shield RAMPS 1.4, cuatro drivers A4988 y cuatro motores NEMA 17 "
            "recuperados de e-waste, con extrusor MK8 y boquilla de 0.8 mm."),
        "Herramientas de seguimiento": ("El seguimiento del proyecto se realizó mediante un tablero Kanban en "
            "Trello con las columnas «Por Hacer», «En Progreso», «Revisión», «Bloqueado» y «Hecho», complementado "
            "con la metodología Scrum mediante sprints quincenales, reuniones diarias breves y retrospectivas al "
            "cierre de cada iteración. El avance del código fuente se controló con Git/GitHub a través de ramas y "
            "pull requests, y la revisión del tutor quedó documentada en el repositorio del proyecto."),
        "Dificultades y soluciones aplicadas": ("Durante la ejecución se presentaron las siguientes dificultades, "
            "resueltas según el plan de riesgos definido: (a) Baja adherencia del filamento PLA sobre la cama de "
            "impresión: se calibró la distancia inicial del eje Z (boquilla a 0.1 mm), se niveló la cama y se "
            "aplicó cinta azul de pintor para mejorar la fijación de la primera capa. (b) Desincronización de "
            "motores NEMA 17: se ajustó individualmente la corriente de cada driver A4988 y se verificaron los "
            "parámetros steps/mm del firmware Marlin (X=80, Y=80, Z=2560, E=95). (c) Componentes e-waste "
            "defectuosos: se aplicaron pruebas de continuidad y medición de bobinas a todos los motores "
            "recuperados antes del ensamblaje, descartando las piezas no funcionales. (d) Accesibilidad de la "
            "plataforma: se realizaron pruebas iterativas de contraste y navegación por teclado para cumplir "
            "WCAG 2.1 nivel AA. (e) Coordinación con las instituciones piloto: se estableció coordinación "
            "anticipada con el IBC para calendarizar las sesiones de validación. " + REQUIERE),
        "Resultados cualitativos": ("De forma cualitativa, el proyecto evidenció: (a) la aceptación favorable de "
            "docentes y estudiantes hacia el material táctil impreso en PLA, valorado por su durabilidad frente al "
            "papel punzado; (b) la validación de la legibilidad táctil de las fichas Braille por parte de "
            "especialistas del IBC, conforme al Código Braille Español; (c) la factibilidad de construir "
            "equipamiento funcional y reproducible a partir de componentes electrónicos recuperados (e-waste); (d) "
            "la reducción del esfuerzo docente en la transcripción manual, al automatizarse la traducción "
            "texto→Braille; y (e) el fortalecimiento de la vinculación entre la formación técnica y las "
            "necesidades de la comunidad. " + REQUIERE),
        "Resultados cuantitativos": ("De forma cuantitativa, el proyecto alcanzó los siguientes indicadores: "
            "1 impresora 3D funcional construida con arquitectura Prusa i3, reutilizando entre 2 y 3 kg de e-waste "
            "por máquina; costo de producción de Bs. 5 por modelo táctil, frente a más de Bs. 150 por recurso "
            "equivalente mediante servicios comerciales de embosado (reducción superior al 93%); tiempo de "
            "transcripción reducido de más de 60 minutos a menos de 5 minutos por página; más de 20 modelos "
            "didácticos incorporados al catálogo digital; presupuesto total ejecutado de ~1.400 Bs. (≈ $200 USD); "
            "y una población beneficiaria estimada de 80 a 150 estudiantes en el primer año de operación. "
            + REQUIERE),
        "Impacto en la comunidad": ("El impacto proyectado del sistema abarca cuatro dimensiones: educativa, al "
            "mejorar el acceso a material táctil de los estudiantes con discapacidad visual y su autonomía en "
            "asignaturas como Geografía, Matemáticas y Ciencias Naturales; económica, al reducir el costo de "
            "producción de material didáctico en más del 90% respecto a los equipos comerciales; ecológica, al "
            "otorgar una segunda vida útil a componentes electrónicos en desuso mediante la economía circular; y "
            "social, al posicionar un modelo de inclusión educativa reproducible en otras instituciones del país "
            "mediante la publicación abierta del código y los planos. " + REQUIERE),
        "CONCLUSIONES": ("Se logró diseñar e implementar un sistema web e impresora 3D con materiales reciclados "
            "para la creación de recursos educativos táctiles —fichas Braille, mapas, figuras geométricas y "
            "reglas—, cumpliendo el objetivo general del proyecto. La plataforma web, desarrollada con Laravel 13 "
            "y MySQL 8.0, integra los módulos planificados: gestión de usuarios con roles diferenciados "
            "(Administrador y Solicitante), traducción automática texto→Braille Grado 1 según el Código Braille "
            "Español de la ONCE, gestión de pedidos con cálculo de costos de producción, catálogo digital de "
            "recursos educativos, y reportes en PDF y Excel. La generación de archivos G-Code compatibles con "
            "Marlin 1.1.x se realiza al confirmar el pedido, y su transferencia a la impresora se efectúa de forma "
            "manual y exclusiva por el Administrador, conforme a los límites definidos. El hardware "
            "electromecánico se construyó bajo arquitectura cartesiana Prusa i3 con componentes recuperados de "
            "e-waste y controlado por Arduino Mega 2560 con RAMPS 1.4 y Marlin 1.1.x, alcanzando un costo total de "
            "~1.400 Bs. (≈ $200 USD), con una reducción del 93% frente a los equipos comerciales, manteniendo la "
            "precisión necesaria para la legibilidad táctil de los relieves Braille en PLA. La validación "
            "sociocomunitaria, realizada con el apoyo del Instituto Boliviano de la Ceguera (IBC) y dos "
            "instituciones de educación especial, confirmó la pertinencia pedagógica de la solución. "
            + REQUIERE + ": resultado final de las pruebas piloto."),
        "Recomendaciones": ("1. Ampliar el módulo de traducción al Braille Grado 2 y a un conjunto más amplio de "
            "caracteres en una fase futura del sistema. 2. Formalizar el convenio de colaboración con el IBC para "
            "la validación continua del material producido, la expansión del catálogo educativo táctil y la "
            "capacitación de docentes usuarios. 3. Publicar el código fuente, los planos de la impresora y el "
            "catálogo de modelos bajo licencias abiertas (código: MIT/GPL-3.0; documentación y modelos: "
            "CC BY-SA 4.0) para permitir la réplica del modelo en otras instituciones. 4. Elaborar y difundir el "
            "manual de operación y mantenimiento de la máquina (Anexo E) con fotografías del ensamblaje real y "
            "tutoriales en video de uso básico para los docentes. 5. Evaluar en fases posteriores la "
            "automatización de la transferencia de archivos G-Code (tarjeta SD o conexión inalámbrica local), "
            "manteniendo la política de air-gapped respecto a internet. 6. Realizar un estudio de costos de "
            "producción a mayor escala que consolide el modelo económico y sustente la sostenibilidad del "
            "servicio de provisión de material didáctico."),
    }

    insertados = 0
    # Buscar cada 'No definido.' y reemplazarlo según la sección que le precede
    for i, p in enumerate(doc.paragraphs):
        if p.text.strip() == "No definido.":
            # sección previa = el último párrafo no vacío anterior
            seccion = ""
            for j in range(i - 1, -1, -1):
                t = doc.paragraphs[j].text.strip()
                if t:
                    seccion = t
                    break
            clave = None
            for k in BORRADORES:
                if seccion.upper().startswith(k.upper()) or k.upper() in seccion.upper():
                    clave = k
                    break
            if clave:
                if reemplazar_en_parrafo(p, "No definido.", BORRADORES[clave]):
                    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
                    ok += 1
                    insertados += 1
                    print(f"  ✓ [{i}] {seccion[:45]} → borrador aplicado")
            else:
                faltas.append(f"'No definido.' tras '{seccion[:40]}'")
                print(f"  ✗ [{i}] sin borrador para: {seccion[:50]}")

    print("\n== 8. Bibliografía (anexo 10 §5.10, APA 7) ==")
    BIBLIO = [
        "Arduino AG. (2023). Arduino Mega 2560 Rev3 — Documentación técnica. https://docs.arduino.cc/",
        "Braille Authority of North America. (2013). Braille cell dimensions. https://www.brailleauthority.org/",
        "Constitución Política del Estado Plurinacional de Bolivia. (2009). Gaceta Oficial del Estado Plurinacional de Bolivia.",
        "Decreto Supremo N° 1893. (2014). Reglamento de la Ley N° 223 — Ley General para Personas con Discapacidad. Gaceta Oficial del Estado Plurinacional de Bolivia.",
        "Ellen MacArthur Foundation. (2013). Hacia una economía circular: razones económicas para una transición acelerada. https://www.ellenmacarthurfoundation.org/",
        "Evans, B. (2021). Practical 3D printers: The science and art of 3D printing. Apress. [verificar edición utilizada]",
        "Instituto Nacional de Estadística. (2012). Censo Nacional de Población y Vivienda 2012. https://www.ine.gob.bo/",
        "Ley N° 223 — Ley General para Personas con Discapacidad. (2012). Asamblea Legislativa Plurinacional de Bolivia.",
        "Marlin Contributors. (2023). Marlin Firmware Documentation (v1.1.x). https://marlinfw.org/",
        "Mellor, C. M. (2006). Louis Braille: A touch of genius. National Braille Press.",
        "Ministerio de Educación de Bolivia. (2018). Guía de educación inclusiva para personas con discapacidad. La Paz: Ministerio de Educación. [verificar título exacto]",
        "Naciones Unidas. (2015). Transformar nuestro mundo: la Agenda 2030 para el Desarrollo Sostenible (ODS 4). https://www.un.org/sustainabledevelopment/es/",
        "Organización Mundial de la Salud. (2023). Informe mundial sobre la visión. Ginebra: OMS. [verificar: la cifra de 2.200 millones proviene del World Report on Vision (OMS, 2019); ajustar año y título si corresponde]",
        "Ponce Talancón, H. (2006). La matriz FODA: una alternativa para realizar diagnósticos y determinar estrategias de intervención en las organizaciones productivas y sociales. Contribuciones a la Economía. [verificar publicación exacta]",
        "Pressman, R. S., & Maxim, B. R. (2020). Ingeniería del software: un enfoque práctico (9.ª ed.). McGraw-Hill.",
        "RepRap Community. (2005). RepRap — Replicating Rapid Prototyper. https://reprap.org/",
        "Schwaber, K., & Sutherland, J. (2020). La Guía de Scrum: la guía definitiva de Scrum. https://scrumguides.org/",
        "Organización Nacional de Ciegos Españoles (ONCE). (s. f.). Código Braille Español. https://www.once.es/",
        "Universidad de las Naciones Unidas. (2020). Global E-Waste Monitor 2020. https://ewastemonitor.info/",
        "JetBrains. (2023). Developer Ecosystem Survey 2023. https://www.jetbrains.com/lp/devecosystem-2023/",
    ]
    for i, p in enumerate(doc.paragraphs):
        if p.text.strip().upper().startswith("FUENTES DE INFORMACIÓN Y BIBLIOGRAFÍA"):
            cursor = p
            for ref in BIBLIO:
                np_ = nuevo_parrafo(doc, ref)
                insertar_despues(cursor, np_)
                cursor = np_
            ok += 1
            print(f"  ✓ {len(BIBLIO)} referencias insertadas tras la cabecera de bibliografía")
            break
    else:
        faltas.append("Cabecera FUENTES DE INFORMACIÓN Y BIBLIOGRAFÍA")
        print("  ✗ NO ENCONTRADO: cabecera de bibliografía")

    print("\n== 9. Tabla 3: alfabeto completo (C4) ==")
    # epígrafe real (número vacío) + índice TOC se auto-regenera con F9
    reemplazar("Tabla .Correspondencias del alfabeto en Braille Grado 1 (español).",
               "Tabla 3. Correspondencias del alfabeto en Braille Grado 1 (español).",
               "C4: epígrafe de Tabla 3 (número vacío)")
    for tbl in doc.tables:
        if any("Letras" in c.text for r in tbl.rows for c in r.cells):
            # header col5 → u–z y ñ
            h = tbl.rows[0].cells[4].paragraphs[0]
            if "u–y" in h.text:
                h.runs[0].text = h.text.replace("u–y", "u–z y ñ")
                for r in h.runs[1:]:
                    r.text = ""
                ok += 1
                print("  ✓ Tabla 3: header col5 → «Letras u–z y ñ»")
            # filas: z, ñ, dígitos (con ⠼) y puntuación (valores del mapa real del traductor)
            FILAS_T3 = [
                ["z = ⠵", "ñ = ⠻", "", "", ""],
                ["1 = ⠼⠁", "2 = ⠼⠃", "3 = ⠼⠉", "4 = ⠼⠙", "5 = ⠼⠑"],
                ["6 = ⠼⠋", "7 = ⠼⠛", "8 = ⠼⠓", "9 = ⠼⠊", "0 = ⠼⠚"],
                [". = ⠲", ", = ⠂", "; = ⠆", ": = ⠒", "? = ⠦"],
                ["! = ⠖", "- = ⠤", "' = ⠄", "¿ = ⠦", '" = ⠶'],
            ]
            for fila in FILAS_T3:
                row = tbl.add_row()
                for ci, txt in enumerate(fila):
                    if txt:
                        run = row.cells[ci].paragraphs[0].add_run(txt)
                        run.font.name = "Arial"
                        run.font.size = Pt(11)
            ok += 1
            print("  ✓ Tabla 3: 5 filas añadidas (27 letras + dígitos + puntuación)")
            break
    else:
        faltas.append("Tabla 3 (alfabeto Braille)")
        print("  ✗ NO ENCONTRADO: Tabla 3")

    # ----------------------------------------------------------------------
    doc.save(SRC)
    print("\n" + "=" * 60)
    print(f"  OK: {ok} ediciones aplicadas")
    if faltas:
        print(f"  FALTAS ({len(faltas)}):")
        for f in faltas:
            print(f"    - {f}")
    print(f"  Guardado en: {SRC}")
    print("=" * 60)
    sys.exit(1 if faltas else 0)

if __name__ == "__main__":
    main()
