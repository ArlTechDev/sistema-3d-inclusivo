<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_formulario_de_registro(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Registro de Nuevo Solicitante');
        $response->assertSee('Registrarse');
    }

    public function test_solicitante_puede_registrarse_exitosamente_y_se_asigna_rol_solicitante(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Profesor Juan Perez',
            'email' => 'juan.perez@escuela.bo',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertRedirect(route('recursos.index'));
        $this->assertAuthenticated();

        $user = User::where('email', 'juan.perez@escuela.bo')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Profesor Juan Perez', $user->name);
        $this->assertEquals(User::ROL_SOLICITANTE, $user->rol);
    }

    public function test_intento_de_escalacion_de_privilegios_rol_admin_es_ignorado(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Atacante Hacker',
            'email' => 'hacker@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'rol' => User::ROL_ADMINISTRADOR, // Intento malicioso de forzar rol Administrador
        ]);

        $response->assertRedirect(route('recursos.index'));

        $user = User::where('email', 'hacker@test.com')->first();
        $this->assertNotNull($user);
        // Debe ser SIEMPRE Solicitante
        $this->assertEquals(User::ROL_SOLICITANTE, $user->rol);
    }

    public function test_registro_falla_con_email_duplicado(): void
    {
        User::create([
            'name' => 'Usuario Existente',
            'email' => 'existente@test.com',
            'password' => 'password123',
            'rol' => User::ROL_SOLICITANTE,
        ]);

        $response = $this->post(route('register.post'), [
            'name' => 'Otro Usuario',
            'email' => 'existente@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registro_falla_con_contrasena_corta_o_sin_confirmar(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Usuario Test',
            'email' => 'test@test.com',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_sanitiza_entradas_para_prevenir_xss(): void
    {
        $this->post(route('register.post'), [
            'name' => '<script>alert("XSS")</script>Profesor Seguro',
            'email' => 'profesor.seguro@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'profesor.seguro@test.com')->first();
        $this->assertNotNull($user);
        $this->assertStringNotContainsString('<script>', $user->name);
    }
}
