<?php

namespace Tests\Feature;

use App\Models\ConfiguracionSistema;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfiguracionTest extends TestCase
{
    use RefreshDatabase;

    protected function crearAdmin(): User
    {
        return User::create([
            'name' => 'Admin Prueba',
            'email' => 'admin@test.com',
            'password' => 'password123',
            'rol' => User::ROL_ADMINISTRADOR,
        ]);
    }

    protected function crearSolicitante(): User
    {
        return User::create([
            'name' => 'Docente Prueba',
            'email' => 'docente@test.com',
            'password' => 'password123',
            'rol' => User::ROL_SOLICITANTE,
        ]);
    }

    public function test_admin_puede_ver_vista_de_configuracion(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->get(route('configuracion.index'));

        $response->assertOk();
        $response->assertSee('Configuración de Costos y Parámetros');
    }

    public function test_solicitante_no_puede_acceder_a_configuracion(): void
    {
        $solicitante = $this->crearSolicitante();

        $response = $this->actingAs($solicitante)->get(route('configuracion.index'));

        $response->assertForbidden();
    }

    public function test_admin_puede_actualizar_parametros_de_costo_y_moneda(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->post(route('configuracion.update'), [
            'precio_gramo_pla' => 0.18,
            'moneda_simbolo' => 'Bs',
            'gramos_por_celda_braille' => 0.025,
        ]);

        $response->assertRedirect(route('configuracion.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('0.18', ConfiguracionSistema::where('clave', 'precio_gramo_pla')->value('valor'));
        $this->assertEquals('Bs', ConfiguracionSistema::where('clave', 'moneda_simbolo')->value('valor'));
        $this->assertEquals('0.025', ConfiguracionSistema::where('clave', 'gramos_por_celda_braille')->value('valor'));
    }

    public function test_validacion_de_parametros_de_configuracion(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->post(route('configuracion.update'), [
            'precio_gramo_pla' => 0, // Invalido (debe ser > 0)
            'moneda_simbolo' => '',
            'gramos_por_celda_braille' => -1,
        ]);

        $response->assertSessionHasErrors(['precio_gramo_pla', 'moneda_simbolo', 'gramos_por_celda_braille']);
    }
}
