<?php

namespace Tests\Feature\Frontend;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuariosViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_formulario_crear_usuario_contiene_roles_permitidos(): void
    {
        $admin = User::create([
            'name' => 'Admin Sistema',
            'email' => 'admin.sistema@ejemplo.com',
            'password' => bcrypt('password123'),
            'rol' => 'Administrador',
        ]);

        $response = $this->actingAs($admin)->get(route('usuarios.create'));
        $response->assertStatus(200);
        $response->assertSee('Crear Usuario');
        $response->assertSee('Administrador');
        $response->assertSee('Solicitante');
    }
}
