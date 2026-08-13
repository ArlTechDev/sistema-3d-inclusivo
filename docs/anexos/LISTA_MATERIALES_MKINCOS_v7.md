# Lista Completa de Materiales — MK-INCOS v7

**Impresora 3D para Recursos Educativos Inclusivos**
**Versión:** v7 (Arco Único + Gantry Doble Placa)
**Área imprimible:** ~190 × 165 × 180 mm (X × Y × Z)
**Chasis externo:** 420 × 410 × 400 mm

---

## Resumen Ejecutivo

| Categoría | Piezas | Costo estimado (BOB) |
|---|---|---|
| Madera y MDF | 10 piezas + vidrio | 80-130 |
| Mecánica (varillas, rulemanes) | 15+ componentes | 0 (ya comprado) |
| Electrónica de control | 1 set completo | 0 (ya comprado) |
| Tornillería y fijaciones | ~100 unidades | 25-45 |
| Consumibles de impresión | 1 set | 25-40 |
| **TOTAL ADICIONAL** | | **130-215 BOB** |

**Nota:** Los componentes de mecánica y electrónica ya fueron comprados previamente (ver sección B).

---

## A. MADERA Y CARPINTERÍA

### A.1 Plancha de pino 20mm (LO PRINCIPAL)

**Cantidad:** 1 planche de **1.2 × 0.6 m** (mínimo) o 1.5 × 0.6 m (recomendado con margen)
**Costo:** 50-80 BOB
**Proveedor:** Maderera local, Home Depot, Easy

### A.2 Piezas a cortar de la plancha de pino 20mm

| # | Pieza | Medidas exactas | Cantidad | Uso en la impresora |
|---|---|---|---|---|
| 1 | Pared lateral marco Y | 410 × 70 × 20 mm | 2 | Laterales izq/der del marco base |
| 2 | Pared frontal/trasera Y | 380 × 70 × 20 mm | 2 | Frente y atrás del marco base |
| 3 | Pilar lateral del arco | 380 × 80 × 20 mm | 2 | Postes verticales que sostienen el arco |
| 4 | Travesaño superior | 420 × 80 × 20 mm | 1 | Techo horizontal que une los 2 pilares |
| 5 | Escuadra triangular | Triángulo 120×120 mm, espesor 20 mm | 2 | Refuerzo lateral entre pilar y base |
| 6 | Placa inferior del gantry | 380 × 80 × 20 mm | 1 | Sostiene eje X inferior + tuercas Z inferiores |
| 7 | Placa superior del gantry | 380 × 80 × 20 mm | 1 | Sostiene eje X superior + tuercas Z superiores |

**Total piezas pino 20mm: 11 unidades**
**Área total utilizada: ~0.65 m²** (cabe en planche de 1.2×0.6m)

### A.3 MDF (fibra de densidad media)

| # | Pieza | Medidas | Cantidad | Uso |
|---|---|---|---|---|
| 8 | Espaciador del gantry | 15 × 80 × **5 mm** | 2 | Conecta las 2 placas del gantry (separa 5mm) |
| 9 | Soporte rulemán 608ZZ | 50 × 50 × 5-6 mm | 2 | Sostiene rulemán superior del eje Z |
| 10 | Sub-chasis de la cama | 220 × 185 × **12 mm** | 1 | Base donde se apoya el vidrio |

**Total MDF 5mm:** ~0.02 m² (retazo pequeño, se puede sacar de un recorte)
**Total MDF 12mm:** 1 pieza 220×185 mm (se corta de plancha de 300×300 mm)

**Costo MDF:**
- Plancha MDF 12mm de 30×30 cm: 15-25 BOB
- Retazo MDF 5mm: 5-10 BOB (o gratis si hay retazo disponible)

### A.4 Vidrio para la cama

| # | Pieza | Medidas | Cantidad | Uso |
|---|---|---|---|---|
| 11 | Vidrio float | 220 × 185 × **4 mm mínimo** (recomendado 5mm) | 1 | Superficie de impresión |

**Costo:** 15-25 BOB (con corte incluido en vidriería)
**Proveedor:** Vidriería local, corte a medida

---

## B. MECÁNICA Y MOVIMIENTO (YA COMPRADOS)

### B.1 Motores

| Componente | Cantidad | Estado |
|---|---|---|
| NEMA 17 (1.8° stepper) | 4 | ✅ Comprado |

### B.2 Varillas lisas ø8mm (acero calibrado)

| Componente | Cantidad | Largo | Estado |
|---|---|---|---|
| Varilla lisa Y (eje cama) | 2 | 400 mm | ✅ Comprado |
| Varilla lisa Z (estabilización) | 2 | 400 mm | ✅ Comprado |
| Varilla lisa X (carro extrusor) | 2 | 360 mm (cortar a 350 mm) | ✅ Comprado |

### B.3 Husillos (varillas roscadas)

| Componente | Cantidad | Largo | Estado |
|---|---|---|---|
| Husillo roscado M8 (eje Z) | 2 | 500 mm | ✅ Comprado |

### B.4 Rulemanes

| Componente | Cantidad | Dimensiones | Estado |
|---|---|---|---|
| SC8UU (bloque aluminio, eje Y) | 3 | 34×30×24 mm | ✅ Comprado |
| LM8UU (cilíndrico, eje X) | 4 | ø24×45 mm | ✅ Comprado |
| 608ZZ (rulemán superior eje Z) | 2 | ø22×7 mm | ✅ Comprado (en lista) |

### B.5 Acoples y correas

| Componente | Cantidad | Especificación | Estado |
|---|---|---|---|
| Acople flexible aluminio (motor Z ↔ husillo) | 2 | Eje 5mm → M8 | ✅ Comprado |
| Correa dentada GT2 | 2 metros lineales | Paso 2mm, ancho 6mm | ✅ Comprado |
| Polea GT2 drive 20 dientes | 2 | ø16mm, eje 5mm | ✅ Comprado |
| Polea loca GT2 idler (lisa) | 2 | ø16mm, eje 5mm | ✅ Comprado |

---

## C. ELECTRÓNICA DE CONTROL (YA COMPRADOS)

| Componente | Cantidad | Especificación | Estado |
|---|---|---|---|
| Arduino Mega 2560 | 1 | Con cable USB | ✅ |
| Placa RAMPS 1.4 | 1 | Para Arduino Mega | ✅ |
| Drivers A4988 (stepper) | 4 | Con disipadores | ✅ |
| Fuente ATX reciclada | 1 | 12V/20A mínimo | ✅ |
| Endstop mecánico | 3 | Micro switch con palanca | ✅ |

---

## D. TORNILLERÍA Y FIJACIONES (POR COMPRAR)

### D.1 Para motores NEMA 17 (4 motores)

| Componente | Cantidad | Especificación | Costo |
|---|---|---|---|
| Tornillo Allen M3 × 8mm | 16 | Cabeza cilíndrica, Allen 2.5mm | 3-5 BOB |
| Tuerca M3 | 16 | Hexagonal estándar | 1-2 BOB |

### D.2 Para bloques SC8UU (3 unidades bajo la cama)

| Componente | Cantidad | Especificación | Costo |
|---|---|---|---|
| Tornillo Allen M3 × 15mm | 12 | Cabeza cilíndrica, Allen 2.5mm | 3-5 BOB |
| Tuerca M3 | 12 | Hexagonal estándar | 1-2 BOB |

### D.3 Para estructura de madera (armado del chasis)

**Opción recomendada: MIXTA (clavos + cola + tirafondos críticos + escuadras metálicas)**

| Componente | Cantidad | Especificación | Costo |
|---|---|---|---|
| Clavos galvanizados 2.5 × 50mm | 40 und | Cabeza perdida, acero galvanizado | 2-3 BOB |
| Tirafondos 4 × 50mm | 12 und | Para pilar-base y travesaño-pilar | 4-6 BOB |
| Tirafondos 3.5 × 30mm | 16 und | Para escuadras y top crossbars | 3-5 BOB |
| Tirafondos 3.5 × 25mm | 8 und | Para varillas con abrazaderas | 2-3 BOB |
| Cola vinílica para madera 250ml | 1 | Tipo PVA, resistente al agua | 15-25 BOB |
| Escuadras metálicas L 40×40mm | 4 und | Acero galvanizado, con agujeros | 12-16 BOB |
| Tornillo M4 × 15mm + tuerca + arandela | 16 sets | Para fijar escuadras metálicas | 4-6 BOB |

**Subtotal estructura: 42-64 BOB**

### D.4 Para sistema anti-backlash del eje Z

| Componente | Cantidad | Especificación | Costo |
|---|---|---|---|
| Tuerca M8 hexagonal estándar | 4 | ISO 4032, acero galvanizado | 2-3 BOB |
| Resorte de compresión ø10×20mm | 2 | k ≈ 1-2 N/mm | 3-5 BOB |
| Contratuerca M8 nylock (opcional) | 2 | Para fijar tuerca inferior | 1-2 BOB |
| Tornillo M4 × 20mm + tuerca + arandela | 4 sets | Para fijar placa de gantry | 3-4 BOB |

**Subtotal anti-backlash: 9-14 BOB**

### D.5 Para el cristal/vidrio de la cama

| Componente | Cantidad | Especificación | Costo |
|---|---|---|---|
| Clips metálicos de papelería | 4 | Grandes, para sujetar el vidrio | 2-3 BOB |

---

## E. CONSUMIBLES DE IMPRESIÓN (POR COMPRAR)

| Componente | Cantidad | Especificación | Costo |
|---|---|---|---|
| Cinta masking azul (rollo) | 1 | Ancho 24mm, papel | 10-15 BOB |
| Pegamento en barra UHU Stic | 2 | Para aplicar sobre la cinta | 10-15 BOB |
| Laca fijadora para cabello (opcional) | 1 | Alternativa al UHU, mejor adherencia | 15-25 BOB |
| Filamento PLA 1.75mm | 1 kg | Color sólido (cualquiera) | 80-120 BOB |
| Boquilla 0.4mm de repuesto | 1 | Latón, para MK8 | 8-15 BOB |

**Subtotal consumibles: 123-190 BOB** (incluye filamento)

---

## F. HERRAMIENTAS NECESARIAS

### F.1 Para corte de madera

| Herramienta | Uso | ¿Ya tiene? |
|---|---|---|
| Sierra circular o caladora | Cortar las 7 piezas de pino 20mm | Verificar |
| Sierra de mano | Cortes rectos en la plancha | Verificar |
| Caladora con hoja para madera | Cortes curvos (escuadras triangulares) | Verificar |
| Lija #80, #120, #220 | Desbastar y suavizar todos los cortes | Necesario comprar |

### F.2 Para corte de varillas y precisión

| Herramienta | Uso | ¿Ya tiene? |
|---|---|---|
| Arco de sierra + hoja para metal | Cortar varillas X de 360→350 mm | Verificar |
| Amarrabotellas o prensa de banco | Sujetar varillas para corte | Verificar |
| Lima redonda | Desbarbar extremos cortados | Verificar |
| Calibrador digital (pie de rey) | Medir alineación de poleas | Necesario comprar |

### F.3 Para taladrar

| Herramienta | Uso | ¿Ya tiene? |
|---|---|---|
| Taladro eléctrico | Hacer todos los holes | Verificar |
| **Broca para madera ø8mm EXACTA** | Holes para varillas lisas (CRÍTICO) | **Necesario comprar** |
| Broca para madera ø3mm | Pre-taladros para tirafondos | Necesario comprar |
| Broca para madera ø8.5mm | Holes para varillas en placas (ajuste deslizante) | Necesario comprar |
| Broca para madera ø10mm | Hole pasante para polea loca | Necesario comprar |

### F.4 Para armado y calibración

| Herramienta | Uso | ¿Ya tiene? |
|---|---|---|
| Destornillador Phillips #2 | Tirafondos | Verificar |
| Llave Allen M3 (hex 2.5mm) | Tornillos de motores y SC8UU | Necesario comprar |
| Llave Allen M4 (hex 3mm) | Escuadras metálicas | Necesario comprar |
| Llave de tuercas 5.5mm o 7mm | Tuercas M3 y M8 | Verificar |
| Multímetro digital | Verificar continuidad eléctrica y voltajes | Necesario comprar |
| Nivel burbuja | Verificar horizontalidad de cama y arco | Necesario comprar |
| Cinta métrica | Verificar dimensiones finales | Verificar |
| Martillo | Clavar los clavos galvanizados | Verificar |

---

## G. PRESUPUESTO TOTAL ESTIMADO

### G.1 Lo que YA está comprado (no requiere inversión adicional)

| Categoría | Componentes | Costo ya invertido |
|---|---|---|
| Mecánica | 4 NEMA 17, 6 varillas lisas, 2 husillos, 2 acoples, 3 SC8UU, 4 LM8UU, 2 poleas drive, 2 poleas idler, 2m correa GT2 | ~400-600 BOB |
| Electrónica | 1 Arduino Mega, 1 RAMPS, 4 A4988, 1 ATX reciclada, 3 endstops | ~250-400 BOB |
| Madera marco Y | 4 paredes del marco (ya cortadas) | ~30-50 BOB |
| **Subtotal ya comprado** | | **~680-1050 BOB** |

### G.2 Lo que FALTA comprar (presupuesto nuevo)

| Categoría | Costo estimado |
|---|---|
| Plancha pino 20mm (1.2×0.6m) | 50-80 BOB |
| Plancha MDF 12mm (30×30cm) | 15-25 BOB |
| Retazo MDF 5mm | 5-10 BOB |
| Vidrio 220×185×4mm con corte | 15-25 BOB |
| Tornillería estructura (mixta) | 42-64 BOB |
| Sistema anti-backlash | 9-14 BOB |
| Consumibles impresión | 123-190 BOB |
| Herramientas faltantes | 80-150 BOB |
| **TOTAL NUEVO** | **339-558 BOB** |

### G.3 Inversión total del proyecto

| Concepto | Monto |
|---|---|
| Ya comprado | ~680-1050 BOB |
| Falta comprar | ~339-558 BOB |
| **TOTAL** | **~1019-1608 BOB** |

---

## H. ORDEN DE COMPRA SUGERIDO

### Compra 1 — CRÍTICA (no se puede empezar sin esto)
1. Plancha pino 20mm (1.2 × 0.6 m o más grande)
2. Plancha MDF 12mm (30 × 30 cm mínimo)
3. Vidrio 220 × 185 × 4 mm con corte
4. Broca ø8 mm EXACTA para madera
5. Broca ø3 mm
6. Broca ø8.5 mm
7. Broca ø10 mm
8. Lija #80, #120, #220

### Compra 2 — Para armado del chasis
1. Tirafondos 4×50mm (12 und)
2. Tirafondos 3.5×30mm (16 und)
3. Tirafondos 3.5×25mm (8 und)
4. Clavos galvanizados 2.5×50mm (40 und)
5. Cola vinílica 250ml
6. Escuadras metálicas L 40×40mm (4 und)
7. Tornillo M4×15mm + tuerca + arandela (16 sets)
8. Tornillo M4×20mm + tuerca + arandela (4 sets)
9. Tornillo Allen M3×8mm (16 und)
10. Tornillo Allen M3×15mm (12 und)
11. Tuerca M3 (28 und)
12. Tuerca M8 hexagonal (4 und)
13. Contratuerca M8 nylock (2 und, opcional)
14. Resorte compresión ø10×20mm (2 und)
15. Llave Allen M3 (2.5mm)
16. Llave Allen M4 (3mm)

### Compra 3 — Consumibles de impresión
1. Cinta masking azul (1 rollo, ancho 24mm)
2. Pegamento UHU Stic (2 und)
3. Filamento PLA 1.75mm (1 kg)
4. Boquilla 0.4mm de repuesto (1 und)
5. Clips metálicos grandes (4 und)

---

## I. PROCEDIMIENTO DE ARMADO (resumen)

### Etapa 1: Corte de madera
1. Cortar las 7 piezas de pino 20mm según la tabla A.2
2. Cortar las 2 piezas triangulares de 120×120mm (escuadras)
3. Cortar la cama MDF de 300×300 → 220×185
4. Cortar los 2 espaciadores de 15×80×5mm
5. Cortar los 2 soportes de 608ZZ de 50×50×6mm
6. Lijar todas las piezas (#80, luego #120, luego #220)

### Etapa 2: Marco Y base
1. Ensamblar las 4 paredes del marco (clavos + cola)
2. Reforzar las 4 esquinas con escuadras metálicas L
3. Dejar curar 24-48 horas

### Etapa 3: Empotrar varillas Y
1. Marcar las posiciones de las varillas (X=100 y X=280, Z=30)
2. Taladrar con broca ø8mm EXACTA en las paredes frontal y trasera
3. Insertar las varillas (queda 15mm empotrado en cada pared)

### Etapa 4: Cama y rulemanes Y
1. Atornillar los 3 SC8UU bajo la cama MDF
2. Ensamblar la cama sobre las varillas Y

### Etapa 5: Motor Y y poleas
1. Instalar motor Y dentro del hueco de la pared trasera
2. Instalar polea drive en el eje del motor
3. Instalar polea idler al frente con soporte
4. Colocar correa GT2 (tensar antes de fijar polea idler)

### Etapa 6: Arco (pilares + travesaño + escuadras)
1. Atornillar los 2 pilares a las paredes laterales del marco (tirafondos 4×50mm)
2. Colocar las 2 escuadras triangulares (clavos + cola)
3. Atornillar el travesaño superior a los pilares (tirafondos 4×50mm)

### Etapa 7: Eje Z
1. Montar los 2 motores Z en la base (dentro del marco, a los lados)
2. Acoplar los 2 husillos con los motores (acoples flexibles)
3. Colocar los 2 rulemanes 608ZZ con soporte arriba
4. Pasar las 2 varillas lisas Z a través de los motores y rulemanes

### Etapa 8: Gantry doble placa
1. Taladrar 4 holes en la placa inferior (X=21, X=56, X=324, X=359, todos a Y=205)
2. Taladrar 4 holes en la placa superior (mismas posiciones)
3. Montar las 4 tuercas M8 con resortes (2 arriba + 2 abajo de cada husillo)
4. Ensamblar las 2 placas con los 2 espaciadores (sandwich)
5. Fijar el gantry en los husillos y varillas lisas

### Etapa 9: Eje X y extrusor
1. Pasar las 2 varillas X (cortadas a 350mm) a través del gantry
2. Instalar las 2 poleas GT2 (idler a la izq, drive a la der)
3. Montar el motor X a la derecha del gantry
4. Colocar la correa GT2 del eje X
5. Ensamblar el carro X con los 4 LM8UU
6. Montar el extrusor MK8 al frente del carro
7. Colocar la boquilla

### Etapa 10: Electrónica
1. Montar Arduino + RAMPS en la caja del Cerebro
2. Conectar la fuente ATX
3. Cablear los 4 motores, 3 endstops, y la cama (si tuviera heatbed)
4. Cerrar la caja del Cerebro

### Etapa 11: Calibración
1. Verificar que el gantry se mueve suavemente en Z
2. Calibrar el eje Y (cama se mueve sin trabarse)
3. Calibrar el eje X (carro se mueve sin vibración)
4. Nivelar la cama con el endstop Z
5. Cargar firmware Marlin en Arduino
6. Probar con una pieza de calibración

---

## J. NOTAS IMPORTANTES

1. **La broca ø8mm debe ser EXACTA.** Si la broca "baila" y el hole queda de 8.5mm, las varillas vibrarán y la impresión saldrá con ondas. Comprar en ferretería de confianza.

2. **El vidrio debe cortarse en vidriería con herramienta profesional**, no a mano. Un corte mal hecho puede romper el vidrio.

3. **La cama MDF debe cortarse con sierra de mesa o caladora con guía**, no con sierra de mano. Un corte desviado hará que la cama no entre en las varillas.

4. **Aplicar cola en TODAS las uniones de madera** antes de clavar/atornillar. La cola pega Y sella contra humedad.

5. **El marco Y debe estar perfectamente nivelado** antes de montar el arco. Usar nivel de burbuja en las 4 esquinas.

6. **Los tirafondos del pilar-base y travesaño-pilar son CRÍTICOS** (no usar clavos ahí). Soportan el peso del arco completo.

7. **Los rulemanes 608ZZ son IMPORTANTES** arriba de los husillos. Sin ellos, los husillos vibran y pierden pasos.

8. **El sistema anti-backlash es OPCIONAL pero recomendado.** Sin él, las capas Z pueden tener desniveles de 0.5-1mm.

---

## K. CHECKLIST DE COMPRA RÁPIDA

Imprimí esta lista y llevala a la ferretería:

```
MADERA
[ ] Plancha pino 20mm (1.2 × 0.6 m) .................. ~60 BOB
[ ] Plancha MDF 12mm (30 × 30 cm) .................... ~20 BOB
[ ] Retazo MDF 5mm (pequeño) ........................ ~8 BOB
[ ] Vidrio float 220×185×4mm con corte .............. ~20 BOB

TORNILLERÍA
[ ] Tirafondos 4×50mm (12 und) ...................... ~5 BOB
[ ] Tirafondos 3.5×30mm (16 und) .................... ~4 BOB
[ ] Tirafondos 3.5×25mm (8 und) ..................... ~2 BOB
[ ] Clavos galvanizados 2.5×50mm (40 und) ........... ~2 BOB
[ ] Tornillo Allen M3×8mm (16 und) + tuerca M3 (16) . ~5 BOB
[ ] Tornillo Allen M3×15mm (12 und) + tuerca M3 (12)  ~4 BOB
[ ] Tornillo M4×15mm (16 sets c/tuerca+arandela) .... ~5 BOB
[ ] Tornillo M4×20mm (4 sets c/tuerca+arandela) ..... ~3 BOB
[ ] Tuerca M8 hexagonal (4 und) ..................... ~2 BOB
[ ] Contratuerca M8 nylock (2 und, opcional) ....... ~1 BOB
[ ] Resorte compresión ø10×20mm (2 und) ............ ~4 BOB
[ ] Escuadras metálicas L 40×40mm (4 und) ........... ~14 BOB

ADHESIVOS Y CONSUMIBLES
[ ] Cola vinílica 250ml ............................. ~20 BOB
[ ] Cinta masking azul (rollo 24mm) ................. ~12 BOB
[ ] Pegamento UHU Stic (2 und) ...................... ~12 BOB
[ ] Filamento PLA 1.75mm (1 kg) ..................... ~100 BOB
[ ] Boquilla 0.4mm repuesto (1 und) ................. ~10 BOB
[ ] Clips metálicos grandes (4 und) ................. ~2 BOB

HERRAMIENTAS (solo lo que falte)
[ ] Broca madera ø8mm EXACTA ....................... ~20 BOB
[ ] Broca madera ø3mm ............................... ~8 BOB
[ ] Broca madera ø8.5mm ............................. ~12 BOB
[ ] Broca madera ø10mm .............................. ~10 BOB
[ ] Lija #80, #120, #220 (set) ...................... ~8 BOB
[ ] Llave Allen M3 (2.5mm) .......................... ~5 BOB
[ ] Llave Allen M4 (3mm) ............................ ~5 BOB
[ ] Calibrador digital .............................. ~30 BOB
[ ] Multímetro digital .............................. ~40 BOB
[ ] Nivel burbuja .................................... ~15 BOB

─────────────────────────────────────────
TOTAL ESTIMADO NUEVO:          ~470 BOB (rango 339-558)
─────────────────────────────────────────
```

---

**Fin del documento.**
**Generado para:** MK-INCOS v7 — Impresora 3D para Recursos Educativos Inclusivos
**Versión del documento:** 1.0
**Compatible con archivo CAD:** `Impresora_3D_MKINCOS_v7.py`
