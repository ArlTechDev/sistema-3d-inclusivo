#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Pipeline Automatizado de Recursos 3D: STL -> GLB (Web) + G-Code (Laminado CNC)
Sistema Braille Inclusivo — Hardware & Software

Uso:
    python3 scripts/procesar_recurso_3d.py <ruta_al_modelo.stl> [--color "#156c59"] [--output-dir <directorio>]
"""

import os
import sys
import re
import argparse
import subprocess

REPO_ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DEFAULT_INI = os.path.join(REPO_ROOT, "hardware", "marlin_firmware", "perfil_prusa_i3_08mm.ini")
BLENDER_SCRIPT = os.path.join(REPO_ROOT, "scripts", "convertir_stl_a_glb.py")

def verificar_dependencias():
    # Verificar Blender
    res_blender = subprocess.run(["which", "blender"], capture_output=True, text=True)
    if res_blender.returncode != 0:
        print("[ERROR] Blender no esta instalado o no se encuentra en el PATH.")
        sys.exit(1)

    # Verificar PrusaSlicer
    res_slicer = subprocess.run(["which", "prusa-slicer"], capture_output=True, text=True)
    if res_slicer.returncode != 0:
        print("[ERROR] prusa-slicer no esta instalado. Instálalo con: sudo pacman -S prusa-slicer")
        sys.exit(1)

def convertir_a_glb_y_thumb(stl_path, glb_path, thumb_path, color_hex):
    print("\n" + "="*60)
    print(" 1. GENERANDO VISUALIZACION 3D WEB (.GLB) Y MINIATURA (.PNG)")
    print("="*60)
    cmd = [
        "blender",
        "--background",
        "--factory-startup",
        "--python", BLENDER_SCRIPT,
        "--",
        stl_path,
        glb_path,
        color_hex,
        thumb_path
    ]
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0 or not os.path.exists(glb_path):
        print("[ERROR] Falló la conversión a GLB/PNG con Blender:")
        print(result.stdout)
        print(result.stderr)
        return False

    tamanio_kb = os.path.getsize(glb_path) / 1024.0
    print(f" -> [OK] Archivo GLB generado: {glb_path} ({tamanio_kb:.1f} KB)")
    if os.path.exists(thumb_path):
        tamanio_thumb = os.path.getsize(thumb_path) / 1024.0
        print(f" -> [OK] Miniatura PNG generada: {thumb_path} ({tamanio_thumb:.1f} KB)")
    return True

def laminar_a_gcode(stl_path, gcode_path, ini_path):
    print("\n" + "="*60)
    print(" 2. LAMINANDO G-CODE CNC CON PRUSASLICER (.GCODE)")
    print("="*60)
    if not os.path.exists(ini_path):
        print(f"[ERROR] No se encuentra el perfil INI: {ini_path}")
        return False

    cmd = [
        "prusa-slicer",
        "--load", ini_path,
        "--slice",
        "--output", gcode_path,
        stl_path
    ]
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0 or not os.path.exists(gcode_path):
        print("[ERROR] Falló el laminado con PrusaSlicer CLI:")
        print(result.stdout)
        print(result.stderr)
        return False

    print(f" -> [OK] Archivo G-Code generado: {gcode_path}")
    return True

def extraer_metricas_gcode(gcode_path):
    metricas = {
        "tiempo_estimado": "No disponible",
        "gramos_pla": 0.0,
        "longitud_filamento_mm": 0.0,
        "altura_total_mm": 0.0
    }
    with open(gcode_path, "r", encoding="utf-8", errors="ignore") as f:
        contenido = f.read()

    # Tiempo estimado
    m_tiempo = re.search(r'; estimated printing time \(normal mode\) = ([^\n]+)', contenido)
    if m_tiempo:
        metricas["tiempo_estimado"] = m_tiempo.group(1).strip()

    # Gramos de filamento
    m_gramos = re.search(r'; (?:total )?filament used \[g\] = ([0-9\.]+)', contenido)
    if m_gramos and float(m_gramos.group(1)) > 0:
        metricas["gramos_pla"] = float(m_gramos.group(1))
    else:
        # Fallback usando volumen en cm3 * densidad estándar PLA (1.24 g/cm3)
        m_vol = re.search(r'; filament used \[cm3\] = ([0-9\.]+)', contenido)
        if m_vol:
            metricas["gramos_pla"] = round(float(m_vol.group(1)) * 1.24, 2)

    # Longitud de filamento
    m_longitud = re.search(r'; filament used \[mm\] = ([0-9\.]+)', contenido)
    if m_longitud:
        metricas["longitud_filamento_mm"] = float(m_longitud.group(1))

    # Altura máxima Z
    matches_z = re.findall(r'G1 Z([0-9\.]+)', contenido)
    if matches_z:
        metricas["altura_total_mm"] = max(float(z) for z in matches_z)

    return metricas

def main():
    parser = argparse.ArgumentParser(description="Pipeline Automatizado STL -> GLB + G-Code")
    parser.add_argument("stl_file", help="Ruta al archivo STL de entrada")
    parser.add_argument("--color", default="#156c59", help="Color HEX para el modelo GLB (defecto: #156c59)")
    parser.add_argument("--ini", default=DEFAULT_INI, help="Ruta al archivo de configuracion INI")
    parser.add_argument("--output-dir", default=None, help="Directorio de salida (defecto: mismo directorio que STL)")

    args = parser.parse_args()

    if not os.path.exists(args.stl_file):
        print(f"[ERROR] Archivo no encontrado: {args.stl_file}")
        sys.exit(1)

    verificar_dependencias()

    stl_abs = os.path.abspath(args.stl_file)
    base_dir = os.path.abspath(args.output_dir) if args.output_dir else os.path.dirname(stl_abs)
    nombre_base = os.path.splitext(os.path.basename(stl_abs))[0]

    glb_out = os.path.join(base_dir, f"{nombre_base}.glb")
    thumb_out = os.path.join(base_dir, f"{nombre_base}_thumb.png")
    gcode_out = os.path.join(base_dir, f"{nombre_base}_base.gcode")

    print("\n" + "#"*60)
    print(" INICIANDO PIPELINE DE AUTOMATIZACION DE RECURSOS 3D")
    print(f" Recurso: {nombre_base}")
    print(f" Entrada: {stl_abs}")
    print("#"*60)

    # 1. Convertir a GLB y renderizar miniatura
    ok_glb = convertir_a_glb_y_thumb(stl_abs, glb_out, thumb_out, args.color)
    if not ok_glb:
        sys.exit(1)

    # 2. Laminar a G-Code
    ok_gcode = laminar_a_gcode(stl_abs, gcode_out, args.ini)
    if not ok_gcode:
        sys.exit(1)

    # 3. Métricas
    metricas = extraer_metricas_gcode(gcode_out)

    print("\n" + "="*60)
    print(" RESUMEN DE PROCESAMIENTO Y METRICAS PARA LARAVEL")
    print("="*60)
    print(f" • Imagen de Portada (PNG Web):   {thumb_out}")
    print(f" • Archivo GLB (Visor 3D Web):    {glb_out}")
    print(f" • Archivo G-Code (CNC Marlin):   {gcode_out}")
    print(f" • Tiempo estimado de impresion:  {metricas['tiempo_estimado']}")
    print(f" • Gramos PLA calculados:         {metricas['gramos_pla']:.2f} g")
    print(f" • Longitud de filamento:         {metricas['longitud_filamento_mm']:.1f} mm")
    print(f" • Altura Z total de la pieza:    {metricas['altura_total_mm']:.2f} mm")
    print("="*60)
    print(" [LISTO] Los archivos estan listos para subirse a la plataforma.")
    print("="*60 + "\n")

if __name__ == "__main__":
    main()
