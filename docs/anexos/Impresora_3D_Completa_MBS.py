import FreeCAD as App
import Part
from FreeCAD import Vector

# 1. Crear un único documento unificado para la presentación final
if "Impresora_3D_Completa_MBS" in App.listDocuments():
    App.closeDocument("Impresora_3D_Completa_MBS")

doc = App.newDocument("Impresora_3D_Completa_MBS")

# ─── PALETA DE COLORES DE INGENIERÍA ──────────────────────────────────────
color_madera        = (0.87, 0.72, 0.53)  # Pino claro (Estructura principal)
color_madera_oscura = (0.50, 0.30, 0.10)  # Madera oscura (Tarugos de ensamble)
color_aluminio      = (0.80, 0.80, 0.85)  # Aluminio brillante (SCS8UU, acoples)
color_acero         = (0.70, 0.70, 0.70)  # Acero gris (Varillas, ejes, poleas)
color_motor         = (0.20, 0.20, 0.20)  # Gris oscuro/Negro (Cuerpo motores NEMA)
color_pcb_verde     = (0.10, 0.45, 0.10)  # Verde (Arduino)
color_pcb_rojo      = (0.85, 0.10, 0.10)  # Rojo (RAMPS 1.4)
color_pantalla      = (0.05, 0.05, 0.20)  # Azul oscuro (LCD)
color_amarillo      = (0.95, 0.85, 0.10)  # Cables de potencia

# ==============================================================================
# ─── MÓDULO Y: LA BASE DE LA IMPRESORA ────────────────────────────────────────
# ==============================================================================

# Herramientas de corte modular laterales (Y=205 para tarugo, Y=245 para perno)
agujero_tarugo_izq = Part.makeCylinder(4, 18, Vector(0, 205, 40), Vector(1, 0, 0))
agujero_perno_izq = Part.makeCylinder(3, 18, Vector(0, 245, 40), Vector(1, 0, 0))

# Lateral Izquierdo con cortes modulares
base_lat1 = Part.makeBox(18, 450, 80)
lat1_final = base_lat1.cut(agujero_tarugo_izq).cut(agujero_perno_izq)
obj_lat1 = doc.addObject("Part::Feature", "Y_Lateral_Izquierdo")
obj_lat1.Shape = lat1_final
obj_lat1.Placement.Base = Vector(0, 0, 0)
obj_lat1.ViewObject.ShapeColor = color_madera

# Lateral Derecho con cortes modulares posicionados en X=238
base_lat2 = Part.makeBox(18, 450, 80)
lat2_final = base_lat2.cut(agujero_tarugo_izq).cut(agujero_perno_izq)
obj_lat2 = doc.addObject("Part::Feature", "Y_Lateral_Derecho")
obj_lat2.Shape = lat2_final
obj_lat2.Placement.Base = Vector(238, 0, 0)
obj_lat2.ViewObject.ShapeColor = color_madera

# Tabla Frontal
base_frontal = Part.makeBox(220, 18, 80)
ag_izq_f = Part.makeCylinder(4, 18, Vector(30, 0, 40), Vector(0, 1, 0))
ag_der_f = Part.makeCylinder(4, 18, Vector(190, 0, 40), Vector(0, 1, 0))
frontal_final = base_frontal.cut(ag_izq_f).cut(ag_der_f)
obj_front = doc.addObject("Part::Feature", "Y_Tabla_Frontal")
obj_front.Shape = frontal_final
obj_front.Placement.Base = Vector(18, 0, 0)
obj_front.ViewObject.ShapeColor = color_madera

# Tabla Trasera con alojamiento para Motor Y
base_trasera = Part.makeBox(220, 18, 80)
hueco_motor_y = Part.makeBox(42, 18, 42, Vector(89, 0, 38))
trasera_final = base_trasera.cut(ag_izq_f).cut(ag_der_f).cut(hueco_motor_y)
obj_tras = doc.addObject("Part::Feature", "Y_Tabla_Trasera")
obj_tras.Shape = trasera_final
obj_tras.Placement.Base = Vector(18, 432, 0)
obj_tras.ViewObject.ShapeColor = color_madera

# Varillas de Acero Lisas Y
varilla_y_izq = Part.makeCylinder(4, 450, Vector(0, 0, 0), Vector(0, 1, 0))
obj_vy1 = doc.addObject("Part::Feature", "Y_Varilla_Acero_Izq")
obj_vy1.Shape = varilla_y_izq
obj_vy1.Placement.Base = Vector(48, 0, 40)
obj_vy1.ViewObject.ShapeColor = color_acero

varilla_y_der = Part.makeCylinder(4, 450, Vector(0, 0, 0), Vector(0, 1, 0))
obj_vy2 = doc.addObject("Part::Feature", "Y_Varilla_Acero_Der")
obj_vy2.Shape = varilla_y_der
obj_vy2.Placement.Base = Vector(208, 0, 40)
obj_vy2.ViewObject.ShapeColor = color_acero

# Rodamientos SCS8UU (Bloques de aluminio bajo la cama)
scs1 = Part.makeBox(34, 30, 22)
obj_scs1 = doc.addObject("Part::Feature", "Y_SCS8UU_Izq_Frente")
obj_scs1.Shape = scs1
obj_scs1.Placement.Base = Vector(31, 100, 29)
obj_scs1.ViewObject.ShapeColor = color_aluminio

scs2 = Part.makeBox(34, 30, 22)
obj_scs2 = doc.addObject("Part::Feature", "Y_SCS8UU_Izq_Atras")
obj_scs2.Shape = scs2
obj_scs2.Placement.Base = Vector(31, 300, 29)
obj_scs2.ViewObject.ShapeColor = color_aluminio

scs3 = Part.makeBox(34, 30, 22)
obj_scs3 = doc.addObject("Part::Feature", "Y_SCS8UU_Der_Centro")
obj_scs3.Shape = scs3
obj_scs3.Placement.Base = Vector(191, 200, 29)
obj_scs3.ViewObject.ShapeColor = color_aluminio

# Cama Móvil A4 (Semitransparente)
cama = Part.makeBox(220, 310, 9)
obj_cama = doc.addObject("Part::Feature", "Y_Cama_Movil_A4")
obj_cama.Shape = cama
obj_cama.Placement.Base = Vector(18, 70, 45)
obj_cama.ViewObject.ShapeColor = (0.9, 0.9, 0.9)
obj_cama.ViewObject.Transparency = 40

# Motor Eje Y
cuerpo_motor_y = Part.makeBox(42, 40, 42)
obj_motory = doc.addObject("Part::Feature", "Y_Motor_NEMA17")
obj_motory.Shape = cuerpo_motor_y
obj_motory.Placement.Base = Vector(107, 450, 38)
obj_motory.ViewObject.ShapeColor = color_motor

# Eje del Motor Y
eje_motory = Part.makeCylinder(2.5, 24, Vector(0,0,0), Vector(0,-1,0))
obj_ejey = doc.addObject("Part::Feature", "Y_Eje_Motor")
obj_ejey.Shape = eje_motory
obj_ejey.Placement.Base = Vector(128, 450, 59)
obj_ejey.ViewObject.ShapeColor = color_acero

# Soporte y Polea Loca Frontal Y
soporte_poleay = Part.makeBox(20, 20, 15)
obj_soporte = doc.addObject("Part::Feature", "Y_Soporte_Polea")
obj_soporte.Shape = soporte_poleay
obj_soporte.Placement.Base = Vector(118, 18, 50)
obj_soporte.ViewObject.ShapeColor = color_madera

poleay = Part.makeCylinder(8, 12, Vector(0,0,0), Vector(0,0,1))
obj_poleay = doc.addObject("Part::Feature", "Y_Polea_Loca")
obj_poleay.Shape = poleay
obj_poleay.Placement.Base = Vector(128, 28, 65)
obj_poleay.ViewObject.ShapeColor = color_acero

# Tarugos de Ensamble Físicos (Sobresalen 10mm de los laterales)
tarugo_izq = Part.makeCylinder(4, 20, Vector(-10, 205, 40), Vector(1,0,0))
obj_t_izq = doc.addObject("Part::Feature", "Y_Tarugo_Izq")
obj_t_izq.Shape = tarugo_izq
obj_t_izq.ViewObject.ShapeColor = color_madera_oscura

tarugo_der = Part.makeCylinder(4, 20, Vector(246, 205, 40), Vector(1,0,0))
obj_t_der = doc.addObject("Part::Feature", "Y_Tarugo_Der")
obj_t_der.Shape = tarugo_der
obj_t_der.ViewObject.ShapeColor = color_madera_oscura


# ==============================================================================
# ─── MÓDULO Z/X: EL ARCO VERTICAL Y EL EXTRUSOR ───────────────────────────────
# ==============================================================================

# Pilares Verticales del Arco (Abrazan los laterales en Y=185..265)
# Pilar Izquierdo (X: -18..0) con agujeros de acople
base_pilar_izq = Part.makeBox(18, 80, 400)
ag_t_pilar = Part.makeCylinder(4, 18, Vector(0, 20, 40), Vector(1,0,0)) # Entrada tarugo
ag_p_pilar = Part.makeCylinder(3, 18, Vector(0, 60, 40), Vector(1,0,0)) # Entrada perno
pilar_izq_final = base_pilar_izq.cut(ag_t_pilar).cut(ag_p_pilar)

obj_p_izq = doc.addObject("Part::Feature", "Z_Pilar_Izquierdo")
obj_p_izq.Shape = pilar_izq_final
obj_p_izq.Placement.Base = Vector(-18, 185, 0)
obj_p_izq.ViewObject.ShapeColor = color_madera

# Pilar Derecho (X: 256..274)
base_pilar_der = Part.makeBox(18, 80, 400)
pilar_der_final = base_pilar_der.cut(ag_t_pilar).cut(ag_p_pilar)
obj_p_der = doc.addObject("Part::Feature", "Z_Pilar_Derecho")
obj_p_der.Shape = pilar_der_final
obj_p_der.Placement.Base = Vector(256, 185, 0)
obj_p_der.ViewObject.ShapeColor = color_madera

# Travesaño Superior (Une ambos pilares en la parte alta Z=400)
travesano = Part.makeBox(256, 80, 18)
obj_trav = doc.addObject("Part::Feature", "Z_Travesano_Superior")
obj_trav.Shape = travesano
obj_trav.Placement.Base = Vector(0, 185, 400)
obj_trav.ViewObject.ShapeColor = color_madera

# Varillas Lisas Verticales Z
varilla_z_izq = Part.makeCylinder(4, 380, Vector(0,0,0), Vector(0,0,1))
obj_vz1 = doc.addObject("Part::Feature", "Z_Varilla_Lisa_Izq")
obj_vz1.Shape = varilla_z_izq
obj_vz1.Placement.Base = Vector(48, 225, 20)
obj_vz1.ViewObject.ShapeColor = color_acero

varilla_z_der = Part.makeCylinder(4, 380, Vector(0,0,0), Vector(0,0,1))
obj_vz2 = doc.addObject("Part::Feature", "Z_Varilla_Lisa_Der")
obj_vz2.Shape = varilla_z_der
obj_vz2.Placement.Base = Vector(208, 225, 20)
obj_vz2.ViewObject.ShapeColor = color_acero

# Motores Eje Z (Doble tracción en la base del arco)
motor_z_izq = Part.makeBox(42, 42, 40)
obj_mz1 = doc.addObject("Part::Feature", "Z_Motor_Izq")
obj_mz1.Shape = motor_z_izq
obj_mz1.Placement.Base = Vector(10, 204, 0)
obj_mz1.ViewObject.ShapeColor = color_motor

motor_z_der = Part.makeBox(42, 42, 40)
obj_mz2 = doc.addObject("Part::Feature", "Z_Motor_Der")
obj_mz2.Shape = motor_z_der
obj_mz2.Placement.Base = Vector(204, 204, 0)
obj_mz2.ViewObject.ShapeColor = color_motor

# --- EJE X HORIZONTAL (GUIAS Y EXTRUSOR) ---

# Varillas Lisas Horizontales Eje X (Cruzan a lo largo del arco a Z=300 y Z=330)
varilla_x1 = Part.makeCylinder(4, 256, Vector(0,0,0), Vector(1,0,0))
obj_vx1 = doc.addObject("Part::Feature", "X_Varilla_Lisa_Inf")
obj_vx1.Shape = varilla_x1
obj_vx1.Placement.Base = Vector(0, 225, 300)
obj_vx1.ViewObject.ShapeColor = color_acero

varilla_x2 = Part.makeCylinder(4, 256, Vector(0,0,0), Vector(1,0,0))
obj_vx2 = doc.addObject("Part::Feature", "X_Varilla_Lisa_Sup")
obj_vx2.Shape = varilla_x2
obj_vx2.Placement.Base = Vector(0, 225, 330)
obj_vx2.ViewObject.ShapeColor = color_acero

# Carro del Extrusor (Desplazable en X)
carro_x = Part.makeBox(60, 40, 50)
obj_cx = doc.addObject("Part::Feature", "X_Carro_Extrusor")
obj_cx.Shape = carro_x
obj_cx.Placement.Base = Vector(98, 205, 295)
obj_cx.ViewObject.ShapeColor = color_aluminio

# Extrusor MK8 Térmico (Fundidor de PLA)
cuerpo_mk8 = Part.makeBox(42, 42, 42)
obj_mk8 = doc.addObject("Part::Feature", "X_Extrusor_MK8")
obj_mk8.Shape = cuerpo_mk8
obj_mk8.Placement.Base = Vector(107, 163, 299)
obj_mk8.ViewObject.ShapeColor = color_motor

# Boquilla de Latón (Nozzle) apuntando hacia abajo
boquilla = Part.makeCone(4, 0.4, 10, Vector(0,0,0), Vector(0,0,-1))
obj_boq = doc.addObject("Part::Feature", "X_Nozzle_Laton")
obj_boq.Shape = boquilla
obj_boq.Placement.Base = Vector(128, 184, 299)
obj_boq.ViewObject.ShapeColor = (0.8, 0.55, 0.2) # Color bronce/cobre


# ==============================================================================
# ─── MÓDULO CEREBRO: LA CAJA DE CONTROL ELECTRÓNICA ───────────────────────────
# ==============================================================================
# Ubicado a la izquierda del chasis principal (X = -250, Y = 90) para un render de escritorio limpio

OX = -250
OY = 90
OZ = 0

# Caja de Madera
exterior_c = Part.makeBox(180, 240, 110)
interior_c = Part.makeBox(180 - PARED*2, 240 - PARED*2, 110 - PARED)
interior_c.Placement.Base = Vector(PARED, PARED, PARED)
caja_c = exterior_c.cut(interior_c)

# Agujero frontal: conector ATX 24 pines
ag_atx_c = Part.makeBox(51, PARED + 2, 15, Vector(180//2 - 25, -1, 110//2 - 7))
caja_c = caja_c.cut(ag_atx_c)

# Agujero USB
ag_usb_c = Part.makeBox(14, PARED + 2, 7, Vector(180//2 + 30, -1, 110//2 - 3))
caja_c = caja_c.cut(ag_usb_c)

# Agujero lector SD
ag_sd_c = Part.makeBox(32, 4, PARED + 2, Vector(-1, 240//2 - 16, 110//2 - 2))
caja_c = caja_c.cut(ag_sd_c)

# Agujero de encendido y LED
ag_on_c = Part.makeCylinder(10, PARED + 2, Vector(180//2 - 10, -1, 20), Vector(0, 1, 0))
ag_led_c = Part.makeCylinder(2.5, PARED + 2, Vector(180//2 + 10, -1, 20), Vector(0, 1, 0))
caja_c = caja_c.cut(ag_on_c).cut(ag_led_c)

obj_cajac = doc.addObject("Part::Feature", "Cerebro_Caja_Madera")
obj_cajac.Shape = caja_c
obj_cajac.Placement.Base = Vector(OX, OY, OZ)
obj_cajac.ViewObject.ShapeColor = color_madera
obj_cajac.ViewObject.Transparency = 30

# Fuente ATX Reciclada
atx_c = Part.makeBox(125, 63, 100)
ag_fan_c = Part.makeCylinder(40, 8, Vector(62, 31, 92), Vector(0, 0, 1))
atx_c = atx_c.cut(ag_fan_c)
obj_atxc = doc.addObject("Part::Feature", "Cerebro_Fuente_ATX")
obj_atxc.Shape = atx_c
obj_atxc.Placement.Base = Vector(OX + PARED + 2, OY + PARED + 2, OZ + PARED)
obj_atxc.ViewObject.ShapeColor = color_motor

# Arduino Mega 2560 PCB
arduino = Part.makeBox(101.6, 53.3, 1.6)
obj_ardc = doc.addObject("Part::Feature", "Cerebro_Arduino_Mega")
obj_ardc.Shape = arduino
obj_ardc.Placement.Base = Vector(OX + PARED + 12, OY + PARED + 100, OZ + PARED + 16)
obj_ardc.ViewObject.ShapeColor = color_pcb_verde

# RAMPS 1.4 PCB (Montada sobre Arduino)
ramps = Part.makeBox(101.6, 53.3, 1.6)
obj_rampsc = doc.addObject("Part::Feature", "Cerebro_RAMPS_1_4")
obj_rampsc.Shape = ramps
obj_rampsc.Placement.Base = Vector(OX + PARED + 12, OY + PARED + 100, OZ + PARED + 16 + 1.6 + 8)
obj_rampsc.ViewObject.ShapeColor = color_pcb_rojo

# LCD Smart Controller 20x4
lcd = Part.makeBox(128, 75, 2)
obj_lcdc = doc.addObject("Part::Feature", "Cerebro_LCD_PCB")
obj_lcdc.Shape = lcd
obj_lcdc.Placement.Base = Vector(OX + PARED + 2, OY + PARED + 150, OZ + PARED)
obj_lcdc.ViewObject.ShapeColor = (0.15, 0.35, 0.15)

pantalla_c = Part.makeBox(99, 24, 5)
obj_pantc = doc.addObject("Part::Feature", "Cerebro_LCD_Pantalla")
obj_pantc.Shape = pantalla_c
obj_pantc.Placement.Base = Vector(OX + PARED + 12, OY + PARED + 160, OZ + PARED + 2)
obj_pantc.ViewObject.ShapeColor = color_pantalla

# ==============================================================================
# ─── RECALCULAR Y RENDERIZAR VISTAS ───────────────────────────────────────────
# ==============================================================================
doc.recompute()
import FreeCADGui as Gui
Gui.activeDocument().activeView().viewIsometric()
Gui.SendMsgToActiveView("ViewFit")

print("=" * 60)
print("¡IMPRESORA 3D MODULAR COMPLETA GENERADA CON ÉXITO!")
print("Estructura de madera (Pino 18mm), componentes e-waste y cerebro.")
print("=" * 60)
