# Python Core — Algoritmo de Traducción Braille a G-Code

> ## ⚠️ ARCHIVADO (decisión de arquitectura: PHP puro — 2026-08)
> Este módulo quedó **deprecado**. El algoritmo de traducción texto → Braille Grado 1 → G-Code vive ahora en
> `software/laravel_web/app/Services/BrailleTranslator.php` (Service class de Laravel).
> Referencia: `docs/anexos/11_revision_codigo_vs_documento.md` § 6.
> Se conserva como respaldo por si PHP no resulta. **No agregar funcionalidad nueva aquí.**

## Descripcion
Este modulo contiene el algoritmo que traduce texto a Braille Grado 1 y genera coordenadas G-Code para la CNC.

## Estado
En desarrollo — placeholder inicial

## Estructura Planeada
```
python_core/
├── main.py              # Entry point
├── braille_translator.py # Traductor texto → Braille
├── gcode_generator.py    # Generador Braille → G-Code
├── config.py            # Configuracion de parametros CNC
├── requirements.txt     # Dependencias
└── tests/               # Tests unitarios (pytest)
```

## Instalacion
```bash
python -m venv venv
source venv/bin/activate
pip install -r requirements.txt
```

## Uso (placeholder)
```bash
python main.py --texto "Hola Mundo" --output salida.gcode
```
