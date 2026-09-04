<?php

namespace Tests\Feature\Frontend;

use App\Models\Institucion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstitucionesViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrador_ve_tabla_de_instituciones_con_etiquetas_accesibles(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin.test@ejemplo.com',
            'password' => bcrypt('password123'),
            'rol' => 'Administrador',
        ]);

        Institucion::create([
            'nombre' => 'Centro de Educación Especial Aprecia',
            'direccion' => 'Av. Heroínas 456',
            'telefono' => '4256789',
            'director' => 'Lic. Roberto Gómez',
        ]);

        $response = $this->actingAs($admin)->get(route('instituciones.index'));
        $response->assertStatus(200);
        $response->assertSee('Centro de Educación Especial Aprecia');
        $response->assertSee('Gestión de Instituciones');
    }
}
