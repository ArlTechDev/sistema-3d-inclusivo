# =============================================================================
# MK-INCOS 22x16.5x18 - v5 (Prusa i3 bedslinger, cama optimizada)
# =============================================================================
# BLUEPRINT
#   Area de impresion:    ~190 x 165 x 180 mm  (X x Y x Z, con margen 10mm)
#   Chasis externo:       380 x 410 x 400 mm  (W_TOT x L_TOT x H_TOTAL)
#   Madera:               Pino 20 mm (E=20), base 70mm alto
#   Cama:                 220 x 185 x 12 mm  (cortada de 300x300, MDF+vidrio 4mm)
#   Cama:                 Fria (sin heatbed)
#   Motores (4 x NEMA17): 1 X (derecha)  +  1 Y (dentro pared trasera)
#                       +  2 Z (abajo, dentro del marco)
#   Varillas lisas ø8mm:  2 Y (400mm)  +  2 Z (400mm)  +  2 X (360mm)
#   Varillas roscadas M8: 2 Z (500mm)
#   Empotramiento:        15mm en madera (todas las varillas)
#   Rodamientos:          SC8UU en cama (3, isostatico) + LM8UU en carro X (4)
#   Acoples flexibles:    NEMA17 (eje 5mm) <-> M8 (husillo), 2 unidades
#   Correa:               GT2 + poleas 20T en X e Y
#   608ZZ:                2 arriba de los husillos
#   Cerebro:              Caja separada (vista exploded) con ATX+Arduino+RAMPS+LCD
#   Aplicacion:           Impresion de recursos educativos 3D para personas
#                         con discapacidad visual (mapas tactiles, modelos
#                         anatomicos, figuras geometricas en relieve)
#
# AUDITORIA - v4 -> v5
#   F-1  Marco Y ahora SIN PISO (4 paredes, holes 8mm directos)
#   F-2  Paredes laterales full-height -> bajadas a 70mm (marco bajo)
#   F-3  Cama 300x300 -> cortada a 220x185 (regla L_int/2 = 185mm)
#   F-4  Rodamientos cama: LM8UU -> SC8UU isostatico (3 unidades)
#   F-5  Sin LM8UU en gantry Z (varillas Z pasan por holes directos)
#   F-6  Empotramiento 15mm en todas las varillas
#   F-7  Vidrio 3mm -> 4mm minimo
#   F-8  Z travel 300mm -> 200mm (limitado por varillas Z 400mm)
#   F-9  H_TOTAL 520 -> 400mm (optimizado para varillas existentes)
#   F-10 Aplicacion: Braille -> Recursos educativos 3D inclusivos
#
# REGLA DE OPTIMIZACION DE CAMA (Bedslinger)
#   Para maximizar Y travel con hotend fijo:
#     Cama_optima = L_int / 2
#     Y_travel = L_int - Cama = L_int / 2
#   Con L_int=370mm: Cama=185mm, Y_travel=185mm, Y_util=165mm (con margen)
#
# EJECUCION
#   Abrir FreeCAD -> View -> Panels -> Python console
#   Ejecutar UNA linea:
#     exec(open("/mnt/extens/incos/utils/web3/sistema_inclusivo/docs/anexos/Impresora_3D_MKINCOS_v5.py").read())
# =============================================================================

import FreeCAD as App
import Part
from FreeCAD import Vector

doc_name = "Impresora_3D_MKINCOS_v5"
if doc_name in App.listDocuments():
    App.closeDocument(doc_name)
doc = App.newDocument(doc_name)

# ── PALETA DE COLORES ───────────────────────────────────────────────────────
c_pino          = (0.87, 0.72, 0.53)
c_mdf           = (0.70, 0.55, 0.40)
c_madera_oscura = (0.40, 0.20, 0.05)
c_aluminio      = (0.75, 0.75, 0.80)
c_acero         = (0.60, 0.60, 0.65)
c_motor         = (0.15, 0.15, 0.15)
c_vidrio        = (0.70, 0.90, 0.90)
c_cobre         = (0.80, 0.50, 0.20)
c_pcb           = (0.10, 0.40, 0.10)
c_polea         = (0.20, 0.20, 0.20)
c_endstop       = (0.95, 0.95, 0.00)

# ── DIMENSIONES GLOBALES ────────────────────────────────────────────────────
E       = 20
W_INT   = 340
L_INT   = 370
W_TOT   = W_INT + 2 * E
L_TOT   = L_INT + 2 * E
ALTO_Y  = 70
H_TOTAL = 400

# Cama optimizada (regla L_int/2)
BED_W   = 220
BED_L   = 185
SUB_T   = 8
VID_T   = 4
BED_T   = SUB_T + VID_T
BED_Z   = ALTO_Y

BED_X   = (W_INT - BED_W) / 2
BED_Y0  = (L_INT - BED_L) / 2 + E

# Eje Y
Y_ROD_X1    = 100
Y_ROD_X2    = 280
Y_ROD_Z     = 30
Y_ROD_LEN   = 400
Y_MOTOR_Z   = Y_ROD_Z

# Eje Z
Z_ROD_X_IZQ_T = 21
Z_ROD_X_IZQ_S = 56
Z_ROD_X_DER_T = W_TOT - 21
Z_ROD_X_DER_S = W_TOT - 56
Z_ROD_Y       = L_TOT / 2
Z_MOT_Z_BASE  = 0
Z_MOT_Z_TOP   = 40
Z_ROD_LEN     = 400

# Gantry X (posicion ARRIBA)
PILAR_H    = H_TOTAL - ALTO_Y
G_Z        = 330
G_BAR_W    = 60
G_BAR_D    = 80
G_BAR_H    = 80
G_TOP_THK  = 20

# Varillas X (dentro del gantry, centradas en G_Z)
X_ROD_Z_INF  = G_Z - 15
X_ROD_Z_SUP  = G_Z + 15
X_ROD_Y      = Z_ROD_Y
X_ROD_LEN    = 360

# Carro X y hotend
NOZZLE_OFFSET = 50
CARRO_W = 60
CARRO_L = 50
CARRO_H = 60
CARRO_X_CENTER = W_TOT / 2
CARRO_Y_CENTER = X_ROD_Y
CARRO_Z_CENTER = G_Z

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
    if eje == (1, 0, 0):
        pos = Vector(cx - h/2, cy, cz)
    elif eje == (0, 1, 0):
        pos = Vector(cx, cy - h/2, cz)
    elif eje == (0, 0, 1):
        pos = Vector(cx, cy, cz - h/2)
    else:
        pos = Vector(cx, cy, cz)
    obj = doc.addObject("Part::Feature", nombre)
    obj.Shape = Part.makeCylinder(r, h, pos, Vector(*eje))
    obj.ViewObject.ShapeColor = color
    return obj

def add_cone(nombre, cx, cy, cz_base, r_base, r_tip, h, eje, color):
    if eje == (0, 0, -1):
        pos = Vector(cx, cy, cz_base)
    else:
        pos = Vector(cx, cy, cz_base)
    obj = doc.addObject("Part::Feature", nombre)
    obj.Shape = Part.makeCone(r_base, r_tip, h, pos, Vector(*eje))
    obj.ViewObject.ShapeColor = color
    return obj

def add_nema17(nombre, x, y, z, color):
    return add_box(nombre, x, y, z, 42, 42, 40, color)

def add_sc8uu(nombre, x, y, z, color):
    return add_box(nombre, x, y, z, 34, 30, 24, color)

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
# ── MODULO Y: MARCO SIN PISO + CAMA + EJE Y ────────────────────────────────
# =============================================================================

add_box("Y_Pared_Frontal", 0, 0, 0, W_TOT, E, ALTO_Y, c_pino)

add_box("Y_Pared_Izq",   0, 0, 0, E, L_TOT, ALTO_Y, c_pino)
add_box("Y_Pared_Der",   W_TOT - E, 0, 0, E, L_TOT, ALTO_Y, c_pino)

back = Part.makeBox(W_TOT, E, ALTO_Y, Vector(0, L_TOT - E, 0))
hueco_y_motor = Part.makeBox(42, E, 42,
                             Vector(W_TOT/2 - 21, L_TOT - E, 0))
back = back.cut(hueco_y_motor)
ob_back = doc.addObject("Part::Feature", "Y_Pared_Trasera")
ob_back.Shape = back
ob_back.ViewObject.ShapeColor = c_pino

add_box("Y_SubChasis_MDF",
        BED_X, BED_Y0, BED_Z, BED_W, BED_L, SUB_T, c_mdf)
add_box("Y_Cama_Vidrio",
        BED_X, BED_Y0, BED_Z + SUB_T, BED_W, BED_L, VID_T, c_vidrio,
        transp=50)

# Varillas Y empotradas 15mm c/extremo (span 370mm = L_INT exacto)
add_cyl("Y_Varilla_Izq",
        Y_ROD_X1, 200, Y_ROD_Z, 4, Y_ROD_LEN, (0, 1, 0), c_acero)
add_cyl("Y_Varilla_Der",
        Y_ROD_X2, 200, Y_ROD_Z, 4, Y_ROD_LEN, (0, 1, 0), c_acero)

# 3 SC8UU isostaticos bajo la cama (2 izq + 1 der)
SC8UU_Y_FRONT = BED_Y0 + 15
SC8UU_Y_BACK  = BED_Y0 + BED_L - 15
SC8UU_Y_MID   = BED_Y0 + BED_L/2
add_sc8uu("Y_SC8UU_Izq_Frente", Y_ROD_X1 - 17, SC8UU_Y_FRONT - 15,
          Y_ROD_Z - 12, c_aluminio)
add_sc8uu("Y_SC8UU_Izq_Atras",  Y_ROD_X1 - 17, SC8UU_Y_BACK - 15,
          Y_ROD_Z - 12, c_aluminio)
add_sc8uu("Y_SC8UU_Der_Centro", Y_ROD_X2 - 17, SC8UU_Y_MID - 15,
          Y_ROD_Z - 12, c_aluminio)

# Motor Y dentro de la pared trasera
add_nema17("Y_Motor_NEMA17_Dentro_Pared",
           W_TOT/2 - 21, L_TOT - 42, 0, c_motor)
add_polea_gt2("Y_Polea_Drive",
              W_TOT/2, L_TOT - 42, Y_ROD_Z,
              (0, 1, 0), c_polea)

# Idler Y al frente (soporte sobresale)
add_box("Y_Soporte_Idler",
        W_TOT/2 - 10, -20, Y_ROD_Z - 7, 20, 20, 15, c_pino)
add_polea_gt2("Y_Polea_Idler",
              W_TOT/2, -10, Y_ROD_Z, (0, 1, 0), c_polea)

# =============================================================================
# ── MODULO PILARES Z (4 esquinas) ──────────────────────────────────────────
# =============================================================================

add_box("Z_Pilar_Front_Izq",
        0, 0, ALTO_Y, E, E, PILAR_H, c_pino)
add_box("Z_Pilar_Front_Der",
        W_TOT - E, 0, ALTO_Y, E, E, PILAR_H, c_pino)
add_box("Z_Pilar_Back_Izq",
        0, L_TOT - E, ALTO_Y, E, E, PILAR_H, c_pino)
add_box("Z_Pilar_Back_Der",
        W_TOT - E, L_TOT - E, ALTO_Y, E, E, PILAR_H, c_pino)

# =============================================================================
# ── MODULO EJE Z (husillos + varillas + motores abajo) ─────────────────────
# =============================================================================

add_cyl("Z_Husillo_Izq",
        Z_ROD_X_IZQ_T, Z_ROD_Y, H_TOTAL/2, 4, 500, (0, 0, 1), c_acero)
add_cyl("Z_Husillo_Der",
        Z_ROD_X_DER_T, Z_ROD_Y, H_TOTAL/2, 4, 500, (0, 0, 1), c_acero)
add_cyl("Z_Varilla_Lisa_Izq",
        Z_ROD_X_IZQ_S, Z_ROD_Y, H_TOTAL/2, 4, Z_ROD_LEN, (0, 0, 1), c_acero)
add_cyl("Z_Varilla_Lisa_Der",
        Z_ROD_X_DER_S, Z_ROD_Y, H_TOTAL/2, 4, Z_ROD_LEN, (0, 0, 1), c_acero)

add_nema17("Z_Motor_Izq",
           0, Z_ROD_Y - 21, Z_MOT_Z_BASE, c_motor)
add_nema17("Z_Motor_Der",
           W_TOT - 42, Z_ROD_Y - 21, Z_MOT_Z_BASE, c_motor)

add_acople_flexible("Z_Acople_Izq",
                    Z_ROD_X_IZQ_T, Z_ROD_Y, 20, (0, 0, 1), c_aluminio)
add_acople_flexible("Z_Acople_Der",
                    Z_ROD_X_DER_T, Z_ROD_Y, 20, (0, 0, 1), c_aluminio)

add_cyl("Z_608ZZ_Superior_Izq",
        Z_ROD_X_IZQ_T, Z_ROD_Y, H_TOTAL - 14, 11, 7, (0, 0, 1), c_aluminio)
add_box("Z_Soporte_608_Izq",
        Z_ROD_X_IZQ_T - 25, Z_ROD_Y - 25, H_TOTAL - 21,
        50, 50, 6, c_madera_oscura)
add_cyl("Z_608ZZ_Superior_Der",
        Z_ROD_X_DER_T, Z_ROD_Y, H_TOTAL - 14, 11, 7, (0, 0, 1), c_aluminio)
add_box("Z_Soporte_608_Der",
        Z_ROD_X_DER_T - 25, Z_ROD_Y - 25, H_TOTAL - 21,
        50, 50, 6, c_madera_oscura)

# =============================================================================
# ── MODULO GANTRY X (marco rectangular arriba, SIN LM8UU) ──────────────────
# =============================================================================

G_X_LEFT_OUTER  = 0
G_X_LEFT_INNER  = G_X_LEFT_OUTER + G_BAR_W
G_X_RIGHT_INNER = W_TOT - G_BAR_W
G_X_RIGHT_OUTER = W_TOT

G_Y_FRONT = Z_ROD_Y - G_BAR_D/2
G_Y_BACK  = Z_ROD_Y + G_BAR_D/2
G_Z_BOT   = G_Z - G_BAR_H/2
G_Z_TOP   = G_Z + G_BAR_H/2

add_box("X_Gantry_Vertical_Izq",
        G_X_LEFT_OUTER, G_Y_FRONT, G_Z_BOT,
        G_BAR_W, G_BAR_D, G_BAR_H, c_aluminio)
add_box("X_Gantry_Vertical_Der",
        G_X_RIGHT_INNER, G_Y_FRONT, G_Z_BOT,
        G_BAR_W, G_BAR_D, G_BAR_H, c_aluminio)

add_box("X_Gantry_TopCross_Front",
        G_X_LEFT_OUTER, G_Y_FRONT, G_Z_TOP,
        W_TOT, E, G_TOP_THK, c_pino)
add_box("X_Gantry_TopCross_Back",
        G_X_LEFT_OUTER, G_Y_BACK - E, G_Z_TOP,
        W_TOT, E, G_TOP_THK, c_pino)

# =============================================================================
# ── MODULO EJE X (varillas + carro + hotend) ───────────────────────────────
# =============================================================================

add_cyl("X_Varilla_Inf",
        W_TOT/2, X_ROD_Y, X_ROD_Z_INF, 4, X_ROD_LEN, (1, 0, 0), c_acero)
add_cyl("X_Varilla_Sup",
        W_TOT/2, X_ROD_Y, X_ROD_Z_SUP, 4, X_ROD_LEN, (1, 0, 0), c_acero)

add_polea_gt2("X_Polea_Idler",
              G_X_LEFT_INNER + 4, X_ROD_Y, G_Z,
              (0, 1, 0), c_polea)
add_polea_gt2("X_Polea_Drive",
              G_X_RIGHT_OUTER - 4, X_ROD_Y, G_Z,
              (0, 1, 0), c_polea)

add_nema17("X_Motor_NEMA17_Derecha",
           G_X_RIGHT_OUTER, X_ROD_Y - 21, G_Z - 20, c_motor)

CARRO_X0 = CARRO_X_CENTER - CARRO_W/2
CARRO_Y0 = CARRO_Y_CENTER - CARRO_L/2
CARRO_Z0 = CARRO_Z_CENTER - CARRO_H/2
add_box("X_Carro",
        CARRO_X0, CARRO_Y0, CARRO_Z0,
        CARRO_W, CARRO_L, CARRO_H, c_aluminio)

add_lm8uu("X_LM8UU_Inf_Frontal",
          CARRO_X_CENTER, CARRO_Y_CENTER - 15, X_ROD_Z_INF,
          (1, 0, 0), c_aluminio)
add_lm8uu("X_LM8UU_Inf_Trasero",
          CARRO_X_CENTER, CARRO_Y_CENTER + 15, X_ROD_Z_INF,
          (1, 0, 0), c_aluminio)
add_lm8uu("X_LM8UU_Sup_Frontal",
          CARRO_X_CENTER, CARRO_Y_CENTER - 15, X_ROD_Z_SUP,
          (1, 0, 0), c_aluminio)
add_lm8uu("X_LM8UU_Sup_Trasero",
          CARRO_X_CENTER, CARRO_Y_CENTER + 15, X_ROD_Z_SUP,
          (1, 0, 0), c_aluminio)

MK8_W = 42
MK8_D = 57
MK8_H = 42
MK8_X0 = CARRO_X_CENTER - MK8_W/2
MK8_Y0 = CARRO_Y0 - MK8_D
MK8_Z0 = CARRO_Z_CENTER - MK8_H/2
add_box("X_Extrusor_MK8",
        MK8_X0, MK8_Y0, MK8_Z0, MK8_W, MK8_D, MK8_H, c_motor)

NOZZLE_TIP_Z = G_Z - NOZZLE_OFFSET
NOZZLE_BASE_Z = NOZZLE_TIP_Z + 15
add_cone("X_Boquilla_Laton",
         CARRO_X_CENTER, CARRO_Y_CENTER - 12, NOZZLE_BASE_Z,
         4, 0.4, 15, (0, 0, -1), c_cobre)

# =============================================================================
# ── FINALES DE CARRERA ──────────────────────────────────────────────────────
# =============================================================================

add_endstop("Endstop_X_Min",
            E - 5, X_ROD_Y - 3, X_ROD_Z_INF + 5, (1, 0, 0), c_endstop)
add_endstop("Endstop_Y_Min",
            W_TOT/2 - 3, E - 5, Y_ROD_Z + 15, (0, 1, 0), c_endstop)
add_endstop("Endstop_Z_Min",
            Z_ROD_X_IZQ_S - 5, Z_ROD_Y - 3, Z_MOT_Z_TOP + 5,
            (0, 0, 1), c_endstop)

# =============================================================================
# ── MODULO CEREBRO (vista exploded a la izquierda) ──────────────────────────
# =============================================================================

OX = -300
OY = 60
PARED = 15

caja_ext = Part.makeBox(180, 240, 110, Vector(OX, OY, 0))
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
print("MK-INCOS v5 - Prusa i3 bedslinger, cama optimizada 220x185")
print("Area util: ~190x165x180 mm  |  Chasis: 380x410x400 mm")
print("Y travel: 185mm (regla L_int/2)  |  Z travel: 200mm")
print("Marco sin piso, 4 pilares en esquinas, motores Z abajo")
print("Objetos en el documento: %d" % len(doc.Objects))
print("=" * 70)
print("")
print("PLANO DE CORTE DE MADERA (mm):")
print("-" * 70)
print("  #  Pieza                      Medidas       Cant  Funcion")
print("  1  Pared lateral marco Y     410 x 70 x 20    2  Laterales izq/der")
print("  2  Pared frontal/trasera     380 x 70 x 20    2  Frente/atras marco")
print("  3  Pilar Z esquina           330 x 20 x 20    4  Esquinas del arco")
print("  4  Gantry vertical           330 x 60 x 20    2  Laterales gantry X")
print("  5  Gantry top crossbar       380 x 20 x 20    2  Travesanos arriba")
print("  6  Soporte 608ZZ             50 x 50 x 6      2  Rodamiento sup Z")
print("  7  Cama MDF (cortar)         220 x 185 x 12   1  Sub-chasis cama")
print("-" * 70)
print("  Total madera 20mm: ~0.55 m² (1 planche 1.2x0.5m alcanza)")
print("  Cama: cortar plancha MDF 300x300 -> 220x185")
print("=" * 70)
