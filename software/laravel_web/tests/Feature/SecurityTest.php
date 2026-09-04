<?php

namespace Tests\Feature;

use App\Models\Recurso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_blocks_brute_force_after_6_attempts(): void
    {
        User::factory()->create([
            'email' => 'victima@test.com',
            'password' => Hash::make('correcta'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'email' => 'victima@test.com',
                'password' => 'wrong'.$i,
            ]);
            $response->assertStatus(302);
        }

        $response = $this->post('/login', [
            'email' => 'victima@test.com',
            'password' => 'wrong6',
        ]);

        $response->assertStatus(429);
    }

    public function test_sql_injection_is_neutralized(): void
    {
        $payloads = [
            "' OR '1'='1",
            "admin' --",
            "'; DROP TABLE users; --",
            "' UNION SELECT * FROM users --",
        ];

        foreach ($payloads as $payload) {
            $response = $this->post('/login', [
                'email' => $payload,
                'password' => 'cualquiera',
            ]);

            $response->assertStatus(302);
        }

        $this->assertDatabaseCount('users', 0);
    }

    public function test_xss_payload_is_sanitized_on_input(): void
    {
        $admin = User::factory()->administrador()->create();
        $this->actingAs($admin);

        $xssTitulo = '<script>alert("XSS")</script>Material educativo';
        $xssDescripcion = 'Texto con <script>alert(1)</script> malicioso';

        $response = $this->post('/recursos', [
            'titulo' => $xssTitulo,
            'descripcion' => $xssDescripcion,
            'gramos_pla' => 10,
            'tiempo_minutos' => 30,
            'fecha_creacion' => '2026-07-01',
            'estado' => 'Activo',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('recursos.index'));

        $recurso = Recurso::first();

        $this->assertStringNotContainsString('<script>', $recurso->titulo);
        $this->assertStringNotContainsString('<script>', $recurso->descripcion);
        $this->assertStringContainsString('Material educativo', $recurso->titulo);
        $this->assertStringContainsString('Texto con', $recurso->descripcion);
    }

    public function test_unvalidated_redirect_is_blocked(): void
    {
        User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->withSession(['url.intended' => 'https://evil.com/phishing'])
            ->post('/login', [
                'email' => 'test@test.com',
                'password' => 'password',
            ]);

        $response->assertStatus(302);
        $location = $response->headers->get('Location') ?? '';
        $this->assertStringStartsWith(config('app.url'), $location);
    }

    public function test_dos_throttle_blocks_excessive_requests(): void
    {
        for ($i = 0; $i < 30; $i++) {
            $response = $this->get('/login');
            $response->assertStatus(200);
        }

        $response = $this->get('/login');
        $response->assertStatus(429);
    }

    public function test_exports_son_solo_para_administradores(): void
    {
        $solicitante = User::factory()->create(['rol' => 'Solicitante']);
        $this->actingAs($solicitante);

        // Los exports no deben estar disponibles para un rol distinto de Administrador.
        foreach (['/recursos/exportar/pdf', '/recursos/exportar/excel', '/instituciones/exportar/pdf', '/instituciones/exportar/excel', '/pedidos/exportar/pdf', '/pedidos/exportar/excel'] as $ruta) {
            $this->get($ruta)->assertForbidden();
        }

        $admin = User::factory()->administrador()->create();
        $this->actingAs($admin);

        $this->get('/recursos/exportar/excel')->assertOk();
        $this->get('/instituciones/exportar/excel')->assertOk();
    }

    public function test_paginas_de_gestion_son_solo_para_administradores(): void
    {
        $solicitante = User::factory()->create(['rol' => 'Solicitante']);
        $this->actingAs($solicitante);

        // Páginas de gestión (tablas/CRUD) prohibidas para el Solicitante.
        foreach (['/pedidos', '/usuarios', '/instituciones', '/recursos/papelera', '/instituciones/papelera', '/usuarios/papelera'] as $ruta) {
            $this->get($ruta)->assertForbidden();
        }

        // El catálogo sí es de su rol.
        $this->get('/recursos')->assertOk();
    }
}
