# Python Core — Algoritmo de Traducción Braille a G-Code

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
