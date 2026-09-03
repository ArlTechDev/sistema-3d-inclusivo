<?php

namespace Tests\Unit;

use App\Services\BrailleTranslator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BrailleTranslatorTest extends TestCase
{
    private BrailleTranslator $traductor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->traductor = new BrailleTranslator;
    }

    public function test_alfabeto_espanol_contiene_27_letras(): void
    {
        $alfabetoEspanol = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'ñ', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

        foreach ($alfabetoEspanol as $letra) {
            $this->assertArrayHasKey($letra, BrailleTranslator::MAPA, "Falta la letra: {$letra}");
        }
    }

    public function test_celdas_braille_conocidas(): void
    {
        $this->assertSame([1], $this->traductor->traducir('a')[0]);
        $this->assertSame([1, 2], $this->traductor->traducir('b')[0]);
        $this->assertSame([1, 2, 4, 5, 6], $this->traductor->traducir('ñ')[0]);
        $this->assertSame([1, 3, 5, 6], $this->traductor->traducir('z')[0]);
        $this->assertSame([1, 6], $this->traductor->traducir('á')[0]);
    }

    public function test_digitos_usan_signo_numeral(): void
    {
        $celdas = $this->traductor->traducir('12');

        $this->assertSame(BrailleTranslator::SIGNO_NUMERAL, $celdas[0]);
        $this->assertSame([1], $celdas[1]);      // 1 = a
        $this->assertSame([1, 2], $celdas[2]);   // 2 = b
    }

    public function test_signo_numeral_no_se_repite_dentro_del_grupo(): void
    {
        $celdas = $this->traductor->traducir('123');

        $this->assertSame(BrailleTranslator::SIGNO_NUMERAL, $celdas[0]);
        $this->assertCount(4, $celdas); // numeral + 3 dígitos
    }

    public function test_signo_numeral_se_reemite_despues_de_letra(): void
    {
        $celdas = $this->traductor->traducir('1a2');

        $this->assertSame(BrailleTranslator::SIGNO_NUMERAL, $celdas[0]);
        $this->assertSame([1], $celdas[1]);       // 1
        $this->assertSame([1], $celdas[2]);       // a
        $this->assertSame(BrailleTranslator::SIGNO_NUMERAL, $celdas[3]); // nuevo numeral
        $this->assertSame([1, 2], $celdas[4]);    // 2
    }

    public function test_mayusculas_usan_signo_de_mayuscula(): void
    {
        $celdas = $this->traductor->traducir('A');

        $this->assertSame(BrailleTranslator::SIGNO_MAYUSCULA, $celdas[0]);
        $this->assertSame([1], $celdas[1]);
    }

    public function test_palabra_ñandu_mayuscula(): void
    {
        $celdas = $this->traductor->traducir('ÑANDÚ');

        $this->assertCount(10, $celdas); // 5 letras × (signo mayúscula + celda)
        $this->assertSame(BrailleTranslator::SIGNO_MAYUSCULA, $celdas[0]);
        $this->assertSame([1, 2, 4, 5, 6], $celdas[1]); // Ñ
    }

    public function test_espacios_se_representan_como_celda_vacia(): void
    {
        $celdas = $this->traductor->traducir('a b');

        $this->assertSame([1], $celdas[0]);
        $this->assertSame([], $celdas[1]);
        $this->assertSame([1, 2], $celdas[2]);
    }

    public function test_puntuacion_soportada(): void
    {
        foreach (['.', ',', ';', ':', '?', '¿', '!', '¡', '-', "'", '"', '(', ')'] as $signo) {
            $this->assertNotEmpty($this->traductor->traducir($signo)[0], "Falta la puntuación: {$signo}");
        }
    }

    public function test_validar_caracteres_devuelve_invalidos(): void
    {
        $this->assertSame(['@'], $this->traductor->validarCaracteres('ñandú@'));
        $this->assertSame(['%', '&'], $this->traductor->validarCaracteres('%hola&'));
    }

    public function test_validar_caracteres_texto_valido(): void
    {
        $this->assertSame([], $this->traductor->validarCaracteres('Hola mundo 123, ¿qué tal?'));
    }

    public function test_gcode_estructura_basica(): void
    {
        $gcode = $this->traductor->generarGCode('a');

        $this->assertStringContainsString('G21', $gcode);
        $this->assertStringContainsString('G90', $gcode);
        $this->assertStringContainsString('G28', $gcode);
        $this->assertStringContainsString('G92 E0', $gcode);
        $this->assertStringContainsString('M104 S210.0', $gcode);
        $this->assertStringContainsString('G0 X', $gcode);
        $this->assertStringContainsString('G1 E', $gcode);
    }

    public function test_gcode_coordenadas_del_punto(): void
    {
        // 'b' = puntos 1 (fila 0) y 2 (fila 1) en la columna 0
        $gcode = $this->traductor->generarGCode('b');

        $this->assertStringContainsString('G0 X0.00 Y0.00', $gcode);
        $this->assertStringContainsString('G0 X0.00 Y2.50', $gcode);
    }

    public function test_gcode_aplica_offset(): void
    {
        $gcode = $this->traductor->generarGCode('a', 10.0, 20.0);

        // Con offset, la primera celda cae en X=10, Y=20
        $this->assertStringContainsString('G0 X10.00 Y20.00', $gcode);
        // Ninguna coordenada X puede ser menor al offset
        preg_match_all('/G0 X([\d.]+) Y([\d.]+)/', $gcode, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $this->assertGreaterThanOrEqual(10.0, (float) $m[1]);
            $this->assertGreaterThanOrEqual(20.0, (float) $m[2]);
        }
    }

    public function test_gcode_salto_de_linea_automatico(): void
    {
        $gcode = $this->traductor->generarGCode('abc', 0.0, 0.0, 0.2, ['max_caracteres_linea' => 1]);

        // 'c' debe caer en la segunda línea (Y = avance_linea = 5.5)
        $this->assertStringContainsString('G0 X0.00 Y5.50', $gcode);
    }

    public function test_gcode_texto_vacio_lanza_excepcion(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->traductor->generarGCode('');
    }

    public function test_traducir_en_lineas_word_wrap_respeta_palabras(): void
    {
        // "Hola Mundo" -> "Hola" (5 celdas: Mayus + H + o + l + a), "Mundo" (6 celdas: Mayus + M + u + n + d + o)
        // Con max=6, "Hola" entra en línea 1 (5 celdas). "Mundo" no cabe (5+1+6=12 > 6), pasa a línea 2
        $lineas = $this->traductor->traducirEnLineas('Hola Mundo', 6);

        $this->assertCount(2, $lineas);
        $this->assertCount(5, $lineas[0]); // Signo mayus + H + o + l + a
        $this->assertCount(6, $lineas[1]); // Signo mayus + M + u + n + d + o
    }

    public function test_traducir_en_lineas_palabra_larga_se_particiona_sin_desbordar(): void
    {
        // "ABCDEF" (6 celdas sin mayúsculas si es minúscula) -> 'abcdef' con max=3 celdas por línea
        $lineas = $this->traductor->traducirEnLineas('abcdef', 3);

        $this->assertCount(2, $lineas);
        $this->assertCount(3, $lineas[0]);
        $this->assertCount(3, $lineas[1]);
    }

    public function test_gcode_parametros_personalizables(): void
    {
        $gcode = $this->traductor->generarGCode('a', 0.0, 0.0, 0.2, ['temperatura' => 200.0]);

        $this->assertStringContainsString('M104 S200.0', $gcode);
    }
}
