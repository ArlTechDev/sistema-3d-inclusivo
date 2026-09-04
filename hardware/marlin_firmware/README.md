# Firmware & Host — Impresión 3D

<p align="center">
  <img src="https://raw.githubusercontent.com/MarlinFirmware/Marlin/bugfix-2.1.x/buildroot/share/pixmaps/logo/marlin-outrun-nf-500.png" alt="Marlin Logo" width="320">
</p>

> **Proyecto:** Sistema Braille Inclusivo — Prusa i3 casera (Arduino Mega + RAMPS 1.4)

---

## 1. Marlin Firmware — Base

> **Descarga oficial:** https://marlinfw.org/meta/download/ — Marlin 1.1.x

Gracias a la comunidad **Marlin Firmware** — sin su base open-source, esta impresora no existiría.

### Calibración realizada
- **Drivers:** DRV8825 — corriente y microstepping
- **Ejes:** pasos/mm (X/Y GT2, Z varillas roscadas), distancia mínima
- **Modelo:** Prusa i3, MK8 directo, cama fría, PLA reciclado
- **Archivos clave:** `Configuration.h`, `pins_RAMPS.h`

---

## 2. Pronterface — Host de Impresión

<p align="center">
  <img src="https://raw.githubusercontent.com/kliment/Printrun/master/printrun/assets/icons/pronterface/pronterface_256x256.png" alt="Pronterface Logo" width="180">
</p>

> **Oficial:** https://www.pronterface.com/ — **Mirror Windows:** https://pronterface.en.uptodown.com/windows

Host USB para enviar G-Code y controlar la impresora.

### Uso
- Conectar vía USB a Arduino Mega
- Cargar `.gcode` de `App\Services\BrailleTranslator`
- Control manual de ejes, temperatura y extrusión

### Flujo
1. Generar G-Code en `software/laravel_web` o `software/python_core`
2. Transferencia air-gapped vía SD/USB
3. Imprimir con Pronterface — ver `hardware/README.md`

---

> **Refs:** `hardware/README.md` · `hardware/bitacora/README-IM.md` · `hardware/bitacora/13-dia-16.md`
