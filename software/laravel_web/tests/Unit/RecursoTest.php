<?php

namespace Tests\Unit;

use App\Models\Recurso;
use PHPUnit\Framework\TestCase;

class RecursoTest extends TestCase
{
    public function test_max_caracteres_retorna_valor_si_no_es_nulo(): void
    {
        $recurso = new Recurso;
        $recurso->max_caracteres = 25;
        $recurso->placa_ancho = 100; // 100 / 3.5 = 28.57, pero debe retornar 25 ya que no es nulo

        $this->assertSame(25, $recurso->max_caracteres);
    }

    public function test_max_caracteres_calculado_dinamicamente_si_es_nulo(): void
    {
        $recurso = new Recurso;
        $recurso->max_caracteres = null;
        $recurso->placa_ancho = 70; // 70 / 3.5 = 20 celdas caben

        $this->assertSame(20, $recurso->max_caracteres);

        $recurso->placa_ancho = 50; // 50 / 3.5 = 14.28 -> 14 celdas caben
        $this->assertSame(14, $recurso->max_caracteres);
    }

    public function test_max_caracteres_retorna_null_si_ambos_valores_son_nulos(): void
    {
        $recurso = new Recurso;
        $recurso->max_caracteres = null;
        $recurso->placa_ancho = null;

        $this->assertNull($recurso->max_caracteres);
    }
}
