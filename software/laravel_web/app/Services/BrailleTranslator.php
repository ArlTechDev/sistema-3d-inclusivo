<?php

namespace App\Services;

use InvalidArgumentException;

/**
 * BrailleTranslator — Traductor texto → Braille Grado 1 → G-Code.
 *
 * Código Braille Español (ONCE), Braille Grado 1 literal (sin estenografía).
 * Genera G-Code compatible con Marlin 1.1.x sobre Prusa i3 (GT2 X/Y, husillo M8 Z,
 * extrusor MK8 directo, boquilla 0.8 mm, cama fría + PLA).
 *
 * Decisión de arquitectura: PHP puro (ver docs/anexos/11_revision_codigo_vs_documento.md § 6).
 * `software/python_core/` quedó archivado como respaldo.
 */
class BrailleTranslator
{
    /**
     * Mapa carácter → puntos (1-6) del Código Braille Español (ONCE).
     * Incluye las 27 letras (a-z + ñ), vocales acentuadas (á é í ó ú), ü,
     * dígitos (mapeados a letras a-j con signo numeral) y puntuación básica.
     */
    public const MAPA = [
        // Letras a-z (26)
        'a' => [1], 'b' => [1, 2], 'c' => [1, 4], 'd' => [1, 4, 5], 'e' => [1, 5],
        'f' => [1, 2, 4], 'g' => [1, 2, 4, 5], 'h' => [1, 2, 5], 'i' => [2, 4], 'j' => [2, 4, 5],
        'k' => [1, 3], 'l' => [1, 2, 3], 'm' => [1, 3, 4], 'n' => [1, 3, 4, 5],
        'o' => [1, 3, 5], 'p' => [1, 2, 3, 4], 'q' => [1, 2, 3, 4, 5], 'r' => [1, 2, 3, 5],
        's' => [2, 3, 4], 't' => [2, 3, 4, 5], 'u' => [1, 3, 6], 'v' => [1, 2, 3, 6],
        'w' => [2, 4, 5, 6], 'x' => [1, 3, 4, 6], 'y' => [1, 3, 4, 5, 6], 'z' => [1, 3, 5, 6],
        // ñ (letra 27 del alfabeto español)
        'ñ' => [1, 2, 4, 5, 6],
        // Vocales acentuadas y ü (Código Braille Español / ONCE)
        'á' => [1, 6], 'é' => [1, 2, 6], 'í' => [3, 4], 'ó' => [3, 4, 6], 'ú' => [1, 5, 6], 'ü' => [1, 2, 5, 6],
        // Dígitos 1-9 y 0 (sin el signo numeral; este se antepone en traducir())
        '1' => [1], '2' => [1, 2], '3' => [1, 4], '4' => [1, 4, 5], '5' => [1, 5],
        '6' => [1, 2, 4], '7' => [1, 2, 4, 5], '8' => [1, 2, 5], '9' => [2, 4], '0' => [2, 4, 5],
        // Puntuación básica (español/ONCE)
        '.' => [2, 5, 6], ',' => [2], ';' => [2, 3], ':' => [2, 5],
        '?' => [2, 3, 6], '¿' => [2, 3, 6], '!' => [2, 3, 5], '¡' => [2, 3, 5],
        '-' => [3, 6], "'" => [3], '"' => [2, 3, 6], '(' => [1, 2, 3, 5, 6], ')' => [2, 3, 4, 5, 6],
    ];

    /** Signo numeral (⠼) — precede a los dígitos en Braille Grado 1. */
    public const SIGNO_NUMERAL = [3, 4, 5, 6];

    /** Signo de mayúscula (⠠) — precede a una letra mayúscula en Grado 1. */
    public const SIGNO_MAYUSCULA = [6];

    /**
     * @var array<string, array<int>>
     */
    protected array $mapa;

    /**
     * @param  array<string, array<int>>  $mapa  Mapa personalizado (para pruebas o extensiones)
     */
    public function __construct(array $mapa = [])
    {
        $this->mapa = $mapa !== [] ? $mapa : static::MAPA;
    }

    /**
     * Devuelve los caracteres del texto que el traductor NO soporta.
     * Vacío si el texto completo es válido (incluye espacios y saltos de línea).
     *
     * @return array<int, string>
     */
    public function validarCaracteres(string $texto): array
    {
        $invalidos = [];

        foreach (mb_str_split($texto) as $caracter) {
            if (ctype_space($caracter)) {
                continue;
            }

            $clave = mb_strtolower($caracter);

            if (! isset($this->mapa[$clave])) {
                $invalidos[] = $caracter;
            }
        }

        return array_values(array_unique($invalidos));
    }

    /**
     * Traduce el texto a una lista de celdas Braille (cada celda es un array de puntos 1-6).
     * - Mayúsculas: se antepone el signo de mayúscula (punto 6) a la celda de la letra.
     * - Números: el signo numeral se emite una sola vez por grupo contiguo de dígitos.
     * - Espacios y saltos de línea se representan como celdas vacías ([]).
     *
     * @return array<int, array<int>>
     */
    public function traducir(string $texto): array
    {
        $celdas = [];
        $enNumero = false;

        foreach (mb_str_split($texto) as $caracter) {
            if (ctype_space($caracter)) {
                $celdas[] = [];
                $enNumero = false;

                continue;
            }

            $esDigito = ctype_digit($caracter);

            if ($esDigito && ! $enNumero) {
                $celdas[] = static::SIGNO_NUMERAL;
                $enNumero = true;
            } elseif (! $esDigito) {
                $enNumero = false;
            }

            $clave = mb_strtolower($caracter);

            if (! isset($this->mapa[$clave])) {
                throw new InvalidArgumentException("Carácter no soportado en Braille Grado 1: {$caracter}");
            }

            if ($clave !== $caracter) {
                $celdas[] = static::SIGNO_MAYUSCULA;
            }

            $celdas[] = $this->mapa[$clave];
        }

        return $celdas;
    }

    /**
     * Genera un programa G-Code (Marlin 1.1.x) que deposita el texto como puntos Braille en relieve.
     *
     * @param  string  $texto  Texto a imprimir
     * @param  float  $offsetX  Desplazamiento horizontal del origen del texto (Opción A de personalización)
     * @param  float  $offsetY  Desplazamiento vertical del origen del texto
     * @param  float  $z  Altura base (altura de la boquilla sobre la cama al iniciar cada punto)
     * @param  array<string, mixed>  $config  Parámetros de impresión (ver $configuracionPorDefecto)
     */
    public function generarGCode(string $texto, float $offsetX = 0.0, float $offsetY = 0.0, float $z = 0.2, array $config = []): string
    {
        if ($texto === '') {
            throw new InvalidArgumentException('El texto a traducir no puede estar vacío.');
        }

        $cfg = array_merge($this->configuracionPorDefecto(), $config);
        $celdas = $this->traducir($texto);

        $lineas = [
            '; G-Code generado por App\\Services\\BrailleTranslator',
            '; Texto: '.str_replace(["\r", "\n", "\t"], ' ', $texto),
            '; Braille Grado 1 (Código Braille Español - ONCE)',
            'G21 ; milímetros',
            'G90 ; posicionamiento absoluto',
            'G28 ; home de todos los ejes',
            'G92 E0 ; reset extrusor',
            'M104 S'.number_format($cfg['temperatura'], 1).' ; temperatura del extrusor (PLA)',
        ];

        $x = $offsetX;
        $y = $offsetY;
        $lineaActual = 0;

        foreach ($celdas as $indiceCelda => $celda) {
            if ($celda === []) {
                if (($indiceCelda + 1) < count($celdas)) {
                    $x += $cfg['espaciado_palabra'];
                }

                continue;
            }

            // Salto de línea automático (ajuste de texto)
            if ($cfg['max_caracteres_linea'] > 0
                && ($x - $offsetX) > ($cfg['max_caracteres_linea'] * $cfg['avance_celda'])) {
                $lineaActual++;
                $x = $offsetX;
                $y = $offsetY + ($lineaActual * $cfg['avance_linea']);
            }

            foreach ($celda as $punto) {
                [$columna, $fila] = $this->posicionPunto($punto);

                $px = $x + ($columna * $cfg['paso_puntos_x']);
                $py = $y + ($fila * $cfg['paso_puntos_y']);

                $lineas[] = sprintf('G0 Z%.2f ; elevar boquilla', $z + $cfg['altura_punto'] + $cfg['holgura_z']);
                $lineas[] = sprintf('G0 X%.2f Y%.2f F%.0f ; posicionar punto', $px, $py, $cfg['velocidad_xy']);
                $lineas[] = sprintf('G1 Z%.2f F%.0f ; descender a la base del punto', $z, $cfg['velocidad_z']);
                $lineas[] = 'G92 E0';
                $lineas[] = sprintf('G1 E%.3f F%.0f ; extruir volumen del punto', $cfg['volumen_punto'], $cfg['velocidad_e']);
                $lineas[] = sprintf('G1 Z%.2f F%.0f ; subir mientras se forma el relieve', $z + $cfg['altura_punto'], $cfg['velocidad_z']);
                $lineas[] = 'G92 E0';
            }

            $x += $cfg['avance_celda'];
        }

        $lineas[] = 'G0 Z'.number_format($z + 10, 2).' ; retraer boquilla al final';
        $lineas[] = 'M104 S0 ; apagar extrusor';
        $lineas[] = 'M84 ; desactivar motores';
        $lineas[] = '; Fin del programa';

        return implode("\n", $lineas)."\n";
    }

    /**
     * Posición (columna, fila) de un punto dentro de la celda Braille.
     * Puntos 1-3 → columna 0; puntos 4-6 → columna 1. Filas 0,1,2 por cada par.
     *
     * @return array{0: int, 1: int}
     */
    protected function posicionPunto(int $punto): array
    {
        if ($punto < 1 || $punto > 6) {
            throw new InvalidArgumentException("Punto Braille inválido: {$punto}");
        }

        $columna = $punto > 3 ? 1 : 0;
        $fila = (($punto - 1) % 3);

        return [$columna, $fila];
    }

    /**
     * @return array<string, mixed>
     */
    protected function configuracionPorDefecto(): array
    {
        return [
            'paso_puntos_x' => 2.34,     // dot pitch horizontal (BANA)
            'paso_puntos_y' => 2.34,     // dot pitch vertical (BANA)
            'avance_celda' => 3.6,       // avance horizontal por celda (mm)
            'avance_linea' => 6.2,       // avance vertical por línea (mm)
            'espaciado_palabra' => 3.6,  // espacio adicional entre palabras (mm)
            'altura_punto' => 0.6,       // altura del relieve (rango 0.5-0.8 mm)
            'holgura_z' => 1.0,          // holgura de desplazamiento rápido en Z (mm)
            'volumen_punto' => 0.05,     // volumen de extrusión por punto (mm³) — calibrar
            'velocidad_xy' => 1000.0,    // mm/min
            'velocidad_z' => 300.0,      // mm/min
            'velocidad_e' => 600.0,      // mm/min
            'temperatura' => 210.0,      // °C (PLA)
            'max_caracteres_linea' => 20, // 0 = sin ajuste de línea
        ];
    }
}
