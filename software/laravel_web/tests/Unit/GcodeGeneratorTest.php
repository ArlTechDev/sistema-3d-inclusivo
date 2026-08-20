<?php

namespace Tests\Unit;

use App\Services\GcodeGenerator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GcodeGeneratorTest extends TestCase
{
    private GcodeGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new GcodeGenerator;
    }

    public function test_posicion_punto_mapea_correctamente_los_puntos_1_a_6(): void
    {
        // Posición de punto se prueba indirectamente a través del generador
        // Punto 1 -> Col 0, Fila 0
        $gcode1 = $this->generator->generar([[1]], 'a', 0.0, 0.0, 0.2);
        $this->assertStringContainsString('G0 X0.00 Y0.00', $gcode1);

        // Punto 4 -> Col 1, Fila 0 (X = 0 + 2.5 = 2.5)
        $gcode4 = $this->generator->generar([[4]], 'd', 0.0, 0.0, 0.2);
        $this->assertStringContainsString('G0 X2.50 Y0.00', $gcode4);

        // Punto 6 -> Col 1, Fila 2 (X = 2.5, Y = 2 * 2.5 = 5.0)
        $gcode6 = $this->generator->generar([[6]], 'z', 0.0, 0.0, 0.2);
        $this->assertStringContainsString('G0 X2.50 Y5.00', $gcode6);
    }

    public function test_punto_invalido_lanza_excepcion(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Punto Braille inválido: 7');

        $this->generator->generar([[7]], 'inválido');
    }

    public function test_punto_cero_lanza_excepcion(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Punto Braille inválido: 0');

        $this->generator->generar([[0]], 'inválido');
    }

    public function test_generar_gcode_contiene_encabezado_y_pie_correctos(): void
    {
        $gcode = $this->generator->generar([[1]], 'test');

        $this->assertStringContainsString('; G-Code generado por App\Services\GcodeGenerator', $gcode);
        $this->assertStringContainsString('; Texto: test', $gcode);
        $this->assertStringContainsString('G21 ; milímetros', $gcode);
        $this->assertStringContainsString('G90 ; posicionamiento absoluto', $gcode);
        $this->assertStringContainsString('G28 ; home de todos los ejes', $gcode);
        $this->assertStringContainsString('M104 S0 ; apagar extrusor', $gcode);
        $this->assertStringContainsString('M84 ; desactivar motores', $gcode);
    }

    public function test_salto_de_linea_automatico(): void
    {
        // 3 celdas con max_caracteres_linea = 1 -> la tercera celda salta a Y = 5.5 (avance_linea)
        $celdas = [[1], [1], [1]];
        $gcode = $this->generator->generar($celdas, 'aaa', 0.0, 0.0, 0.2, ['max_caracteres_linea' => 1]);

        $this->assertStringContainsString('G0 X0.00 Y0.00', $gcode);
        $this->assertStringContainsString('G0 X0.00 Y5.50', $gcode);
    }

    public function test_espacio_vacio_incrementa_posicion_x(): void
    {
        // Celda 1: [1], Celda 2: [] (espacio), Celda 3: [1]
        $celdas = [[1], [], [1]];
        $gcode = $this->generator->generar($celdas, 'a a');

        // Celda 1 en X=0.0
        $this->assertStringContainsString('G0 X0.00 Y0.00', $gcode);
        // Avance celda 1 = 3.5. Espacio palabra = 3.5. Celda 3 en X = 3.5 + 3.5 = 7.0
        $this->assertStringContainsString('G0 X7.00 Y0.00', $gcode);
    }
}
