<?php

namespace App\Services;

use InvalidArgumentException;

class GcodeGenerator
{
    public const AVANCE_CELDA_DEFECTO = 3.5;

    /**
     * Genera un programa G-Code (Marlin 1.1.x) que deposita celdas Braille en relieve.
     *
     * @param  array<int, array<int>>  $celdas  Lista de celdas Braille
     * @param  string  $texto  Texto original para comentarios
     * @param  float  $offsetX  Desplazamiento horizontal del origen del texto
     * @param  float  $offsetY  Desplazamiento vertical del origen del texto
     * @param  float  $z  Altura base (altura de la boquilla sobre la cama al iniciar cada punto)
     * @param  array<string, mixed>  $config  Parámetros de impresión
     */
    public function generar(array $celdas, string $texto, float $offsetX = 0.0, float $offsetY = 0.0, float $z = 0.2, array $config = []): string
    {
        $cfg = array_merge($this->configuracionPorDefecto(), $config);

        $lineas = [
            '; G-Code generado por App\\Services\\GcodeGenerator',
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
    public function configuracionPorDefecto(): array
    {
        return [
            'paso_puntos_x' => 2.5,      // dot pitch horizontal (ONCE)
            'paso_puntos_y' => 2.5,      // dot pitch vertical (ONCE)
            'avance_celda' => self::AVANCE_CELDA_DEFECTO, // avance horizontal por celda (mm - ONCE)
            'avance_linea' => 5.5,       // avance vertical por línea (mm - ONCE)
            'espaciado_palabra' => 3.5,  // espacio adicional entre palabras (mm - ONCE)
            'altura_punto' => 0.8,       // altura del relieve (nominal ONCE)
            'holgura_z' => 1.0,          // holgura de desplazamiento rápido en Z (mm)
            'volumen_punto' => 0.08,     // volumen de extrusión por punto (mm³) — TODO: calibrar empíricamente
            'velocidad_xy' => 1000.0,    // mm/min
            'velocidad_z' => 300.0,      // mm/min
            'velocidad_e' => 600.0,      // mm/min
            'temperatura' => 210.0,      // °C (PLA)
            'max_caracteres_linea' => 20, // 0 = sin ajuste de línea
        ];
    }
}
