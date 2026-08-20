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

    private GcodeGenerator $gcodeGenerator;

    /**
     * @param  array<string, array<int>>  $mapa  Mapa personalizado (para pruebas o extensiones)
     */
    public function __construct(array $mapa = [], ?GcodeGenerator $gcodeGenerator = null)
    {
        $this->mapa = $mapa !== [] ? $mapa : static::MAPA;
        $this->gcodeGenerator = $gcodeGenerator ?? new GcodeGenerator;
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
     * @param  float  $offsetX  Desplazamiento horizontal del origen del texto
     * @param  float  $offsetY  Desplazamiento vertical del origen del texto
     * @param  float  $z  Altura base (altura de la boquilla sobre la cama al iniciar cada punto)
     * @param  array<string, mixed>  $config  Parámetros de impresión
     */
    public function generarGCode(string $texto, float $offsetX = 0.0, float $offsetY = 0.0, float $z = 0.2, array $config = []): string
    {
        if ($texto === '') {
            throw new InvalidArgumentException('El texto a traducir no puede estar vacío.');
        }

        $celdas = $this->traducir($texto);

        return $this->gcodeGenerator->generar($celdas, $texto, $offsetX, $offsetY, $z, $config);
    }
}
