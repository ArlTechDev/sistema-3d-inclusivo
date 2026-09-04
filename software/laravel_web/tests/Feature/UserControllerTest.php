<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function crearAdmin(): User
    {
        return User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => 'password123',
            'rol' => 'Administrador',
        ]);
    }

    public function test_admin_puede_crear_usuario_y_la_contrasena_permite_autenticarse(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->post(route('usuarios.store'), [
            'name' => 'Nuevo Solicitante',
            'email' => 'nuevo@test.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'rol' => 'Solicitante',
        ]);

        $response->assertRedirect(route('usuarios.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'nuevo@test.com',
            'rol' => 'Solicitante',
        ]);

        // Verificar que el usuario recién creado puede hacer login (sin sufrir el error de doble hash)
        Auth::logout();
        $loginResponse = $this->post(route('login.post'), [
            'email' => 'nuevo@test.com',
            'password' => 'secret123',
        ]);

        $loginResponse->assertRedirect();
        $this->assertAuthenticatedAs(User::where('email', 'nuevo@test.com')->first());
    }

    public function test_admin_puede_actualizar_usuario_sin_cambiar_contrasena(): void
    {
        $admin = $this->crearAdmin();
        $usuario = User::create([
            'name' => 'Usuario Original',
            'email' => 'original@test.com',
            'password' => 'clave123',
            'rol' => 'Solicitante',
        ]);

        $response = $this->actingAs($admin)->put(route('usuarios.update', $usuario), [
            'name' => 'Usuario Editado',
            'email' => 'original@test.com',
            'password' => '',
            'rol' => 'Solicitante',
        ]);

        $response->assertRedirect(route('usuarios.index'));
        $this->assertDatabaseHas('users', [
            'id' => $usuario->id,
            'name' => 'Usuario Editado',
        ]);

        // Autenticar con la contraseña original
        Auth::logout();
        $loginResponse = $this->post(route('login.post'), [
            'email' => 'original@test.com',
            'password' => 'clave123',
        ]);

        $loginResponse->assertRedirect();
        $this->assertAuthenticated();
    }

    public function test_admin_no_puede_enviar_su_propia_cuenta_a_papelera(): void
    {
        $admin = $this->crearAdmin();

        $response = $this->actingAs($admin)->delete(route('usuarios.destroy', $admin));

        $response->assertRedirect(route('usuarios.index'));
        $response->assertSessionHasErrors('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
