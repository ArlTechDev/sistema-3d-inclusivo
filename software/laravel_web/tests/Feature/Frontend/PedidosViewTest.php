<?php

namespace Tests\Feature\Frontend;

use App\Models\Categoria;
use App\Models\Recurso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidosViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_formulario_de_pedido_contiene_seccion_de_previsualizacion(): void
    {
        $user = User::create([
            'name' => 'Solicitante Test',
            'email' => 'solicitante.test@ejemplo.com',
            'password' => bcrypt('12345678'),
            'rol' => 'Solicitante',
        ]);

        $categoria = Categoria::create(['nombre' => 'Ciencias', 'descripcion' => 'Anatomía']);
        Recurso::create([
            'titulo' => 'Célula Táctil',
            'categoria_id' => $categoria->id,
            'descripcion' => 'Estructura celular con relieve Braille.',
            'estado' => 'Activo',
            'gramos_pla' => 25,
            'tiempo_minutos' => 90,
        ]);

        $response = $this->actingAs($user)->get(route('pedidos.create'));
        $response->assertStatus(200);
        $response->assertSee('Solicitar Impresión de Recurso');
        $response->assertSee('Texto Personalizado Braille');
    }
}
