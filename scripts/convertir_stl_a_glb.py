# -*- coding: utf-8 -*-
"""
Conversor de STL a GLB con Blender en segundo plano (Headless) + Generador de Miniaturas
Sistema Braille Inclusivo — Visualización Web

Uso:
    blender --background --factory-startup --python scripts/convertir_stl_a_glb.py -- <archivo_entrada.stl> <archivo_salida.glb> ["#hex_color"] [<archivo_miniatura.png>]

Ejemplo:
    blender --background --factory-startup --python scripts/convertir_stl_a_glb.py -- modelo.stl modelo.glb "#156c59" modelo_thumb.png
"""

import sys
import os
import math
import mathutils
import bpy

def hex_to_rgb(hex_str):
    hex_str = hex_str.lstrip('#')
    if len(hex_str) == 6:
        r = int(hex_str[0:2], 16) / 255.0
        g = int(hex_str[2:4], 16) / 255.0
        b = int(hex_str[4:6], 16) / 255.0
        # sRGB a Linear aproximado
        r = r ** 2.2
        g = g ** 2.2
        b = b ** 2.2
        return (r, g, b, 1.0)
    return (0.082, 0.423, 0.349, 1.0) # #156c59 por defecto

def limpiar_escena():
    bpy.ops.wm.read_factory_settings(use_empty=True)

def importar_stl(stl_path):
    if not os.path.exists(stl_path):
        raise FileNotFoundError(f"No se encontro el archivo STL: {stl_path}")

    try:
        bpy.ops.wm.stl_import(filepath=stl_path)
    except AttributeError:
        bpy.ops.import_mesh.stl(filepath=stl_path)

    objetos = [obj for obj in bpy.context.selected_objects if obj.type == 'MESH']
    if not objetos:
        raise ValueError("No se encontro ninguna malla valida en el archivo STL importado.")
    return objetos[0]

def aplicar_material_y_suavizado(obj, color_rgba):
    bpy.context.view_layer.objects.active = obj

    try:
        bpy.ops.object.shade_smooth()
    except Exception:
        pass

    # Crear material PBR
    mat = bpy.data.materials.new(name="MaterialRecurso")
    mat.use_nodes = True
    nodes = mat.node_tree.nodes
    bsdf = nodes.get("Principled BSDF")
    if bsdf:
        bsdf.inputs["Base Color"].default_value = color_rgba
        if "Roughness" in bsdf.inputs:
            bsdf.inputs["Roughness"].default_value = 0.35
        if "Metallic" in bsdf.inputs:
            bsdf.inputs["Metallic"].default_value = 0.05

    obj.data.materials.clear()
    obj.data.materials.append(mat)

def exportar_glb(output_path):
    os.makedirs(os.path.dirname(os.path.abspath(output_path)), exist_ok=True)
    bpy.ops.export_scene.gltf(
        filepath=output_path,
        export_format='GLB',
        use_selection=False,
        export_apply=True
    )

def renderizar_miniatura(obj, png_path):
    os.makedirs(os.path.dirname(os.path.abspath(png_path)), exist_ok=True)
    scene = bpy.context.scene

    # Configuración de Render (PNG transparente de alta calidad y muy ligero)
    scene.render.image_settings.file_format = 'PNG'
    scene.render.image_settings.color_mode = 'RGBA'
    scene.render.film_transparent = True
    scene.render.resolution_x = 640
    scene.render.resolution_y = 400
    scene.render.resolution_percentage = 100

    # Calcular centro y dimensiones del objeto
    bbox_corners = [obj.matrix_world @ mathutils.Vector(corner) for corner in obj.bound_box]
    center = sum(bbox_corners, mathutils.Vector((0, 0, 0))) / 8.0
    dims = obj.dimensions
    max_dim = max(dims.x, dims.y, dims.z, 1.0)

    # Crear Luces tipo Sol
    light_data = bpy.data.lights.new(name="LuzSol", type='SUN')
    light_data.energy = 4.0
    light_obj = bpy.data.objects.new(name="LuzSol", object_data=light_data)
    scene.collection.objects.link(light_obj)
    light_obj.rotation_euler = (math.radians(45), math.radians(20), math.radians(40))

    fill_data = bpy.data.lights.new(name="LuzRelleno", type='SUN')
    fill_data.energy = 2.0
    fill_obj = bpy.data.objects.new(name="LuzRelleno", object_data=fill_data)
    scene.collection.objects.link(fill_obj)
    fill_obj.rotation_euler = (math.radians(-30), math.radians(-40), math.radians(-140))

    # Crear Cámara
    cam_data = bpy.data.cameras.new(name="CamaraThumb")
    cam_data.lens = 55
    cam_obj = bpy.data.objects.new(name="CamaraThumb", object_data=cam_data)
    scene.collection.objects.link(cam_obj)
    scene.camera = cam_obj

    # Posicionar cámara en perspectiva isométrica
    distancia = max_dim * 2.2
    cam_obj.location = (center.x + distancia * 0.75, center.y - distancia * 1.15, center.z + distancia * 0.85)

    # Objeto vacío en el centro para apuntar la cámara
    empty_center = bpy.data.objects.new("CenterTarget", None)
    empty_center.location = center
    scene.collection.objects.link(empty_center)

    track = cam_obj.constraints.new(type='TRACK_TO')
    track.target = empty_center
    track.track_axis = 'TRACK_NEGATIVE_Z'
    track.up_axis = 'UP_Y'

    scene.render.filepath = png_path
    bpy.ops.render.render(write_still=True)

def main():
    argv = sys.argv
    if "--" not in argv:
        print("Error: Debes pasar los argumentos despues de '--'")
        sys.exit(1)

    args = argv[argv.index("--") + 1:]
    if len(args) < 2:
        print("Uso: blender --background --python convertir_stl_a_glb.py -- <entrada.stl> <salida.glb> [hex_color] [thumb_salida.png]")
        sys.exit(1)

    stl_in = args[0]
    glb_out = args[1]
    color_hex = args[2] if len(args) > 2 else "#156c59"
    thumb_out = args[3] if len(args) > 3 else None

    print(f"\n[BLENDER GLB CONVERTER] Procesando: {stl_in} -> {glb_out}")
    limpiar_escena()
    obj = importar_stl(stl_in)
    color_rgba = hex_to_rgb(color_hex)
    aplicar_material_y_suavizado(obj, color_rgba)
    exportar_glb(glb_out)
    print(f"[BLENDER GLB CONVERTER] Exportacion GLB exitosa: {glb_out} ({os.path.getsize(glb_out)} bytes)")

    if thumb_out:
        print(f"[BLENDER THUMB RENDERER] Renderizando miniatura: {thumb_out}")
        renderizar_miniatura(obj, thumb_out)
        print(f"[BLENDER THUMB RENDERER] Miniatura renderizada con exito: {thumb_out} ({os.path.getsize(thumb_out)} bytes)\n")

if __name__ == "__main__":
    main()
