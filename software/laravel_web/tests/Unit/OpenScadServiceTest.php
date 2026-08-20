<?php

namespace Tests\Unit;

use App\Services\OpenScadService;
use Tests\TestCase;

class OpenScadServiceTest extends TestCase
{
    public function test_esta_disponible_retorna_bool(): void
    {
        $service = new OpenScadService;
        $this->assertIsBool($service->estaDisponible());
    }

    public function test_binario_inexistente_retorna_false(): void
    {
        $service = new OpenScadService('/ruta/inexistente/openscad');
        $this->assertFalse($service->estaDisponible());
        $this->assertFalse($service->compilarSCADaSTL('cube([10,10,10]);', '/tmp/test.stl'));
    }
}
