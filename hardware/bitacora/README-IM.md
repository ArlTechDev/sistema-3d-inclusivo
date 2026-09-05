# Bitácora Completa — Impresora 3D Casera

> **Repositorio original:** https://github.com/ag-cris21/Impresora-3D-casera
> **Playlist YouTube:** https://www.youtube.com/playlist?list=PLODq3_P6bfsg
> **Índice hardware:** `hardware/README.md` · `hardware/marlin_firmware/README.md`

Recopilación histórica **ene–ago 2026** — migrada a single-repo el 2026-08-23 (previa a web 2026-05-20). Este documento es el reporte general; los archivos individuales `01-dia-1.md` … `13-dia-16.md` se mantienen como detalle.

## Índice

| # | Archivo | Fecha | Hito |
|---|---------|-------|------|
| 1 | `01-dia-1.md` | 27 ene 2026 | Arduino Mega + RAMPS, motor prueba |
| 2 | `02-dia-2.md` | 28 ene | DRV8825 comprados |
| 3 | `03-dia-3.a-5.md` | 29 ene–01 feb | Revisión stock, limpieza, mantenimiento fuente |
| 4 | `04-dia-6.md` | 02 feb | Arduino+RAMPS, jumpers, DRV8825 |
| 5 | `05-dia-7-a-13.md` | 03–15 feb | Marlin, Pronterface, humo Vref 1.68→0.59V |
| 6 | `06-dia-14.md` | 16 feb | Ejes X/Y/Z/E, M500 EEPROM |
| 7 | `07-dia-13.md` | 13 abr | Varillas 8mm, aprobación sociocomunitario |
| 8 | `08-avances-abril.md` | 07 may | Varillas reciclaje |
| 9 | `09-dia-12.md` | 21 may | Modelos madera, videos YouTube |
| 10 | `10-mes-junio.md` | 30 jun | Ender 3 elegido |
| 11 | `11-dia-1-7.md` | 01 jul | Estructura + cama movible |
| 12 | `12-dia-4-a-10.md` | 04–10 jul | Ensamblaje, corte Y, resultado final |
| 13 | `13-dia-16.md` | 16 ago | Reemplazo cama Y, primera impresión funcional | `../fotos_avance/2026-08-16_00-00-00.jpg` |
| 14 | `fotos_avance/2026-07-18_09-48-27.jpg` | 18 jul 2026 | Impresora terminada y funcional (foto final) | `../fotos_avance/2026-07-18_09-48-27.jpg` |

## Videos de referencia

| # | Video | Estado | Fecha |
|---|-------|--------|-------|
| 1 | Calibración inicial | En subida | 2026-08-23 |
| 2 | Prueba motores/DRV8825 | Organizado | 2026-09-04 |
| 3 | Reemplazo cama Y + calibración XYZ | Listo | 2026-08-16 |
| 4 | Primera impresión funcional | Listo | 2026-08-16 |

> Ver `hardware/marlin_firmware/README.md` para Marlin/Pronterface.

---

## Reporte General — Copia formalizada de cada día

> A continuación se copia íntegramente cada bitácora individual, ya mejorada visualmente, para tener **todo junto en uno** manteniendo los archivos originales.

### 01-dia-1.md — Impresora 3D

## Día 1 — 27 de enero de 2026

### Información
Desde hace mucho tiempo me interesan los temas relacionados con la impresión 3D.  
Decidí construir mi propia impresora utilizando materiales reciclados y con pocos recursos invertidos.
 

### Materiales
- Arduino Mega
- Motor paso a paso (de pruebas)
- RAMPS 1.4 (sin abrir)
- Fuente de poder y ventilador (obtenidos de una PC antigua)

## Fotos

<div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: center;">

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/Arduino.jpg" alt="Arduino Mega" width="380" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
  <figcaption style="font-size: 12px; color: #666; margin-top: 4px;">Arduino Mega — controlador principal</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/motorPrueba.jpg" alt="Motor paso a paso" width="380" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
  <figcaption style="font-size: 12px; color: #666; margin-top: 4px;">Motor paso a paso — pruebas iniciales</figcaption>
</figure>

</div>

## Futuros pasos
- Comprar los drivers DRV8825
- Buscar materiales para la estructura (metal macizo)
- Comenzar con cuidado la calibración de los drivers

---

### 02-dia-2.md — Día 2 — 28 de enero de 2026

> **Avance del día:** sin pruebas físicas — avance logístico: **compra de componentes clave**.

## Compras realizadas

| Componente | Detalle | Estado |
|---|---------|--------|
| **DRV8825** | Drivers paso a paso (x4) | ✅ Comprados |
| **Disipadores** | Aluminio para DRV8825 | ✅ Comprados |

Con esta compra, ya cuento con los elementos principales para el control de motores.

## Estado actual

> 🟡 **En acopio** — drivers aún no instalados ni probados. Falta definir motores paso a paso adecuados. Objetivo: reunir todo antes de pruebas.

- ⏳ Drivers: pendientes de instalación
- ⏳ Motores: por seleccionar (NEMA 17)
- 🎯 Próximo hito: montaje electrónica + pruebas básicas drivers

## Próximos pasos

- [ ] Buscar motores NEMA 17 compatibles
- [ ] Preparar montaje inicial electrónica (RAMPS + Arduino)
- [ ] Pruebas básicas DRV8825 cuando haya tiempo

---

### 03-dia-3.a-5.md — Días 3–6 — 29 de enero al 1 de febrero

> **Periodo de preparación:** revisión, organización y mantenimiento — sin avance directo, pero necesario para entorno y stock.

| Día | Fecha | Actividad | Detalle |
|---|-------|-----------|---------|
| 3 | 29 ene | 🔍 Revisión stock | Arduino Mega, RAMPS 1.4, 4× DRV8825, motor paso a paso, fuente+ventilador PC |
| 4 | 30 ene | 🧹 Limpieza | Mesa principal y cajones de herramientas |
| 5 | 31 ene | 🔧 Mantenimiento | Limpieza fuente PC tras extracción (polvo) |
| 6 | 01 feb | ⏸ Pausa | Sin actividades |

> **Check:** stock verificado, área limpia, fuente lista.

---

### 04-dia-6.md — Día 6 — 2 de febrero de 2026

> **Avance:** armado del cerebro — Arduino Mega + RAMPS 1.4

<div style="text-align: center;">
  <img src="../fotos_avance/dos.jpg" alt="Arduino + RAMPS" width="520" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
  <div style="font-size: 12px; color: #666;">Cerebro: Arduino Mega + RAMPS 1.4</div>
</div>

## Unir Arduino y RAMPS — pasos y recomendaciones

| # | Paso | Detalle | Foto |
|---|------|---------|------|
| 1 | Alinear pines traseros | RAMPS ↔ Arduino (pines traseros alineados) | — |
| 2 | Presionar alineado | Usar esponja de la RAMPS para presión uniforme | `ArdunoyRamps.jpg` |
| 3 | Verificar pines | Ningún pin suelto/doblado/visible fuera de posición | `ArdunoyRamps-01ç.jpg` |
| 4 | Jumpers (15) | Orientación indiferente, centrados por eje | `jumpers.jpg` / `Ramps.jpg` |
| 5 | DRV8825/A4988 | Pines `DIR/STEP/GND` alineados con RAMPS — evitar daños | `Drv8825.jpg` / `Ramps-1-4.jpg` |

<div style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; margin: 16px 0;">

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/ArdunoyRamps.jpg" alt="Unión Arduino-RAMPS" width="360" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Unión Arduino-RAMPS</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/ArdunoyRamps-01ç.jpg" alt="Detalle pines" width="360" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Detalle pines alineados</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/jumpers.jpg" alt="Jumpers" width="360" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">15 jumpers centrados</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/Ramps.jpg" alt="RAMPS" width="360" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">RAMPS 1.4</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/Drv8825.jpg" alt="DRV8825" width="360" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">DRV8825 — alinear DIR/STEP/GND</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/Ramps-1-4.jpg" alt="RAMPS 1.4 detalle" width="360" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">RAMPS 1.4 detalle</figcaption>
</figure>

</div>

## Próximos pasos

- Calibración drivers y pruebas de movimiento
- Montaje estructura y cama Y

---

### 05-dia-7-a-13.md — Días 7–15 — 3 al 15 de febrero

> **Contexto:** retorno a INCOS Álvarez Plata → tiempo reducido, pero avance constante.

| Fecha | Hito | Detalle |
|-------|------|---------|
| 12-02 | 🛒 Compras | Cama caliente, finales de carrera, 10 m filamento, AWG12 (faltó sensor cama) → **etapa electrónica completa** |

---

## 07-02 — Instalación de Marlin

El día 07-02 incorporé Marlin al Arduino Mega.

Siguiendo recomendaciones de varios videos e incluso IA, intenté instalar Marlin directamente desde el repositorio oficial de GitHub.

Sin embargo, hubo problemas.

Los repositorios actuales de Marlin están pensados para impresoras industriales ya construidas, no para impresoras caseras desde cero.  
La confusión fue principalmente por no leer completamente la documentación oficial.

La versión principal de GitHub incluía solo:

- 4 archivos principales
- 2 carpetas
- Configuraciones específicas para placas comerciales

Después de investigar en otra sección de la web de Marlin, encontré una versión más adecuada que incluía más de 10 archivos y la estructura completa necesaria para Arduino Mega.

Lo más importante fue el archivo:

`Configuration.h`

Este archivo permite:

- Definir el tipo de placa
- Configurar drivers
- Definir cantidad de extrusores (1 o 2)
- Determinar cantidad de motores (4, 5 o 6)
- Ajustar parámetros base de la impresora

Este error me costó aproximadamente 2 días solo por no leer completamente la documentación.

---

## Días 9 al 13 — Recolección de piezas

Durante la segunda semana del instituto, junto a mi compañero encontramos impresoras de tinta antiguas de familiares que no las usaban desde hace años.

El objetivo fue desarmarlas y reutilizar piezas útiles como:

- Motores
- Ejes
- Tornillos
- Componentes estructurales

---

# Día 15-02 — Primer intento de movimiento de motores

Comencé el día con mucha emoción, ya que quería mover los motores paso a paso por primera vez.

Me guié con videos e IA.

## Instalación de Pronterface

Instalé Pronterface en una laptop secundaria por seguridad, en caso de dañar algún componente.

Conecté únicamente el “cerebro”:

- Arduino Mega
- RAMPS 1.4
- Drivers

Desde Pronterface pude detectar:

- Versión de Marlin
- Estado de pines

```gcode
M115 ; info Marlin + estado pines
```

## Conexión de motores y finales de carrera

Conecté:

- Motores paso a paso en sus respectivos ejes
- Finales de carrera

Probé los endstops.

```gcode
M119 ; endstops
; open / triggered al presionar
```

## Problema grave — Salida de humo

Pensando que todo estaba correcto, conecté dos motores al mismo tiempo.

Comenzó a salir humo del sistema.

Desconecté todo inmediatamente.

Esperé que enfriara y separé todos los componentes.

El mayor temor era que el Arduino Mega se hubiera quemado.

---

## Verificación de daños

### 1. Arduino Mega

Lo conecté solo por USB.

Pronterface detectó Marlin correctamente.

Conclusión: Arduino funcional.

---

### 2. RAMPS 1.4

Medí continuidad y voltajes en:

- Entradas de 12V
- GND

Al inicio parecía extraño, pero resultó ser comportamiento normal.

Luego conecté RAMPS + fuente y medí voltajes en distintos puntos.

Todo correcto.

Conclusión: RAMPS no quemada.

---

### 3. Drivers DRV8825

Probé uno por uno.

Procedimiento:

- Desconectar todo
- Colocar solo un driver en eje X
- Conectar USB
- Luego conectar 12V
- Verificar si calentaba excesivamente

Repetí el proceso con los 4 drivers.

Conclusión: ninguno estaba quemado.

---

## Segunda prueba — Movimiento del motor

Conecté solo un motor:

```gcode
G1 E-5 F300 ; mover extrusor -5mm
```

El motor se movió, pero el driver se calentó demasiado — ahí estaba el problema.

## Causa real del humo

No regulé correctamente el voltaje de referencia (Vref) de los DRV8825.

El valor estaba en:

1.68V

Eso era demasiado alto para el motor que estaba usando.

Por eso:

- Se calentó excesivamente
- Generó humo
- Casi quema el sistema

---

## Ajuste correcto de Vref

Con multímetro:

- Punta negra en GND (terminal verde RAMPS)
- Punta roja en el tornillo del potenciómetro del driver

Ajusté milimétricamente hasta:

- 0.59V
- 0.59V
- 0.59V
- 0.62V

Rango recomendado aproximado: 0.55V – 0.65V

---

## Resultado después del ajuste

```gcode
G1 E-5 F300
```

El motor se movió correctamente.

| Medición | Valor | Estado |
|----------|-------|--------|
| Ambiente | 23–24 °C | — |
| Driver tras movimiento | 30–31 °C | ✅ Seguro (Δ 7 °C) |

> *Nota:* sonido “trrr” al finalizar es normal por microstepping inicial.

## Conclusión del Día 15

- Arduino funcional
- RAMPS funcional
- Drivers funcionales
- Motores operativos
- Problema identificado y corregido

El humo fue causado por una mala regulación del Vref.

Aprendizaje importante:
Siempre ajustar los drivers antes de conectar motores.

---

# Próximo paso

Intentar mover todos los motores juntos de forma controlada y segura.

---

### 06-dia-14.md — Día 14 — 16 de febrero de 2026

> **Avance:** movimiento X, Y, Z y E con éxito — sin quemar nada :D

## Calibración Z

Eje Z dio más recorrido que X/Y por pasos mal configurados en Marlin. Corregido al vuelo vía Pronterface (bajar `steps/mm` Z) → movimientos ya correctos.

## Fallos detectados

Al intentar guardar con:

```gcode
M500 ; guarda en EEPROM
```

Error: no guarda. Causa: hay que descomentar en `Configuration.h` la línea que habilita `EEPROM_SETTINGS` — lo haremos mañana.

| Eje | Estado | Nota |
|-----|--------|------|
| X | ✅ OK |  |
| Y | ✅ OK |  |
| Z | ✅ Corregido | pasos ajustados vía Pronterface |
| E | ✅ OK |  |

## Estado actual

Estamos en fase real de calibración; de momento solo un sentido por eje.

## Próximos pasos

- [ ] Base eje Y / cama caliente (solo base, resto espera)
- [ ] Comprar sensor temperatura cama
- [ ] Desarmar impresoras recicladas → conseguir carriles

---

### 07-dia-13.md — Día 16 — 13 de abril de 2026

> 🎉 **Hito:** proyecto aprobado con enfoque sociocomunitario para no videntes (INCOS).

## Avances

| Área | Detalle | Estado |
|------|---------|--------|
| Tiempo | Uni + INCOS → menos horas | 🟡 |
| Varillas | Desarme impresoras tinta, varias de 8 mm conseguidas (faltan 2 para X) | 🟡 |
| Proveedor | Distribuidora barata localizada | ✅ |

## Complicaciones

- 🔍 Varillas 8 mm >30 cm escasas
- 📐 Diseño en discusión por medidas
- 🔧 Ejes lineales compatibles por conseguir

## Próximos pasos

- [ ] Correas + poleas X/Y
- [ ] Construcción eje Y con varillas existentes

---

### 08-avances-abril.md — Día 16 — 07 de mayo de 2026

> **Periodo 14 abr–06 may:** sin avances técnicos — foco Uni/INCOS + búsqueda varillas 8 mm >30 cm (pendiente desde 13-04).

## Avances 07-05

| Hito | Detalle | Estado |
|------|---------|--------|
| Varillas | Varias de 8 mm de impresoras en desuso | ✅ |
| Reciclaje | Lugar barato localizado para estructura | ✅ |
| Rodamientos | En búsqueda | 🟡 |
| Tiempo | Uni + pasantías por iniciar | 🟡 |

<div style="text-align: center; margin: 16px 0;">
  <img src="../fotos_avance/2026-05-07.jpg" alt="Varillas 8mm" width="480" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
  <div style="font-size: 11px; color: #666;">07-05 — varillas 8 mm recuperadas</div>
</div>

## Complicaciones

- ⏳ Tiempo limitado por estudios

## Próximos pasos

- [ ] Definir modelo/estructura (madera — compañero hábil)
- [ ] Conseguir rodamientos

---

### 09-dia-12.md — Día 07 — 21 de mayo de 2026

> **Mes de mayo:** documentación y exploración de modelos — estructura principal en **madera**.

## Modelos de referencia

| Video | Enlace |
|-------|--------|
| Modelo 1 | https://youtu.be/doBTUNCEmxA |
| Modelo 2 | https://youtu.be/f7ot6vyB9jI |
| Modelo 3 | https://youtu.be/nof-7JfQlu4 |

> Todo el mes: viabilidad, objetivos y rentabilidad.

## Complicaciones

- 📐 Medidas recomendables variables — cama y ejes Z/X/Y por definir
- ⏳ Pasantías iniciadas → avance solo fines de semana

## Próximos pasos

- [ ] Definir modelo/estructura y evaluar madera
- [ ] Conseguir husillos (tornillo sin fin)

---

### 10-mes-junio.md — Día 30 — 30 de junio de 2026

> **Mes de junio:** modelado y bocetos — se eligió **Ender 3** (muy común) + base/cama en Z inspirada en modelo del profe García.

| Hito | Detalle | Estado |
|------|---------|--------|
| Modelo | Ender 3 + base Z García | ✅ Definido |
| Medidas | Bocetos acotados listos | ✅ |
| Plan | Cortes y uniones principales | ⏳ Próximos días |

> **Siguiente:** cortes, uniones y forma.

---

### 11-dia-1-7.md — Día 1 — 1 de julio de 2026

> **Avance:** uniones y cortes finales — estructura + cama **funcional y movible**.

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px; margin: 16px 0;">

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-01_00-00-00_03.jpg" alt="Estructura 01" width="100%" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
  <figcaption style="font-size: 11px; color: #666;">Cortes finales — vista 1</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-01_00-00-00_05.jpg" alt="Estructura 02" width="100%" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
  <figcaption style="font-size: 11px; color: #666;">Estructura + cama</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-01_00-00-00_07.jpg" alt="Estructura 03" width="100%" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
  <figcaption style="font-size: 11px; color: #666;">Cama movible</figcaption>
</figure>

</div>

## Próximos pasos

- [ ] Sábado: ensamblar todo para iniciar calibración

---

### 12-dia-4-a-10.md — Días 1–10 — julio 2026

## Sábado 4 — Ensamblaje base

> Nos reunimos con husillos y finales de carrera ya conseguidos. Logramos la forma base, con un primer intento del eje Y en video.

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px; margin: 12px 0;">

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-04_12-27-34.jpg" alt="Sábado 4 — 1" width="100%" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Sábado 4 — ensamblaje 1</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-04_12-30-24.jpg" alt="Sábado 4 — 2" width="100%" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Sábado 4 — ensamblaje 2</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-04_13-03-51.jpg" alt="Sábado 4 — 3" width="100%" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Sábado 4 — ensamblaje 3</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-04_13-03-58.jpg" alt="Sábado 4 — 4" width="100%" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Sábado 4 — ensamblaje 4</figcaption>
</figure>

</div>

## Jueves 9 — Corrección eje X

Tras corregir distancia X a la medida de nuestras varillas, el ensamblaje fue mucho más fácil y completamos el montaje.

### Corte base eje Y

<div style="text-align: center;">
  <img src="../fotos_avance/2026-07-05_00-00-00.jpg" alt="Corte base Y" width="520" style="border-radius: 8px;">
  <div style="font-size: 11px; color: #666;">Corte base eje Y — ajuste a varillas</div>
</div>

### Resultado final

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px;">

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-09_00-00-00_03.jpg" alt="Final 01" width="100%" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Resultado final — 1</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-09_00-00-00_01.jpg" alt="Final 02" width="100%" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Resultado final — 2</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-07-09_00-00-00_02.jpg" alt="Final 03" width="100%" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Resultado final — 3</figcaption>
</figure>

</div>

## Detalles

| Item | Detalle |
|------|---------|
| Madera | Pino |
| Piezas | Husillos, finales de carrera |
| Modelo | Por definir |

<div style="text-align: center;">
  <img src="../fotos_avance/2026-07-04_11-02-20.jpg" alt="Modelo" width="480" style="border-radius: 8px;">
  <div style="font-size: 11px; color: #666;">Modelo a simular</div>
</div>

## Complicaciones

- 📐 Eje X sobraba 5 cm vs varillas → retraso 1 semana
- 🌡️ Madera cama deformada por calor/campo abierto — verificar XYZ

## Próximos pasos

- [x] Cortar base eje Y para varillas (cumplido)
- [ ] Calibrar XYZ individual con finales de carrera

---

### 13-dia-16.md — Día 16 — 16 de agosto de 2026

> **Avances del mes:** reemplazo base/cama eje Y por deformación por calor + calibración XYZ.

## Reemplazo base/cama eje Y

La madera del eje Y se deformó por calor; se reemplazó por otro tipo y se consiguió vidrio para la cama. Pruebas de movimiento de todos los ejes en video.

## Calibración ejes XYZ

### Código base Marlin

> **Fuente:** https://marlinfw.org/meta/download/ — código Arduino Mega. Calibración: drivers, distancia mínima, modelo.

### Host Pronterface

> **Apoyo:** https://www.pronterface.com/ — calibración de todos los motores y primera impresión.


## Primera impresión — funcional

> Tras descartar filamento viejo/húmedo y reemplazar por nuevo, logramos la primera impresión.

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin: 16px 0;">

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-08-16_00-00-00.jpg" alt="Llavero" width="100%" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">1ª impresión — llavero</figcaption>
</figure>

<figure style="margin: 0; text-align: center;">
  <img src="../fotos_avance/2026-08-19_00-00-00.jpg" alt="Carta Braille" width="100%" style="border-radius: 8px;">
  <figcaption style="font-size: 11px; color: #666;">Carta póker Braille + letra A</figcaption>
</figure>

</div>

### Video

> https://youtu.be/7ipZVbgBHOc

## Complicaciones

- 🧵 Filamento viejo/húmedo
- 🤚 Quemadura leve dedos

## Próximos pasos

- [ ] Mejorar calibración y velocidad impresión

---

## Estructura

- `hardware/README.md` — índice Prusa i3, RAMPS 1.4, PLA
- `hardware/marlin_firmware/README.md` — Marlin + Pronterface (logos)
- `hardware/fotos_avance/` — 24 JPG (21 + 3 de 16/19 ago y 18 jul terminada)
- `hardware/bitacora/` — 13 días + este README-IM (reporte general)
