<?php

namespace Tests\Feature\Frontend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagina_inicio_renderiza_correctamente_y_es_accesible(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('TÁCTIL');
        $response->assertSee('3D');
        $response->assertSee('PROYECTO SOCIOCOMUNITARIO PRODUCTIVO');
    }

    public function test_pagina_acerca_de_presenta_al_equipo_completo(): void
    {
        $response = $this->get(route('pages.about'));
        $response->assertStatus(200);
        $response->assertSee('Aramayo Eguino Jose Matias');
        $response->assertSee('Rosales Mamani Ariel Edson');
        $response->assertSee('Aguilar Castellon Cristhian Alessandro');
    }

    public function test_pagina_ayuda_incluye_seccion_faq(): void
    {
        $response = $this->get(route('pages.help'));
        $response->assertStatus(200);
        $response->assertSee('Preguntas Frecuentes');
    }
}
