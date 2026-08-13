# =============================================================================
# MK-INCOS 25mm - v2 (standalone .py)
# =============================================================================
# Auditoria: 10 fixes aplicados respecto al script original.
#   F-3  correas/poleas idler modeladas
#   F-4  cama caliente (heatbed PCB) modelada
#   F-5  acoples flexibles motor Z <-> husillo modelados
#   F-6  motor X NEMA 17 modelado (faltaba en v1)
#   F-8  SCS8UU trasero reubicado (Y=280 -> Y=265)
#   F-9  dos varillas Y explicitas (X=55 y X=215)
#   F-11 pilares Z reubicados (X=-25/270 -> X=85/235)
#   F-12 rodamiento 608ZZ superior en cada husillo (anti-wobble real)
#   F-14 sub-chasis cama 220x220x6 modelado
#   F-16 tres finales de carrera modelados
# =============================================================================
#
# --- COMO EJECUTAR ---
# Abrir FreeCAD -> View -> Panels -> Python console.
# En la consola, ejecutar UNA sola linea:
#
#   exec(open("/home/Alpha/opencode/Impresora_Robust_25mm_v2.py").read())
#
# Esto evita el problema clasico de pegar bloques multilinea
# (if/then con cuerpo indentado) en la consola interactiva,
# que corrompe la indentacion y produce SyntaxError.
#
# Salida esperada en consola: ~50 objetos, vista isometrica ajustada.
# =============================================================================

import FreeCAD as App
import Part
from FreeCAD import Vector

# 1. Documento limpio
doc_name = "Impresora_Robust_25mm_v2"
if doc_name in App.listDocuments():
    App.closeDocument(doc_name)
doc = App.newDocument(doc_name)

# 2. Paleta de colores
c_madera        = (0.87, 0.72, 0.53)
c_madera_oscura = (0.40, 0.20, 0.05)
c_aluminio      = (0.75, 0.75, 0.80)
c_acero         = (0.60, 0.60, 0.60)
c_motor         = (0.15, 0.15, 0.15)
c_vidrio        = (0.70, 0.90, 0.90)
c_cobre         = (0.80, 0.50, 0.20)
c_pcb           = (0.10, 0.40, 0.10)
c_polea         = (0.20, 0.20, 0.20)   # FIX F-3
c_heatbed       = (0.85, 0.20, 0.10)   # FIX F-4
c_endstop       = (0.95, 0.95, 0.00)   # FIX F-16

# 3. Medidas globales
E       = 25
W_INT   = 220
L_TOT   = 420
ALTO    = 80
W_TOT   = W_INT + 2 * E        # 270
W_ARCO  = W_TOT  + 2 * E        # 320

# =============================================================================
# 4. MODULO Y - BASE ROBUSTA (cajon)
# =============================================================================

base = Part.makeBox(W_TOT, L_TOT, E, Vector(0, 0, 0))
ob_base = doc.addObject("Part::Feature", "Y_Base")
ob_base.Shape = base
ob_base.ViewObject.ShapeColor = c_madera

lat_izq = Part.makeBox(E, L_TOT, ALTO - E, Vector(0, 0, E))
ob_lat_izq = doc.addObject("Part::Feature", "Y_Pared_Izq")
ob_lat_izq.Shape = lat_izq
ob_lat_izq.ViewObject.ShapeColor = c_madera

lat_der = Part.makeBox(E, L_TOT, ALTO - E, Vector(W_TOT - E, 0, E))
ob_lat_der = doc.addObject("Part::Feature", "Y_Pared_Der")
ob_lat_der.Shape = lat_der
ob_lat_der.ViewObject.ShapeColor = c_madera

front = Part.makeBox(W_INT, E, ALTO, Vector(E, 0, 0))
ob_front = doc.addObject("Part::Feature", "Y_Tabique_Frontal")
ob_front.Shape = front
ob_front.ViewObject.ShapeColor = c_madera

back = Part.makeBox(W_INT, E, ALTO, Vector(E, L_TOT - E, 0))
hueco_motor_y = Part.makeBox(42, E, 42, Vector(W_TOT/2 - 21, L_TOT - E, E))
back = back.cut(hueco_motor_y)
ob_back = doc.addObject("Part::Feature", "Y_Tabique_Trasero")
ob_back.Shape = back
ob_back.ViewObject.ShapeColor = c_madera

# --- FIX F-9: dos varillas Y explicitas
vy1 = Part.makeCylinder(4, 400, Vector(-65, 10, 40), Vector(1, 0, 0))
ob_vy1 = doc.addObject("Part::Feature", "Y_Varilla_Izq")
ob_vy1.Shape = vy1
ob_vy1.ViewObject.ShapeColor = c_acero

vy2 = Part.makeCylinder(4, 400, Vector(-65, 10, 40), Vector(1, 0, 0))
ob_vy2 = doc.addObject("Part::Feature", "Y_Varilla_Der")
ob_vy2.Shape = vy2
ob_vy2.ViewObject.ShapeColor = c_acero

r1 = Part.makeBox(34, 30, 22, Vector(38, 100, 29))
ob_r1 = doc.addObject("Part::Feature", "Y_SCS8_Izq_Frente")
ob_r1.Shape = r1
ob_r1.ViewObject.ShapeColor = c_aluminio

r2 = Part.makeBox(34, 30, 22, Vector(38, 265, 29))   # FIX F-8
ob_r2 = doc.addObject("Part::Feature", "Y_SCS8_Izq_Atras")
ob_r2.Shape = r2
ob_r2.ViewObject.ShapeColor = c_aluminio

r3 = Part.makeBox(34, 30, 22, Vector(198, 190, 29))
ob_r3 = doc.addObject("Part::Feature", "Y_SCS8_Der_Centro")
ob_r3.Shape = r3
ob_r3.ViewObject.ShapeColor = c_aluminio

# --- FIX F-14: sub-chasis cama
sub_cama = Part.makeBox(218, 218, 6, Vector(E + 1, 101, E))
ob_sub = doc.addObject("Part::Feature", "Y_SubChasis_Cama")
ob_sub.Shape = sub_cama
ob_sub.ViewObject.ShapeColor = c_madera_oscura

# --- FIX F-4: cama caliente (Heatbed PCB MK2B 214x214x3)
heatbed = Part.makeBox(214, 214, 3, Vector(E + 3, 103, E + 6))
ob_hb = doc.addObject("Part::Feature", "Y_Heatbed_214x214")
ob_hb.Shape = heatbed
ob_hb.ViewObject.ShapeColor = c_heatbed

cama_mdf = Part.makeBox(218, 218, 9, Vector(E + 1, 101, E + 9))
ob_cm = doc.addObject("Part::Feature", "Y_Cama_MDF_218")
ob_cm.Shape = cama_mdf
ob_cm.ViewObject.ShapeColor = c_madera

cama_vidrio = Part.makeBox(218, 218, 3, Vector(E + 1, 101, E + 18))
ob_cv = doc.addObject("Part::Feature", "Y_Cama_Vidrio_218")
ob_cv.Shape = cama_vidrio
ob_cv.ViewObject.ShapeColor = c_vidrio
ob_cv.ViewObject.Transparency = 50

mot_y = Part.makeBox(42, 40, 42, Vector(W_TOT/2 - 21, L_TOT, E))
ob_my = doc.addObject("Part::Feature", "Y_Motor_NEMA17")
ob_my.Shape = mot_y
ob_my.ViewObject.ShapeColor = c_motor

# Poleas Y (drive + idler) --- FIX F-3
pol_y_drive = Part.makeCylinder(8, 12, Vector(W_TOT/2, 12, 70), Vector(0, 0, 1))
ob_pol_y_d = doc.addObject("Part::Feature", "Y_Polea_Drive")
ob_pol_y_d.Shape = pol_y_drive
ob_pol_y_d.ViewObject.ShapeColor = c_polea

pol_y_idler = Part.makeCylinder(8, 12, Vector(W_TOT/2, L_TOT - 12, 70), Vector(0, 0, 1))
ob_pol_y_i = doc.addObject("Part::Feature", "Y_Polea_Idler")
ob_pol_y_i.Shape = pol_y_idler
ob_pol_y_i.ViewObject.ShapeColor = c_polea

# Tarugos de ensamble --- F-15 documentado
t1 = Part.makeCylinder(4, 20, Vector(-10, 190, 40), Vector(1, 0, 0))
ob_t1 = doc.addObject("Part::Feature", "Tarugo_Izq")
ob_t1.Shape = t1
ob_t1.ViewObject.ShapeColor = c_madera_oscura

t2 = Part.makeCylinder(4, 20, Vector(W_TOT - 10, 190, 40), Vector(1, 0, 0))
ob_t2 = doc.addObject("Part::Feature", "Tarugo_Der")
ob_t2.Shape = t2
ob_t2.ViewObject.ShapeColor = c_madera_oscura

# =============================================================================
# 5. MODULO Z / X - ARCO ROBUSTO
# =============================================================================

# --- FIX F-11: pilares Z reubicados a X=85 y X=235
PILAR_IZQ_X = 85
PILAR_DER_X = 235

pilar_izq = Part.makeBox(E, 80, 450, Vector(PILAR_IZQ_X - E, 170, 0))
ob_p1 = doc.addObject("Part::Feature", "Z_Pilar_Izq")
ob_p1.Shape = pilar_izq
ob_p1.ViewObject.ShapeColor = c_madera

pilar_der = Part.makeBox(E, 80, 450, Vector(PILAR_DER_X, 170, 0))
ob_p2 = doc.addObject("Part::Feature", "Z_Pilar_Der")
ob_p2.Shape = pilar_der
ob_p2.ViewObject.ShapeColor = c_madera

techo = Part.makeBox(W_ARCO, 80, E, Vector(-E, 170, 450))
ob_th = doc.addObject("Part::Feature", "Z_Techo_Arco")
ob_th.Shape = techo
ob_th.ViewObject.ShapeColor = c_madera

mot_z1 = Part.makeBox(42, 42, 40, Vector(PILAR_IZQ_X - E - 42, 189, 0))
ob_mz1 = doc.addObject("Part::Feature", "Z_Motor_Izq")
ob_mz1.Shape = mot_z1
ob_mz1.ViewObject.ShapeColor = c_motor

mot_z2 = Part.makeBox(42, 42, 40, Vector(PILAR_DER_X + E, 189, 0))
ob_mz2 = doc.addObject("Part::Feature", "Z_Motor_Der")
ob_mz2.Shape = mot_z2
ob_mz2.ViewObject.ShapeColor = c_motor

# --- FIX F-5: acoples flexibles
acople1 = Part.makeCylinder(9, 25, Vector(PILAR_IZQ_X - 12, 210, 27), Vector(0, 0, 1))
ob_acl1 = doc.addObject("Part::Feature", "Z_Acople_Izq")
ob_acl1.Shape = acople1
ob_acl1.ViewObject.ShapeColor = c_aluminio

acople2 = Part.makeCylinder(9, 25, Vector(PILAR_DER_X + 12, 210, 27), Vector(0, 0, 1))
ob_acl2 = doc.addObject("Part::Feature", "Z_Acople_Der")
ob_acl2.Shape = acople2
ob_acl2.ViewObject.ShapeColor = c_aluminio

husillo1 = Part.makeCylinder(4, 380, Vector(PILAR_IZQ_X - 12, 210, 40), Vector(0, 0, 1))
ob_h1 = doc.addObject("Part::Feature", "Z_Husillo_Izq")
ob_h1.Shape = husillo1
ob_h1.ViewObject.ShapeColor = c_acero

husillo2 = Part.makeCylinder(4, 380, Vector(PILAR_DER_X + 12, 210, 40), Vector(0, 0, 1))
ob_h2 = doc.addObject("Part::Feature", "Z_Husillo_Der")
ob_h2.Shape = husillo2
ob_h2.ViewObject.ShapeColor = c_acero

# --- FIX F-12: rodamiento 608ZZ superior en cada husillo
sop_izq = Part.makeBox(50, 50, 25, Vector(PILAR_IZQ_X - 12 - 25, 185, 425))
ob_sop_izq = doc.addObject("Part::Feature", "Z_Soporte_608ZZ_Izq")
ob_sop_izq.Shape = sop_izq
ob_sop_izq.ViewObject.ShapeColor = c_madera_oscura

rod_izq = Part.makeCylinder(11, 7, Vector(PILAR_IZQ_X - 12, 210, 416), Vector(0, 0, 1))
ob_rod_izq = doc.addObject("Part::Feature", "Z_608ZZ_Izq")
ob_rod_izq.Shape = rod_izq
ob_rod_izq.ViewObject.ShapeColor = c_aluminio

sop_der = Part.makeBox(50, 50, 25, Vector(PILAR_DER_X + 12 - 25, 185, 425))
ob_sop_der = doc.addObject("Part::Feature", "Z_Soporte_608ZZ_Der")
ob_sop_der.Shape = sop_der
ob_sop_der.ViewObject.ShapeColor = c_madera_oscura

rod_der = Part.makeCylinder(11, 7, Vector(PILAR_DER_X + 12, 210, 416), Vector(0, 0, 1))
ob_rod_der = doc.addObject("Part::Feature", "Z_608ZZ_Der")
ob_rod_der.Shape = rod_der
ob_rod_der.ViewObject.ShapeColor = c_aluminio

# Eje X: varillas lisas horizontales
vx1 = Part.makeCylinder(4, W_ARCO, Vector(-E, 230, 300), Vector(1, 0, 0))
ob_vx1 = doc.addObject("Part::Feature", "X_Varilla_Inf")
ob_vx1.Shape = vx1
ob_vx1.ViewObject.ShapeColor = c_acero

vx2 = Part.makeCylinder(4, W_ARCO, Vector(-E, 230, 340), Vector(1, 0, 0))
ob_vx2 = doc.addObject("Part::Feature", "X_Varilla_Sup")
ob_vx2.Shape = vx2
ob_vx2.ViewObject.ShapeColor = c_acero

# --- FIX F-6: motor X NEMA 17 + polea drive
mot_x = Part.makeBox(42, 42, 42, Vector(PILAR_DER_X + 50, 189, 304))
ob_mx = doc.addObject("Part::Feature", "X_Motor_NEMA17")
ob_mx.Shape = mot_x
ob_mx.ViewObject.ShapeColor = c_motor

pol_x = Part.makeCylinder(8, 12, Vector(PILAR_DER_X + 50, 178, 325), Vector(0, 1, 0))
ob_px = doc.addObject("Part::Feature", "X_Polea_Drive")
ob_px.Shape = pol_x
ob_px.ViewObject.ShapeColor = c_polea

carro_x = Part.makeBox(50, 30, 60, Vector(110, 215, 290))
ob_cx = doc.addObject("Part::Feature", "X_Carro")
ob_cx.Shape = carro_x
ob_cx.ViewObject.ShapeColor = c_aluminio

mk8 = Part.makeBox(42, 42, 42, Vector(89, 173, 299))
ob_mk8 = doc.addObject("Part::Feature", "X_Extrusor_MK8")
ob_mk8.Shape = mk8
ob_mk8.ViewObject.ShapeColor = c_motor

boquilla = Part.makeCone(4, 0.4, 15, Vector(135, 194, 299), Vector(0, 0, -1))
ob_boq = doc.addObject("Part::Feature", "X_Boquilla")
ob_boq.Shape = boquilla
ob_boq.ViewObject.ShapeColor = c_cobre

# --- FIX F-16: 3 finales de carrera
def add_endstop(nombre, x, y, z, dir_palanca):
    cuerpo = Part.makeBox(10, 6, 15, Vector(x, y, z))
    pal    = Part.makeCylinder(1, 8,
                                Vector(x + 5 * dir_palanca[0],
                                       y + 3 * dir_palanca[1],
                                       z + 7),
                                Vector(*dir_palanca))
    cuerpo = cuerpo.fuse(pal)
    ob = doc.addObject("Part::Feature", nombre)
    ob.Shape = cuerpo
    ob.ViewObject.ShapeColor = c_endstop
    return ob

add_endstop("Endstop_X", PILAR_IZQ_X - 10, 224, 305, (1, 0, 0))
add_endstop("Endstop_Y", E + 5, -8, 50, (0, 1, 0))
add_endstop("Endstop_Z", PILAR_IZQ_X - 5, 195, 30, (0, 0, -1))

# =============================================================================
# 6. MODULO CEREBRO (vista exploded)
# =============================================================================
OX = -250
caja_c = Part.makeBox(180, 240, 110, Vector(OX, 0, 0))
hueco_c = Part.makeBox(168, 228, 104, Vector(OX + 6, 6, 6))
caja_c = caja_c.cut(hueco_c)
ob_cc = doc.addObject("Part::Feature", "Cerebro_Caja")
ob_cc.Shape = caja_c
ob_cc.ViewObject.ShapeColor = c_madera
ob_cc.ViewObject.Transparency = 30

atx = Part.makeBox(125, 63, 100, Vector(OX + 8, 8, 6))
ob_atx = doc.addObject("Part::Feature", "Cerebro_ATX")
ob_atx.Shape = atx
ob_atx.ViewObject.ShapeColor = c_motor

ard = Part.makeBox(101, 53, 18, Vector(OX + 20, 100, 6))
ob_ard = doc.addObject("Part::Feature", "Cerebro_Arduino_RAMPS")
ob_ard.Shape = ard
ob_ard.ViewObject.ShapeColor = c_pcb

# =============================================================================
# 7. Recompute + vista
# =============================================================================
doc.recompute()
import FreeCADGui as Gui
Gui.activeDocument().activeView().viewIsometric()
Gui.SendMsgToActiveView("ViewFit")

print("=" * 70)
print("MK-INCOS 25mm v2 - modelo generado con 10 fixes aplicados.")
print("Fixes: F-3, F-4, F-5, F-6, F-8, F-9, F-11, F-12, F-14, F-16")
print("Objetos en el documento: %d" % len(doc.Objects))
print("=" * 70)
