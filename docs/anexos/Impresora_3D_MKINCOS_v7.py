# =============================================================================
# MK-INCOS 22x16.5x18 - v7 (Arco Unico + Gantry Doble Placa)
# =============================================================================
# BLUEPRINT
#   Area de impresion:    ~190 x 165 x 180 mm  (X x Y x Z, con margen 10mm)
#   Chasis base:          380 x 410 x 70 mm   (marco Y sin piso)
#   Chasis externo:       420 x 410 x 400 mm  (incluye arco lateral)
#   Cama:                 220 x 185 x 12 mm  (cortada de 300x300, MDF+vidrio 4mm)
#   Aplicacion:           Recursos educativos 3D para discapacidad visual
#
# AUDITORIA - v6 -> v7 (Gantry Doble Placa)
#   F-1  Gantry v6: 5 piezas (2 verticales + 2 top crossbars + 1 placa 260mm)
#   F-2  Gantry v7: 4 piezas (2 placas 380mm + 2 espaciadores 15mm)
#   F-3  +Rigidez: estructura sandwich vs marco abierto
#   F-4  +Acceso a tuercas Z: quedan entre las 2 placas (espacio 5mm)
#   F-5  Misma area imprimible, mismo Z travel, misma electronica
#
# EJECUCION
#   exec(open("/mnt/extens/incos/utils/web3/sistema_inclusivo/docs/anexos/Impresora_3D_MKINCOS_v7.py").read())
# =============================================================================

import FreeCAD as App
import Part
from FreeCAD import Vector

doc_name = "Impresora_3D_MKINCOS_v7"
if doc_name in App.listDocuments():
    App.closeDocument(doc_name)
doc = App.newDocument(doc_name)

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

E       = 20
W_INT   = 340
L_INT   = 370
W_TOT   = W_INT + 2 * E
L_TOT   = L_INT + 2 * E
ALTO_Y  = 70
H_TOTAL = 400

BED_W   = 220
BED_L   = 185
SUB_T   = 8
VID_T   = 4
BED_Z   = ALTO_Y
BED_X   = (W_INT - BED_W) / 2
BED_Y0  = (L_INT - BED_L) / 2 + E

Y_ROD_X1    = 100
Y_ROD_X2    = 280
Y_ROD_Z     = 30
Y_ROD_LEN   = 400

Z_ROD_X_IZQ_T = 21
Z_ROD_X_IZQ_S = 56
Z_ROD_X_DER_T = W_TOT - 21
Z_ROD_X_DER_S = W_TOT - 56
Z_ROD_Y       = L_TOT / 2
Z_MOT_Z_BASE  = 0
Z_MOT_Z_TOP   = 40
Z_ROD_LEN     = 400

PILAR_W       = 80
PILAR_T       = 20
PILAR_Z_BASE  = ALTO_Y
PILAR_Z_TOP   = H_TOTAL
ESCUADRA_LEG  = 120
ESCUADRA_T    = 20

# Gantry doble placa (v7)
G_Z           = 330
PLACA_W       = W_TOT
PLACA_D       = 80
PLACA_T       = 20
ESP_W         = 15
ESP_T         = 5
PLACA_INF_Z   = 300
ESP_Z         = 320
PLACA_SUP_Z   = 325

X_ROD_Z_INF   = PLACA_INF_Z + 15
X_ROD_Z_SUP   = PLACA_SUP_Z + 20
X_ROD_Y       = Z_ROD_Y
X_ROD_LEN     = 360

NOZZLE_OFFSET = 50
CARRO_W = 60
CARRO_L = 50
CARRO_H = 60
CARRO_X_CENTER = W_TOT / 2
CARRO_Y_CENTER = X_ROD_Y
CARRO_Z_CENTER = G_Z

# =============================================================================
# HELPERS
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

def add_escuadra(nombre, x_inner, x_outer, y_start, z_base, leg_z, color):
    v1 = Vector(x_inner, 0, 0)
    v2 = Vector(x_outer, 0, 0)
    v3 = Vector(x_inner, 0, leg_z)
    wire = Part.makePolygon([v1, v2, v3, v1])
    face = Part.Face(wire)
    bracket = face.extrude(Vector(0, ESCUADRA_T, 0))
    bracket.translate(Vector(0, y_start, z_base))
    obj = doc.addObject("Part::Feature", nombre)
    obj.Shape = bracket
    obj.ViewObject.ShapeColor = color
    return obj

# =============================================================================
# MODULO Y: MARCO SIN PISO + CAMA + EJE Y
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

add_cyl("Y_Varilla_Izq",
        Y_ROD_X1, 200, Y_ROD_Z, 4, Y_ROD_LEN, (0, 1, 0), c_acero)
add_cyl("Y_Varilla_Der",
        Y_ROD_X2, 200, Y_ROD_Z, 4, Y_ROD_LEN, (0, 1, 0), c_acero)

SC8UU_Y_FRONT = BED_Y0 + 15
SC8UU_Y_BACK  = BED_Y0 + BED_L - 15
SC8UU_Y_MID   = BED_Y0 + BED_L/2
add_sc8uu("Y_SC8UU_Izq_Frente", Y_ROD_X1 - 17, SC8UU_Y_FRONT - 15,
          Y_ROD_Z - 12, c_aluminio)
add_sc8uu("Y_SC8UU_Izq_Atras",  Y_ROD_X1 - 17, SC8UU_Y_BACK - 15,
          Y_ROD_Z - 12, c_aluminio)
add_sc8uu("Y_SC8UU_Der_Centro", Y_ROD_X2 - 17, SC8UU_Y_MID - 15,
          Y_ROD_Z - 12, c_aluminio)

add_nema17("Y_Motor_NEMA17_Dentro_Pared",
           W_TOT/2 - 21, L_TOT - 42, 0, c_motor)
add_polea_gt2("Y_Polea_Drive",
              W_TOT/2, L_TOT - 42, Y_ROD_Z,
              (0, 1, 0), c_polea)

add_box("Y_Soporte_Idler",
        W_TOT/2 - 10, -20, Y_ROD_Z - 7, 20, 20, 15, c_pino)
add_polea_gt2("Y_Polea_Idler",
              W_TOT/2, -10, Y_ROD_Z, (0, 1, 0), c_polea)

# =============================================================================
# MODULO ARCO UNICO: 2 pilares laterales + travesano + 2 escuadras
# =============================================================================

PILAR_Y0 = Z_ROD_Y - PILAR_W/2
PILAR_Y1 = Z_ROD_Y + PILAR_W/2

add_box("Arco_Pilar_Izq",
        -PILAR_T, PILAR_Y0, PILAR_Z_BASE,
        PILAR_T, PILAR_W, PILAR_Z_TOP - PILAR_Z_BASE, c_pino)
add_box("Arco_Pilar_Der",
        W_TOT, PILAR_Y0, PILAR_Z_BASE,
        PILAR_T, PILAR_W, PILAR_Z_TOP - PILAR_Z_BASE, c_pino)

add_box("Arco_Travesano_Superior",
        -PILAR_T, PILAR_Y0, H_TOTAL - 20,
        W_TOT + 2*PILAR_T, PILAR_W, 20, c_pino)

add_escuadra("Arco_Escuadra_Izq",
             0, -ESCUADRA_LEG, PILAR_Y0, PILAR_Z_BASE,
             ESCUADRA_LEG, c_madera_oscura)
add_escuadra("Arco_Escuadra_Der",
             W_TOT, W_TOT + ESCUADRA_LEG, PILAR_Y0, PILAR_Z_BASE,
             ESCUADRA_LEG, c_madera_oscura)

# =============================================================================
# MODULO EJE Z (husillos + varillas + motores abajo)
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
# MODULO GANTRY DOBLE PLACA (v7 - simplificado)
# =============================================================================

GANTRY_Y0 = Z_ROD_Y - PLACA_D/2
GANTRY_Y1 = Z_ROD_Y + PLACA_D/2

add_box("Gantry_Placa_Inferior",
        0, GANTRY_Y0, PLACA_INF_Z,
        PLACA_W, PLACA_D, PLACA_T, c_pino)
add_box("Gantry_Placa_Superior",
        0, GANTRY_Y0, PLACA_SUP_Z,
        PLACA_W, PLACA_D, PLACA_T, c_pino)

add_box("Gantry_Espaciador_Izq",
        0, GANTRY_Y0, ESP_Z,
        ESP_W, PLACA_D, ESP_T, c_madera_oscura)
add_box("Gantry_Espaciador_Der",
        W_TOT - ESP_W, GANTRY_Y0, ESP_Z,
        ESP_W, PLACA_D, ESP_T, c_madera_oscura)

# =============================================================================
# MODULO EJE X (varillas + carro + hotend)
# =============================================================================

add_cyl("X_Varilla_Inf",
        W_TOT/2, X_ROD_Y, X_ROD_Z_INF, 4, X_ROD_LEN, (1, 0, 0), c_acero)
add_cyl("X_Varilla_Sup",
        W_TOT/2, X_ROD_Y, X_ROD_Z_SUP, 4, X_ROD_LEN, (1, 0, 0), c_acero)

add_polea_gt2("X_Polea_Idler",
              4, X_ROD_Y, G_Z,
              (0, 1, 0), c_polea)
add_polea_gt2("X_Polea_Drive",
              W_TOT - 4, X_ROD_Y, G_Z,
              (0, 1, 0), c_polea)

add_nema17("X_Motor_NEMA17_Derecha",
           W_TOT, X_ROD_Y - 21, G_Z - 20, c_motor)

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
# FINALES DE CARRERA
# =============================================================================

add_endstop("Endstop_X_Min",
            E - 5, X_ROD_Y - 3, X_ROD_Z_INF + 5, (1, 0, 0), c_endstop)
add_endstop("Endstop_Y_Min",
            W_TOT/2 - 3, E - 5, Y_ROD_Z + 15, (0, 1, 0), c_endstop)
add_endstop("Endstop_Z_Min",
            Z_ROD_X_IZQ_S - 5, Z_ROD_Y - 3, Z_MOT_Z_TOP + 5,
            (0, 0, 1), c_endstop)

# =============================================================================
# MODULO CEREBRO
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
# RECOMPUTE + VISTA
# =============================================================================

doc.recompute()
import FreeCADGui as Gui
Gui.activeDocument().activeView().viewIsometric()
Gui.SendMsgToActiveView("ViewFit")

print("=" * 70)
print("MK-INCOS v7 - Arco Unico + Gantry Doble Placa")
print("Area util: ~190x165x180 mm  |  Base: 380x410x70 mm")
print("Arco: 2 pilares + 1 travesano + 2 escuadras")
print("Gantry: 2 placas (380x80x20) + 2 espaciadores (15x80x5)")
print("Y travel: 185mm  |  Z travel: ~200mm")
print("Objetos en el documento: %d" % len(doc.Objects))
print("=" * 70)
print("")
print("PLANO DE CORTE v7 (11 piezas):")
print("-" * 70)
print("  #  Pieza                      Medidas         Cant  Material")
print("  1  Pared lateral marco Y     410 x 70 x 20     2  Pino 20mm")
print("  2  Pared frontal/trasera     380 x 70 x 20     2  Pino 20mm")
print("  3  Pilar lateral del arco    380 x 80 x 20     2  Pino 20mm")
print("  4  Travesano superior        420 x 80 x 20     1  Pino 20mm")
print("  5  Escuadra triangular      120x120 tri x 20   2  Pino 20mm")
print("  6  Gantry placa inferior     380 x 80 x 20     1  Pino 20mm")
print("  7  Gantry placa superior     380 x 80 x 20     1  Pino 20mm")
print("  8  Gantry espaciador         15 x 80 x 5       2  MDF 5mm")
print("  9  Soporte 608ZZ             50 x 50 x 6       2  MDF 5/6mm")
print(" 10  Cama MDF (cortar)         220 x 185 x 12    1  MDF 12mm")
print(" 11  Vidrio cama (mandar cortar) 220 x 185 x 4    1  Vidrio float")
print("-" * 70)
print("  Total pino 20mm: ~0.65 m² (1 planche 1.2x0.6m)")
print("  Total MDF 12mm: 1 pieza 220x185 (de plancha 300x300)")
print("  Total MDF 5mm: ~0.02 m² (pequeño retazo)")
print("  Vidrio: 220x185x4mm con corte en vidrieria")
print("=" * 70)
