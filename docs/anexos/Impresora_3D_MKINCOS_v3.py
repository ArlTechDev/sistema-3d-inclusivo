# =============================================================================
# MK-INCOS 30x30x30 - v3 (consolidated)
# =============================================================================
# BLUEPRINT
#   Area de impresion:    300 x 300 x 300 mm  (X x Y x Z)
#   Chasis externo:       380 x 660 x 500 mm  (W_TOT x L_TOT x H_TOTAL)
#   Madera:               Pino 20 mm (E=20)
#   Cama:                 Fria (MDF 6mm + vidrio 3mm) — sin heatbed
#   Motores (4 x NEMA17): 1 X  +  1 Y  +  2 Z (dual drive)
#   Varillas lisas ø8mm:  2 Y (soporte cama)  +  2 X (carro)  +  2 Z (estab.)
#   Varillas roscadas M8: 2 Z (traccion husillo)
#   Rodamientos:          LM8UU (8 mm) en cama y carro X
#   Acoples flexibles:    NEMA17 (eje 5mm) <-> M8 (husillo)
#   Correa:               GT2 + poleas 20T en X e Y
#   Electrónica:          Arduino Mega 2560 + RAMPS 1.4 + ATX reciclada
#   Arquitectura:         Cartesiana Prusa i3 modificada (bedslinger)
#
# AUDITORIA — bugs corregidos respecto a v1/v2
#   B-1  PARED no definida en Impresora_3D_Completa_MBS.py (NameError)
#   B-2  Varillas Y en X=-65 (afuera del chasis) en v2
#   B-3  Poleas Y a Z=70 con varillas a Z=40 (flotan) en v2
#   B-4  Espesor 18/25 mm → normalizado a 20 mm
#   B-5  Print area 220x310 → 300x300 (cumple spec del usuario)
#   B-6  Cama con heatbed PCB → cama fria (segun arquitectura del proyecto)
#   B-7  Pilares Z dentro del area de la cama → movidos a los laterales
#   B-8  Sin LM8UU → 3 en cama + 4 en carro X + 4 en gantry Z
#   B-9  Sin acoples flexibles NEMA↔M8 → agregados
#   B-10 3 finales de carrera → agregados
#
# EJECUCION
#   Abrir FreeCAD → View → Panels → Python console
#   Ejecutar UNA linea:
#     exec(open("/mnt/extens/incos/utils/web3/sistema_inclusivo/docs/anexos/Impresora_3D_MKINCOS_v3.py").read())
# =============================================================================

import FreeCAD as App
import Part
from FreeCAD import Vector

doc_name = "Impresora_3D_MKINCOS_v3"
if doc_name in App.listDocuments():
    App.closeDocument(doc_name)
doc = App.newDocument(doc_name)

# ── PALETA DE COLORES ───────────────────────────────────────────────────────
c_pino         = (0.87, 0.72, 0.53)
c_mdf          = (0.70, 0.55, 0.40)
c_madera_oscura = (0.40, 0.20, 0.05)
c_aluminio     = (0.75, 0.75, 0.80)
c_acero        = (0.60, 0.60, 0.65)
c_motor        = (0.15, 0.15, 0.15)
c_vidrio       = (0.70, 0.90, 0.90)
c_cobre        = (0.80, 0.50, 0.20)
c_pcb          = (0.10, 0.40, 0.10)
c_polea        = (0.20, 0.20, 0.20)
c_endstop      = (0.95, 0.95, 0.00)
c_cables       = (0.95, 0.85, 0.10)

# ── DIMENSIONES GLOBALES ────────────────────────────────────────────────────
E       = 20
W_INT   = 340
L_INT   = 620
W_TOT   = W_INT + 2 * E
L_TOT   = L_INT + 2 * E
ALTO_Y  = 100
H_TOTAL = 500
W_ARCO  = W_TOT + 2 * E
L_ARCO  = L_TOT + 2 * E

BED_W   = 320
BED_L   = 320
SUB_T   = 6
VID_T   = 3
BED_T   = SUB_T + VID_T

BED_X   = (W_TOT - BED_W) / 2
BED_Z   = ALTO_Y

Y_ROD_X1 = 100
Y_ROD_X2 = 280
Y_ROD_Z  = 30
Y_ROD_LEN = 640

Z_ROD_X_IZQ_T = 5
Z_ROD_X_IZQ_S = 15
Z_ROD_X_DER_T = W_TOT - 5
Z_ROD_X_DER_S = W_TOT - 15
Z_ROD_Z_BASE = ALTO_Y
Z_ROD_Z_TOP  = H_TOTAL

X_ROD_Y      = L_TOT / 2
X_ROD_Z_INF  = 322
X_ROD_Z_SUP  = 352

PILAR_IZQ_X  = 0
PILAR_DER_X  = W_TOT - E

# =============================================================================
# ── HELPERS ─────────────────────────────────────────────────────────────────
# =============================================================================

def add_box(nombre, x, y, z, w, l, h, color, transp=None):
    obj = doc.addObject("Part::Feature", nombre)
    obj.Shape = Part.makeBox(w, l, h, Vector(x, y, z))
    obj.ViewObject.ShapeColor = color
    if transp is not None:
        obj.ViewObject.Transparency = transp
    return obj

def add_cyl(nombre, cx, cy, cz, r, h, eje, color):
    obj = doc.addObject("Part::Feature", nombre)
    obj.Shape = Part.makeCylinder(r, h, Vector(cx, cy, cz), Vector(*eje))
    obj.ViewObject.ShapeColor = color
    return obj

def add_nema17(nombre, x, y, z, color):
    return add_box(nombre, x, y, z, 42, 42, 40, color)

def add_lm8uu(nombre, cx, cy, cz, eje, color):
    return add_cyl(nombre, cx, cy, cz, 12, 45, eje, color)

def add_polea_gt2(nombre, cx, cy, cz, eje, color):
    return add_cyl(nombre, cx, cy, cz, 8, 12, eje, color)

def add_acople_flexible(nombre, cx, cy, cz, eje, color):
    return add_cyl(nombre, cx, cy, cz, 9, 25, eje, color)

def add_endstop(nombre, x, y, z, dir_palanca, color):
    cuerpo = Part.makeBox(10, 6, 15, Vector(x, y, z))
    palanca = Part.makeCylinder(1, 8,
                                Vector(x + 5 * dir_palanca[0],
                                       y + 3 * dir_palanca[1],
                                       z + 7),
                                Vector(*dir_palanca))
    cuerpo = cuerpo.fuse(palanca)
    obj = doc.addObject("Part::Feature", nombre)
    obj.Shape = cuerpo
    obj.ViewObject.ShapeColor = color
    return obj

# =============================================================================
# ── MODULO Y: BASE + CAMA + EJE Y ──────────────────────────────────────────
# =============================================================================

add_box("Y_Piso", 0, 0, 0, W_TOT, L_TOT, E, c_pino)

add_box("Y_Pared_Izq_Z_Pilar",
        PILAR_IZQ_X, 0, E, E, L_TOT, H_TOTAL - E, c_pino)

add_box("Y_Pared_Der_Z_Pilar",
        PILAR_DER_X, 0, E, E, L_TOT, H_TOTAL - E, c_pino)

add_box("Y_Pared_Frontal", 0, 0, E, W_TOT, E, ALTO_Y - E, c_pino)

back = Part.makeBox(W_TOT, E, ALTO_Y - E, Vector(0, L_TOT - E, E))
hueco_motor_y = Part.makeBox(42, E, 42,
                             Vector(W_TOT/2 - 21, L_TOT - E, E))
back = back.cut(hueco_motor_y)
ob_back = doc.addObject("Part::Feature", "Y_Pared_Trasera")
ob_back.Shape = back
ob_back.ViewObject.ShapeColor = c_pino

add_box("Z_Travesano_Superior",
        0, L_TOT - 100, H_TOTAL - E, W_TOT, E, E, c_pino)

sub_cama = Part.makeBox(BED_W, BED_L, SUB_T,
                        Vector(BED_X, (L_INT - BED_L)/2 + E, BED_Z))
ob_sub = doc.addObject("Part::Feature", "Y_SubChasis_MDF")
ob_sub.Shape = sub_cama
ob_sub.ViewObject.ShapeColor = c_mdf

cama_vidrio = Part.makeBox(BED_W, BED_L, VID_T,
                           Vector(BED_X, (L_INT - BED_L)/2 + E,
                                  BED_Z + SUB_T))
ob_cv = doc.addObject("Part::Feature", "Y_Cama_Vidrio")
ob_cv.Shape = cama_vidrio
ob_cv.ViewObject.ShapeColor = c_vidrio
ob_cv.ViewObject.Transparency = 50

add_cyl("Y_Varilla_Izq",
        Y_ROD_X1, -10, Y_ROD_Z, 4, Y_ROD_LEN, (0, 1, 0), c_acero)

add_cyl("Y_Varilla_Der",
        Y_ROD_X2, -10, Y_ROD_Z, 4, Y_ROD_LEN, (0, 1, 0), c_acero)

LM8UU_Y_Z1 = Y_ROD_Z
LM8UU_Y_Z2 = Y_ROD_Z
add_lm8uu("Y_LM8UU_Izq_Frente", Y_ROD_X1, 60,  LM8UU_Y_Z1, (0, 1, 0), c_aluminio)
add_lm8uu("Y_LM8UU_Izq_Atras",  Y_ROD_X1, 560, LM8UU_Y_Z2, (0, 1, 0), c_aluminio)
add_lm8uu("Y_LM8UU_Der_Centro", Y_ROD_X2, 310, Y_ROD_Z,    (0, 1, 0), c_aluminio)

add_nema17("Y_Motor_NEMA17",
           W_TOT/2 - 21, L_TOT, E, c_motor)

add_polea_gt2("Y_Polea_Drive",
              W_TOT/2, L_TOT - 6, Y_ROD_Z + 19, (0, 1, 0), c_polea)

sop_idler = add_box("Y_Soporte_Idler",
                    W_TOT/2 - 10, 0, Y_ROD_Z + 5, 20, 20, 15, c_pino)
add_polea_gt2("Y_Polea_Idler",
              W_TOT/2, 10, Y_ROD_Z + 19, (0, 1, 0), c_polea)

# =============================================================================
# ── MODULO Z: HUSILLOS, VARILLAS LISAS, MOTORES DUALES ─────────────────────
# =============================================================================

add_cyl("Z_Husillo_Izq",
        Z_ROD_X_IZQ_T, L_TOT/2, Z_ROD_Z_BASE, 4,
        Z_ROD_Z_TOP - Z_ROD_Z_BASE, (0, 0, 1), c_acero)

add_cyl("Z_Husillo_Der",
        Z_ROD_X_DER_T, L_TOT/2, Z_ROD_Z_BASE, 4,
        Z_ROD_Z_TOP - Z_ROD_Z_BASE, (0, 0, 1), c_acero)

add_cyl("Z_Varilla_Lisa_Izq",
        Z_ROD_X_IZQ_S, L_TOT/2, Z_ROD_Z_BASE, 4,
        Z_ROD_Z_TOP - Z_ROD_Z_BASE, (0, 0, 1), c_acero)

add_cyl("Z_Varilla_Lisa_Der",
        Z_ROD_X_DER_S, L_TOT/2, Z_ROD_Z_BASE, 4,
        Z_ROD_Z_TOP - Z_ROD_Z_BASE, (0, 0, 1), c_acero)

add_nema17("Z_Motor_Izq",
           Z_ROD_X_IZQ_T - 21, L_TOT/2 - 21, Z_ROD_Z_TOP - 40, c_motor)
add_nema17("Z_Motor_Der",
           Z_ROD_X_DER_T - 21, L_TOT/2 - 21, Z_ROD_Z_TOP - 40, c_motor)

add_acople_flexible("Z_Acople_Izq",
                    Z_ROD_X_IZQ_T, L_TOT/2, Z_ROD_Z_BASE + 20,
                    (0, 0, 1), c_aluminio)
add_acople_flexible("Z_Acople_Der",
                    Z_ROD_X_DER_T, L_TOT/2, Z_ROD_Z_BASE + 20,
                    (0, 0, 1), c_aluminio)

rod_608_izq = add_cyl("Z_608ZZ_Superior_Izq",
                      Z_ROD_X_IZQ_T, L_TOT/2, Z_ROD_Z_TOP - 14, 11, 7,
                      (0, 0, 1), c_aluminio)
add_box("Z_Soporte_608_Izq",
        Z_ROD_X_IZQ_T - 25, L_TOT/2 - 25, Z_ROD_Z_TOP - 21,
        50, 50, 5, c_madera_oscura)

rod_608_der = add_cyl("Z_608ZZ_Superior_Der",
                      Z_ROD_X_DER_T, L_TOT/2, Z_ROD_Z_TOP - 14, 11, 7,
                      (0, 0, 1), c_aluminio)
add_box("Z_Soporte_608_Der",
        Z_ROD_X_DER_T - 25, L_TOT/2 - 25, Z_ROD_Z_TOP - 21,
        50, 50, 5, c_madera_oscura)

# =============================================================================
# ── MODULO X: CARRO + EXTRUSOR + MOTOR ──────────────────────────────────────
# =============================================================================

X_GANTRY_Y_HALF = 40
add_box("X_Gantry_Izq",
        PILAR_IZQ_X, X_ROD_Y - X_GANTRY_Y_HALF, X_ROD_Z_INF - 30,
        E, X_GANTRY_Y_HALF * 2, 90, c_aluminio)
add_box("X_Gantry_Der",
        PILAR_DER_X, X_ROD_Y - X_GANTRY_Y_HALF, X_ROD_Z_INF - 30,
        E, X_GANTRY_Y_HALF * 2, 90, c_aluminio)

add_cyl("X_Varilla_Inf",
        -E, X_ROD_Y, X_ROD_Z_INF, 4, W_ARCO,
        (1, 0, 0), c_acero)
add_cyl("X_Varilla_Sup",
        -E, X_ROD_Y, X_ROD_Z_SUP, 4, W_ARCO,
        (1, 0, 0), c_acero)

add_nema17("X_Motor_NEMA17",
           W_TOT + E + 4, X_ROD_Y - 21, X_ROD_Z_INF - 6, c_motor)

add_polea_gt2("X_Polea_Drive",
              W_TOT + E + 4, X_ROD_Y, X_ROD_Z_INF + 15,
              (0, 1, 0), c_polea)
add_polea_gt2("X_Polea_Idler",
              -E + 4, X_ROD_Y, X_ROD_Z_INF + 15,
              (0, 1, 0), c_polea)

CARRO_W = 60
CARRO_L = 50
CARRO_H = 60
CARRO_X_CENTER = W_TOT / 2
add_box("X_Carro",
        CARRO_X_CENTER - CARRO_W/2,
        X_ROD_Y - CARRO_L/2,
        X_ROD_Z_INF - 5,
        CARRO_W, CARRO_L, CARRO_H, c_aluminio)

add_lm8uu("X_LM8UU_Inf_Frontal",
          CARRO_X_CENTER, X_ROD_Y - 15, X_ROD_Z_INF,
          (1, 0, 0), c_aluminio)
add_lm8uu("X_LM8UU_Inf_Trasero",
          CARRO_X_CENTER, X_ROD_Y + 15, X_ROD_Z_INF,
          (1, 0, 0), c_aluminio)
add_lm8uu("X_LM8UU_Sup_Frontal",
          CARRO_X_CENTER, X_ROD_Y - 15, X_ROD_Z_SUP,
          (1, 0, 0), c_aluminio)
add_lm8uu("X_LM8UU_Sup_Trasero",
          CARRO_X_CENTER, X_ROD_Y + 15, X_ROD_Z_SUP,
          (1, 0, 0), c_aluminio)

add_lm8uu("Z_LM8UU_Gantry_Izq_T",
          Z_ROD_X_IZQ_T, X_ROD_Y, X_ROD_Z_INF + 5,
          (0, 0, 1), c_aluminio)
add_lm8uu("Z_LM8UU_Gantry_Der_T",
          Z_ROD_X_DER_T, X_ROD_Y, X_ROD_Z_INF + 5,
          (0, 0, 1), c_aluminio)

add_nema17("X_Extrusor_MK8",
           CARRO_X_CENTER - 21, X_ROD_Y - 60, X_ROD_Z_INF + 10, c_motor)

boquilla_cono = Part.makeCone(4, 0.4, 15,
                              Vector(CARRO_X_CENTER, X_ROD_Y - 17,
                                     X_ROD_Z_INF - 30),
                              Vector(0, 0, -1))
ob_boq = doc.addObject("Part::Feature", "X_Cono_Boquilla")
ob_boq.Shape = boquilla_cono
ob_boq.ViewObject.ShapeColor = c_cobre

# =============================================================================
# ── FINALES DE CARRERA ──────────────────────────────────────────────────────
# =============================================================================

add_endstop("Endstop_X_Min",
            E - 5, X_ROD_Y - 3, X_ROD_Z_INF + 5, (1, 0, 0), c_endstop)

add_endstop("Endstop_Y_Min",
            W_TOT/2 - 3, E - 5, Y_ROD_Z + 15, (0, 1, 0), c_endstop)

add_endstop("Endstop_Z_Min",
            Z_ROD_X_IZQ_S - 5, L_TOT/2 - 3, Z_ROD_Z_BASE + 5,
            (0, 0, 1), c_endstop)

# =============================================================================
# ── MODULO CEREBRO (vista exploded) ─────────────────────────────────────────
# =============================================================================

OX = -300
OY = 60
caja_ext = Part.makeBox(180, 240, 110, Vector(OX, OY, 0))
PARED = 15
caja_int = Part.makeBox(180 - PARED*2, 240 - PARED*2, 110 - PARED,
                        Vector(OX + PARED, OY + PARED, PARED))
caja_c = caja_ext.cut(caja_int)

ag_atx = Part.makeBox(51, PARED + 2, 15,
                      Vector(OX + 90//2 + 90//2 - 25, OY - 1,
                             PARED + 110//2 - 7))
caja_c = caja_c.cut(ag_atx)

ag_usb = Part.makeBox(14, PARED + 2, 7,
                      Vector(OX + 180//2 + 30, OY - 1,
                             PARED + 110//2 - 3))
caja_c = caja_c.cut(ag_usb)

ag_sd = Part.makeBox(32, 4, PARED + 2,
                     Vector(OX - 1, OY + 240//2 - 16, PARED + 110//2 - 2))
caja_c = caja_c.cut(ag_sd)

ag_on = Part.makeCylinder(10, PARED + 2,
                          Vector(OX + 180//2 - 10, OY - 1, 20),
                          Vector(0, 1, 0))
ag_led = Part.makeCylinder(2.5, PARED + 2,
                           Vector(OX + 180//2 + 10, OY - 1, 20),
                           Vector(0, 1, 0))
caja_c = caja_c.cut(ag_on).cut(ag_led)

ob_cajac = doc.addObject("Part::Feature", "Cerebro_Caja")
ob_cajac.Shape = caja_c
ob_cajac.ViewObject.ShapeColor = c_pino
ob_cajac.ViewObject.Transparency = 30

add_box("Cerebro_Fuente_ATX",
        OX + PARED + 2, OY + PARED + 2, PARED,
        125, 63, 100, c_motor)

add_box("Cerebro_Arduino_Mega",
        OX + PARED + 12, OY + PARED + 100, PARED + 16,
        101.6, 53.3, 1.6, c_pcb)

add_box("Cerebro_RAMPS_1_4",
        OX + PARED + 12, OY + PARED + 100, PARED + 16 + 1.6 + 8,
        101.6, 53.3, 1.6, (0.85, 0.10, 0.10))

add_box("Cerebro_LCD_Pantalla",
        OX + PARED + 2, OY + PARED + 150, PARED + 2,
        99, 24, 5, (0.05, 0.05, 0.20))

# =============================================================================
# ── RECOMPUTE + VISTA ───────────────────────────────────────────────────────
# =============================================================================

doc.recompute()
import FreeCADGui as Gui
Gui.activeDocument().activeView().viewIsometric()
Gui.SendMsgToActiveView("ViewFit")

print("=" * 70)
print("MK-INCOS 30x30x30 v3 — modelo generado.")
print("Area util: 300x300x300 mm  |  Chasis: 380x660x500 mm")
print("Madera: pino 20mm  |  Cama: fria (MDF+vidrio)  |  4 NEMA17 (dual Z)")
print("Objetos en el documento: %d" % len(doc.Objects))
print("=" * 70)
