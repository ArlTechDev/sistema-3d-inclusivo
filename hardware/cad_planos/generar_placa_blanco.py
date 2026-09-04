# -*- coding: utf-8 -*-
"""
Script Paramétrico para FreeCAD: Generador de Placa Base en Blanco (Lienzo Braille)
Sistema Braille Inclusivo — Hardware / CAD

Instrucciones de uso en FreeCAD:
1. Abrir FreeCAD.
2. Abrir la consola de Python (Ver -> Paneles -> Consola de Python) o el editor de Macros.
3. Ejecutar:
   exec(open("/mnt/extens/incos/utils/web3/sistema_inclusivo/hardware/cad_planos/generar_placa_blanco.py").read())
4. Se generará la geometría en 3D y se exportará automáticamente el archivo STL a:
   hardware/exportaciones_3d/ficha_base_blanco.stl
"""

import os
import FreeCAD as App
import Part
import Mesh

# ==============================================================================
# PARÁMETROS CONFIGURABLES DE LA PLACA BASE (en milímetros)
# ==============================================================================
ANCHO_X = 80.0          # Largo total de la placa (mm)
ALTO_Y = 30.0           # Ancho total de la placa (mm)
GROSOR_Z = 3.0          # Espesor total de la placa base (mm) -> placa_z_altura en BD
RADIO_REDONDEO = 2.0    # Radio de redondeo en esquinas (ergonomía táctil inclusiva)

# Rutas de salida del repositorio
REPO_DIR = "/mnt/extens/incos/utils/web3/sistema_inclusivo"
STL_SALIDA = os.path.join(REPO_DIR, "hardware", "exportaciones_3d", "ficha_base_blanco.stl")
FCSTD_SALIDA = os.path.join(REPO_DIR, "hardware", "cad_planos", "ficha_base_blanco.FCStd")

# ==============================================================================
# GENERACIÓN DE LA GEOMETRÍA
# ==============================================================================
def generar_placa():
    doc_name = "Placa_Base_Braille"
    doc = App.ActiveDocument
    if doc is None or doc.Name != doc_name:
        doc = App.newDocument(doc_name)

    # Crear prisma rectangular base
    caja = Part.makeBox(ANCHO_X, ALTO_Y, GROSOR_Z)

    # Redondear esquinas verticales (bordes paralelos a Z) para ergonomía táctil
    if RADIO_REDONDEO > 0:
        aristas_verticales = []
        for edge in caja.Edges:
            v1 = edge.Vertexes[0].Point
            v2 = edge.Vertexes[1].Point
            # Si X e Y son iguales, la arista es paralela al eje Z
            if abs(v1.x - v2.x) < 1e-4 and abs(v1.y - v2.y) < 1e-4:
                aristas_verticales.append(edge)

        if aristas_verticales:
            forma_final = caja.makeFillet(RADIO_REDONDEO, aristas_verticales)
        else:
            forma_final = caja
    else:
        forma_final = caja

    # Asignar al documento FreeCAD
    nombre_objeto = "Ficha_Base_En_Blanco"
    obj = doc.getObject(nombre_objeto)
    if obj is None:
        obj = doc.addObject("Part::Feature", nombre_objeto)

    obj.Shape = forma_final
    doc.recompute()

    # Exportar STL
    os.makedirs(os.path.dirname(STL_SALIDA), exist_ok=True)
    Mesh.export([obj], STL_SALIDA)

    # Guardar archivo .FCStd
    doc.saveAs(FCSTD_SALIDA)

    print("\n=======================================================")
    print(" PLACA BASE EN BLANCO GENERADA CON ÉXITO")
    print(f" - Dimensiones: {ANCHO_X} mm x {ALTO_Y} mm x {GROSOR_Z} mm (Grosor Z)")
    print(f" - Esquinas redondeadas: {RADIO_REDONDEO} mm")
    print(f" - Archivo FreeCAD guardado en: {FCSTD_SALIDA}")
    print(f" - Archivo STL exportado a:    {STL_SALIDA}")
    print("=======================================================\n")

generar_placa()
