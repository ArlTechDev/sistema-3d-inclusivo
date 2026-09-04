# Pronterface — Referencia Impresión

> **Sitio oficial:** https://www.pronterface.com/

Herramienta que usamos para imprimir — host USB para enviar G-Code a la impresora.

## Uso
- Conectar vía USB a Arduino Mega + RAMPS 1.4
- Cargar `.gcode` generado por `App\Services\BrailleTranslator` (o Python Core)
- Control manual de ejes, temperatura, extrusión y monitoreo

## Flujo
1. Generar G-Code en `software/laravel_web` / `software/python_core`
2. Transferencia air-gapped vía SD/USB (sin red directa a CNC)
3. Impresión con Pronterface — ver `hardware/README.md`

> Ver también `hardware/marlin_firmware/marlin.md` y `hardware/bitacora/README-IM.md`.
