---
name: braille-gcode
description: Reglas del traductor texto→Braille Grado 1→G-Code (Código Braille Español, Prusa i3/Marlin, PHP puro) para implementar y validar App\Services\BrailleTranslator.
---

# Skill: braille-gcode — Traductor texto→Braille Grado 1→G-Code

## Propósito
Guiar la implementación (y el uso) del algoritmo de traducción texto → Braille Grado 1 → G-Code del proyecto Sistema Braille Inclusivo. Aplica a `app/Services/BrailleTranslator.php` (Laravel/PHP — decisión de arquitectura **PHP puro**; `software/python_core/` está ARCHIVADO).

## Reglas de dominio obligatorias

### Braille
- **Solo Grado 1** (literal, sin estenografía ni contracciones). Braille Grado 2 queda fuera de alcance.
- Tabla: **Código Braille Español (ONCE)** — incluye **ñ** (puntos 1-2-4-5-6), vocales acentuadas (á é í ó ú), ü, y signos ¿ ¡. No usar UEB (inglés) para caracteres españoles.
- Alfabeto: **27 letras** (a–z + ñ), no 26. Dígitos 0–9 usan letras a–j con signo numeral (⠼). Puntuación básica soportada.
- Celda Braille: 2 columnas × 3 filas, puntos numerados 1–6 (columna izquierda arriba→abajo: 1,2,3; derecha: 4,5,6).
- Dimensiones (BANA): punto ~1.5 mm de diámetro, altura de relieve 0.6 mm (rango 0.5–0.8 mm), dot pitch horizontal y vertical 2.34 mm.

### Hardware (Prusa i3 — para generar G-Code válido)
- Cinemática cartesiana: X/Y con correa GT2 (20 dientes → 80 pasos/mm), Z con husillo M8 1.25 mm + microstepping 1/16 → **2560 pasos/mm** (≈0.0004 mm/paso); extrusor E0 = 95 pasos/mm.
- Boquilla **0.8 mm**; cama fría con cinta azul/laca (sin cama caliente); PLA 170–220 °C.
- Firmware **Marlin 1.1.x** sobre Arduino Mega 2560 + RAMPS 1.4 + drivers A4988 (1/16).

### G-Code
- Instrucciones **G0/G1 con coordenadas absolutas**; control de extrusión **relativo** (`G92 E0` antes de cada extrusión).
- Salida air-gapped: el `.gcode` se guarda en el pedido (`gcode_path`) y se transfiere manualmente por SD/USB; **no** hay conexión servidor↔impresora.
- El Solicitante NUNCA descarga G-Code (solo el Administrador, UC-09).

## Implementación (App\Services\BrailleTranslator)
- `validarCaracteres(string $texto): array` — devuelve caracteres inválidos.
- `traducir(string $texto): array` — celdas con puntos 1–6 (y/o caracteres Braille Unicode ⠀-⠿).
- `generarGCode(string $texto, float $offsetX, float $offsetY, float $z, array $config): string` — con soporte de offset (Opción A de personalización: zona de texto del recurso `x_min/x_max/y_min/y_max/max_caracteres`).
- Tests: `tests/Unit/BrailleTranslatorTest.php` — cobertura 100% del alfabeto (27), dígitos, puntuación, inválidos, offset.

## Fuentes
`AGENTS.md` · `docs/anexos/09_informe_revision_documento_final.md` (hallazgo C4: tabla Braille con z/ñ) · `docs/anexos/11_revision_codigo_vs_documento.md` § 6 (decisión PHP puro, especificación).
