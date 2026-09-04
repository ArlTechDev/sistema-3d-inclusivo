# Bitácora — Impresora 3D Casera

> **Repositorio original:** https://github.com/ag-cris21/Impresora-3D-casera  
> **Playlist YouTube:** https://www.youtube.com/playlist?list=PLODq3_P6bfsg  
> **Índice hardware:** `hardware/README.md`

Guardar los datos de la impresora en base al repositorio original.

## Estado actual (2026-09-04)

Hardware migrado a **single-repo** (`feat/clear-hardware`): 23ago recopilación histórica + 04sep formalización, Marlin/Pronterface y día 13 con primera impresión funcional.

## Videos de referencia

| # | Video | Estado | Fecha | Link |
|---|-------|--------|-------|------|
| 1 | Calibración inicial | En subida | 2026-08-23 | Playlist |
| 2 | Prueba motores/DRV8825 | Organizado | 2026-09-04 | Playlist |
| 3 | Reemplazo cama Y + calibración XYZ | Listo | 2026-08-16 | `13-dia-16.md` |
| 4 | Primera impresión funcional | Listo | 2026-08-16 | `fotos_avance/2026-08-16_00-00-00.jpg` |

> *Nota: 2 videos en “público” pasarán a “no listado” al completar playlist. Ver `hardware/marlin_firmware/README.md` para host de impresión.*

## Índice bitácora

| Día | Archivo | Hito |
|-----|---------|------|
| 1 | `01-dia-1.md` | 27 ene 2026 — Arduino Mega + RAMPS, motor prueba |
| 2 | `02-dia-2.md` | 28 ene — drivers DRV8825 |
| 3-5 | `03-dia-3.a-5.md` | 29 ene-02 feb — estructura |
| 6 | `04-dia-6.md` | 03 feb — ensamblaje inicial |
| 7-13 | `05-dia-7-a-13.md` | 04-10 feb — avances |
| 14 | `06-dia-14.md` | 11 feb |
| 13 | `07-dia-13.md` | 12 feb |
| — | `08-avances-abril.md` | abril — Gantt |
| 12 | `09-dia-12.md` | 12 may |
| — | `10-mes-junio.md` | junio |
| 1-7 | `11-dia-1-7.md` | julio — calibraciones |
| 4-10 | `12-dia-4-a-10.md` | julio — pruebas ejes |
| 16 | `13-dia-16.md` | **16 ago 2026 — reemplazo cama Y, calibración XYZ, primera impresión (Marlin + Pronterface)** |

## Firmware & Host

- **Marlin 1.1.x:** https://marlinfw.org/meta/download/ — DRV8825, pasos/mm, `Configuration.h` — ver `hardware/marlin_firmware/README.md`
- **Pronterface:** https://www.pronterface.com/ / https://pronterface.en.uptodown.com/windows — host G-Code

## Estructura

- `hardware/README.md` — índice Prusa i3, RAMPS 1.4, PLA
- `hardware/marlin_firmware/README.md` — Marlin + Pronterface (logos)
- `hardware/fotos_avance/` — 23 JPG (21 + 2 de 16/19 ago)
- `hardware/bitacora/` — 13 días + este README-IM

## Próximos pasos

- Completar subida YouTube y pasar a “no listado”
- Calibración fina y segunda impresión de prueba
