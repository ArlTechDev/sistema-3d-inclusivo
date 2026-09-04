<?php

namespace Tests\Feature\Frontend;

use App\Models\Categoria;
use App\Models\Recurso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogoAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogo_muestra_filtros_con_semantica_accesible(): void
    {
        $user = User::create([
            'name' => 'Docente Test',
            'email' => 'docente.test@ejemplo.com',
            'password' => bcrypt('12345678'),
            'rol' => 'Solicitante',
        ]);

        $categoria = Categoria::create([
            'nombre' => 'Matemáticas Táctiles',
            'descripcion' => 'Material geométrico en relieve',
        ]);

        Recurso::create([
            'titulo' => 'Cubo Didáctico Braille',
            'categoria_id' => $categoria->id,
            'descripcion' => 'Cubo con numeración en relieve para cálculo básico.',
            'estado' => 'Activo',
            'gramos_pla' => 35,
            'tiempo_minutos' => 120,
        ]);

        $response = $this->actingAs($user)->get(route('recursos.index'));
        $response->assertStatus(200);
        $response->assertSee('Filtrar por categoría');
        $response->assertSee('Cubo Didáctico Braille');
    }
}
