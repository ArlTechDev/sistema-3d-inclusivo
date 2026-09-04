#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script: corregir_citas_apa7.py
Aplica formato estricto APA 7 (7.ª edición en español) a las referencias y citas en DocumentoFinalPSCP3DAgosto17.docx:
1. Respaldo de seguridad (.docx.bak).
2. Corrección de 9 párrafos con citas en el cuerpo del texto (dobles paréntesis, redundancias, ampersand).
3. Reformateo tipográfico de las 26 referencias bibliográficas (P[530]..P[555]) en estructura multi-run:
   - Autor y año en texto regular.
   - Títulos de libros, informes independientes, normas, leyes y revistas en cursiva (*italics*).
   - Sangría francesa exacta de 1.27 cm, interlineado 1.5, Arial 11 pt y justificado.
   - Conjunción castellana 'y' en lugar de ampersand '&'.
4. Validación estricta de no-regresión (566 párrafos, 13 tablas).
"""

import sys
import shutil
import docx
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.oxml.ns import qn
from docx.shared import Pt, Cm

SRC_DOCX = "docs/documento_pscp/DocumentoFinalPSCP3DAgosto17.docx"
BAK_DOCX = "docs/documento_pscp/DocumentoFinalPSCP3DAgosto17.docx.bak"

REFERENCIAS_APA7 = [
    # 1. Arduino AG
    [
        ("Arduino AG. (2023). ", False),
        ("Arduino Mega 2560 Rev3 — Documentación técnica.", True),
        (" https://docs.arduino.cc/", False),
    ],
    # 2. BANA
    [
        ("Braille Authority of North America. (2013). ", False),
        ("Size and Spacing of Braille Characters.", True),
        (" https://www.brailleauthority.org/", False),
    ],
    # 3. CPE
    [
        ("Constitución Política del Estado Plurinacional de Bolivia.", True),
        (" (2009). Gaceta Oficial del Estado Plurinacional de Bolivia.", False),
    ],
    # 4. DS 1893
    [
        ("Decreto Supremo N° 1893. Reglamento de la Ley N° 223 — Ley General para Personas con Discapacidad.", True),
        (" (2014). Gaceta Oficial del Estado Plurinacional de Bolivia.", False),
    ],
    # 5. Ellen MacArthur Foundation
    [
        ("Ellen MacArthur Foundation. (2013). ", False),
        ("Hacia una economía circular: razones económicas para una transición acelerada.", True),
        (" https://www.ellenmacarthurfoundation.org/", False),
    ],
    # 6. Evans
    [
        ("Evans, B. (2021). ", False),
        ("Practical 3D Printers: The Science and Art of 3D Printing.", True),
        (" Apress.", False),
    ],
    # 7. Gibson et al.
    [
        ("Gibson, I., Rosen, D., Stucker, B., y Khorasani, M. (2015). ", False),
        ("Additive Manufacturing Technologies: 3D Printing, Rapid Prototyping, and Direct Digital Manufacturing", True),
        (" (2.ª ed.). Springer.", False),
    ],
    # 8. Hernández-Sampieri et al.
    [
        ("Hernández-Sampieri, R., Fernández-Collado, C., y Baptista-Lucio, P. (2014). ", False),
        ("Metodología de la investigación", True),
        (" (6.ª ed.). McGraw-Hill.", False),
    ],
    # 9. INE
    [
        ("Instituto Nacional de Estadística. (2012). ", False),
        ("Censo Nacional de Población y Vivienda 2012.", True),
        (" https://www.ine.gob.bo/", False),
    ],
    # 10. ISO/ASTM 52900
    [
        ("ISO/ASTM 52900. (2021). ", False),
        ("Additive manufacturing — General principles — Fundamentals and vocabulary.", True),
        (" International Organization for Standardization.", False),
    ],
    # 11. JetBrains
    [
        ("JetBrains. (2023). ", False),
        ("Developer Ecosystem Survey 2023.", True),
        (" https://www.jetbrains.com/lp/devecosystem-2023/", False),
    ],
    # 12. Ley 070
    [
        ("Ley N° 070 — Ley de Educación «Avelino Siñani – Elizardo Pérez».", True),
        (" (2010). Asamblea Legislativa Plurinacional de Bolivia.", False),
    ],
    # 13. Ley 223
    [
        ("Ley N° 223 — Ley General para Personas con Discapacidad.", True),
        (" (2012). Asamblea Legislativa Plurinacional de Bolivia.", False),
    ],
    # 14. Marlin Contributors
    [
        ("Marlin Contributors. (2023). ", False),
        ("Marlin Firmware Documentation (v1.1.x).", True),
        (" https://marlinfw.org/", False),
    ],
    # 15. Mellor
    [
        ("Mellor, C. M. (2006). ", False),
        ("Louis Braille: A Touch of Genius.", True),
        (" National Braille Press.", False),
    ],
    # 16. MinEdu
    [
        ("Ministerio de Educación de Bolivia. (2018). ", False),
        ("Guía de educación inclusiva para personas con discapacidad.", True),
        (" La Paz: Ministerio de Educación.", False),
    ],
    # 17. ONU
    [
        ("Naciones Unidas. (2015). ", False),
        ("Transformar nuestro mundo: la Agenda 2030 para el Desarrollo Sostenible (ODS 4).", True),
        (" https://www.un.org/sustainabledevelopment/es/", False),
    ],
    # 18. OMS
    [
        ("Organización Mundial de la Salud. (2023). ", False),
        ("Informe mundial sobre la visión.", True),
        (" Ginebra: OMS. https://www.who.int/es/", False),
    ],
    # 19. ONCE
    [
        ("Organización Nacional de Ciegos Españoles (ONCE). (s. f.). ", False),
        ("Código Braille Español.", True),
        (" https://www.once.es/", False),
    ],
    # 20. OWASP
    [
        ("OWASP Foundation. (2021). ", False),
        ("OWASP Top Ten — 2021.", True),
        (" https://owasp.org/www-project-top-ten/", False),
    ],
    # 21. Ponce Talancón
    [
        ("Ponce Talancón, H. (2006). La matriz FODA: una alternativa para realizar diagnósticos y determinar estrategias de intervención en las organizaciones. ", False),
        ("Contribuciones a la Economía.", True),
        (" http://www.eumed.net/ce/", False),
    ],
    # 22. Pressman & Maxim
    [
        ("Pressman, R. S., y Maxim, B. R. (2020). ", False),
        ("Ingeniería del software: un enfoque práctico", True),
        (" (9.ª ed.). McGraw-Hill.", False),
    ],
    # 23. RepRap Community
    [
        ("RepRap Community. (2005). ", False),
        ("RepRap — Replicating Rapid Prototyper.", True),
        (" https://reprap.org/", False),
    ],
    # 24. RM 0487/2023
    [
        ("Resolución Ministerial N° 0487/2023. Reglamento de Modalidades de Graduación del Subsistema de Educación Superior de Formación Profesional.", True),
        (" (2023). Ministerio de Educación de Bolivia.", False),
    ],
    # 25. Schwaber & Sutherland
    [
        ("Schwaber, K., y Sutherland, J. (2020). ", False),
        ("La Guía de Scrum: la guía definitiva de Scrum.", True),
        (" https://scrumguides.org/", False),
    ],
    # 26. W3C
    [
        ("W3C. (2018). ", False),
        ("Web Content Accessibility Guidelines (WCAG) 2.1.", True),
        (" https://www.w3.org/TR/WCAG21/", False),
    ],
]

def formatear_run(r, is_italic=False, is_bold=False):
    """Aplica tipografía Arial 11 pt con fuente forzada en XML."""
    r.font.name = "Arial"
    r.font.size = Pt(11)
    if is_italic:
        r.italic = True
    if is_bold:
        r.bold = True
    rPr = r._element.get_or_add_rPr()
    rFonts = rPr.find(qn("w:rFonts"))
    if rFonts is None:
        rFonts = rPr.makeelement(qn("w:rFonts"), {})
        rPr.append(rFonts)
    rFonts.set(qn("w:ascii"), "Arial")
    rFonts.set(qn("w:hAnsi"), "Arial")

def formatear_parrafo_cuerpo(p):
    """Aplica formato de cuerpo general: Arial 11 pt, 1.5 líneas, justificado."""
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    p.paragraph_format.line_spacing = 1.5
    for r in p.runs:
        formatear_run(r, is_italic=bool(r.italic), is_bold=bool(r.bold))

def main():
    print("=== INICIANDO CORRECCIÓN INTEGRAL APA 7 EN DOCX ===")
    
    # 1. Crear copia de seguridad
    print(f"--> Creando respaldo de seguridad: {BAK_DOCX}")
    shutil.copyfile(SRC_DOCX, BAK_DOCX)
    print("  ✔ Respaldo creado exitosamente.")
    
    doc = docx.Document(SRC_DOCX)
    total_parrafos_orig = len(doc.paragraphs)
    total_tablas_orig = len(doc.tables)
    print(f"--> Estado inicial: {total_parrafos_orig} párrafos, {total_tablas_orig} tablas.")
    
    # 2. Corregir citas en cuerpo del texto
    print("\n--- FASE 1: Corrección de Citas en el Cuerpo del Texto ---")
    
    # P[61]
    p61 = doc.paragraphs[61]
    p61.runs[0].text = "Investigación documental:"
    p61.runs[0].bold = True
    p61.runs[1].text = " Se revisaron los costos actuales de embozadoras y equipos comerciales de producción táctil (que superan los $3,000 USD), los estándares internacionales del sistema Braille (Braille Authority of North America [BANA], 2013; UEB), la Ley N° 223 de inclusión educativa boliviana, el Decreto Supremo N° 1893 y proyectos similares documentados en la comunidad RepRap y en repositorios académicos de universidades latinoamericanas."
    p61.runs[1].bold = False
    for r in p61.runs[2:]:
        r.text = ""
    formatear_parrafo_cuerpo(p61)
    p61.runs[0].bold = True
    print("  ✔ P[61]: Corregido doble paréntesis en cita BANA/UEB.")
    
    # P[124]
    p124 = doc.paragraphs[124]
    txt124 = "".join(r.text for r in p124.runs).replace("(Braille Authority of North America, 2013)", "(BANA, 2013)")
    p124.runs[0].text = txt124
    for r in p124.runs[1:]:
        r.text = ""
    formatear_parrafo_cuerpo(p124)
    print("  ✔ P[124]: Simplificada cita narrativa BANA.")
    
    # P[125]
    p125 = doc.paragraphs[125]
    txt125 = "".join(r.text for r in p125.runs).replace(
        "(Braille Authority of North America, 2013)manteniendo",
        "estándar de la BANA (2013), manteniendo"
    )
    p125.runs[0].text = txt125
    for r in p125.runs[1:]:
        r.text = ""
    formatear_parrafo_cuerpo(p125)
    print("  ✔ P[125]: Añadido espacio y redacción fluida en cita BANA.")
    
    # P[132]
    p132 = doc.paragraphs[132]
    txt132 = "".join(r.text for r in p132.runs).replace(
        "por la Braille Authority of North America (Braille Authority of North America, 2013)",
        "por la Braille Authority of North America (BANA, 2013)"
    )
    p132.runs[0].text = txt132
    for r in p132.runs[1:]:
        r.text = ""
    formatear_parrafo_cuerpo(p132)
    print("  ✔ P[132]: Eliminada redundancia en cita BANA.")
    
    # P[134]
    p134 = doc.paragraphs[134]
    txt134 = "".join(r.text for r in p134.runs)
    txt134 = txt134.replace(
        "La Organización Mundial de la Salud (Organización Mundial de la Salud, 2023) define",
        "La Organización Mundial de la Salud (OMS, 2023) define"
    ).replace(
        "información escrita (Organización Mundial de la Salud, 2023)",
        "información escrita (OMS, 2023)."
    )
    p134.runs[0].text = txt134
    for r in p134.runs[1:]:
        r.text = ""
    formatear_parrafo_cuerpo(p134)
    print("  ✔ P[134]: Eliminada redundancia OMS y corregida puntuación final.")
    
    # P[137]
    p137 = doc.paragraphs[137]
    txt137 = "".join(r.text for r in p137.runs)
    txt137 = txt137.replace("(Constitución Política del Estado Plurinacional de Bolivia, 2009)", "(CPE, 2009)")
    txt137 = txt137.replace("(Ley N° 223 – Ley General para Personas con Discapacidad, 2012)", "(2012)")
    txt137 = txt137.replace("(Decreto Supremo N° 1893 – Reglamento de la Ley N° 223, 2014)", "(2014)")
    p137.runs[0].text = txt137
    for r in p137.runs[1:]:
        r.text = ""
    formatear_parrafo_cuerpo(p137)
    print("  ✔ P[137]: Eliminada redundancia de títulos legislativos en paréntesis.")
    
    # P[164]
    p164 = doc.paragraphs[164]
    txt164 = "".join(r.text for r in p164.runs).replace("Pressman & Maxim, 2020", "Pressman y Maxim, 2020")
    p164.runs[0].text = txt164
    for r in p164.runs[1:]:
        r.text = ""
    formatear_parrafo_cuerpo(p164)
    print("  ✔ P[164]: Conjunción castellana 'y' en Pressman y Maxim.")
    
    # P[168]
    p168 = doc.paragraphs[168]
    txt168 = "".join(r.text for r in p168.runs).replace("Schwaber & Sutherland, 2020", "Schwaber y Sutherland, 2020")
    p168.runs[0].text = txt168
    for r in p168.runs[1:]:
        r.text = ""
    formatear_parrafo_cuerpo(p168)
    print("  ✔ P[168]: Conjunción castellana 'y' en Schwaber y Sutherland.")
    
    # P[177]
    p177 = doc.paragraphs[177]
    p177.runs[0].text = "Método deductivo:"
    p177.runs[1].text = " "
    p177.runs[2].text = "se aplicó para derivar, a partir del marco normativo vigente (Ley N° 223, D.S. 1893, CPE, ODS 4) y de los estándares técnicos internacionales (UEB, BANA, 2013; WCAG 2.1), los requisitos funcionales y no funcionales del sistema, así como los criterios de aceptación que debe satisfacer la solución propuesta."
    for r in p177.runs[3:]:
        r.text = ""
    formatear_run(p177.runs[2], is_italic=False, is_bold=False)
    print("  ✔ P[177]: Eliminado doble paréntesis preservando estilo de título en run 0.")
    
    # 3. Reformatear Lista de Referencias
    print("\n--- FASE 2: Reformateo Multi-Run de las 26 Referencias APA 7 ---")
    header_idx = None
    for idx, p in enumerate(doc.paragraphs):
        if "FUENTES DE INFORMACIÓN Y BIBLIOGRAFÍA" in p.text.upper():
            header_idx = idx
            break
            
    if header_idx is None:
        print("✗ ERROR CRÍTICO: No se encontró el encabezado de FUENTES DE INFORMACIÓN!")
        sys.exit(1)
        
    print(f"  Encabezado localizado en P[{header_idx}].")
    assert len(REFERENCIAS_APA7) == 26, "La lista debe contener exactamente 26 referencias."
    
    for i, chunks in enumerate(REFERENCIAS_APA7):
        p_ref = doc.paragraphs[header_idx + 1 + i]
        
        # Limpiar runs
        p_ref.text = ""
        
        # Aplicar formato de párrafo APA 7
        p_ref.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p_ref.paragraph_format.line_spacing = 1.5
        p_ref.paragraph_format.left_indent = Cm(1.27)
        p_ref.paragraph_format.first_line_indent = Cm(-1.27)
        
        # Insertar runs estructurados
        for text_part, is_it in chunks:
            r = p_ref.add_run(text_part)
            formatear_run(r, is_italic=is_it, is_bold=False)
            
    print(f"  ✔ {len(REFERENCIAS_APA7)} referencias reformateadas con cursivas, sangría francesa (1.27 cm) y 'y' en español.")
    
    # 4. Verificación de no-regresión estructural
    print("\n--- FASE 3: Verificación de Integridad Estructural ---")
    total_parrafos_fin = len(doc.paragraphs)
    total_tablas_fin = len(doc.tables)
    
    print(f"  Párrafos iniciales: {total_parrafos_orig} | Párrafos finales: {total_parrafos_fin}")
    print(f"  Tablas iniciales:   {total_tablas_orig} | Tablas finales:   {total_tablas_fin}")
    
    assert total_parrafos_orig == total_parrafos_fin, "ERROR: La cantidad de párrafos varió!"
    assert total_tablas_orig == total_tablas_fin, "ERROR: La cantidad de tablas varió!"
    
    # Guardar
    doc.save(SRC_DOCX)
    print(f"\n✅ Guardado exitoso de {SRC_DOCX} sin errores.")

if __name__ == "__main__":
    main()
