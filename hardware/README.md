# Hardware — Sistema Braille Inclusivo

> **Bitácora original:** https://github.com/ArlTechDev/sistema-3d-inclusivo — `hardware/bitacora/` (recopilación histórica ene-jun 2026, previa al desarrollo web)

## Cinemática
- Prusa i3 cartesiana (correas GT2 X/Y, varillas roscadas Z)
- Controlador Arduino Mega + RAMPS 1.4, Marlin 1.1.x
- Extrusor MK8 directo, cama fría con adhesivo, PLA reciclado

## Estructura
- `cad_planos/` — FreeCAD (.FCStd) — chasis y piezas mecánicas
- `marlin_firmware/` — Configuration.h, pines, calibración
- `exportaciones_3d/` — STL exportados desde CAD
- `fotos_avance/` — fotos de progreso (21 JPG)
- `bitacora/` — 12 días de bitácora (27 ene → 12 jun 2026) + README-IM

## Bitácora 23ago — recopilación
Este commit recopila de golpe el trabajo hardware histórico (ene-jun 2026) documentado en `hardware/bitacora/01-dia-1.md` … `12-dia-4-a-10.md` y `hardware/fotos_avance/`. Trabajo previo al monorepo web (2026-05-20).

## Referencias
- `hardware/bitacora/README-IM.md` — índice rápido
- `docs/anexos/` — contexto técnico y fotos para documento PSCP
