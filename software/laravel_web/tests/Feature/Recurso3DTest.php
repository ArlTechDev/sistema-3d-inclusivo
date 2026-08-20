<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Recurso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Recurso3DTest extends TestCase
{
    use RefreshDatabase;

    protected function crearAdmin(): User
    {
        return User::create([
            'name' => 'Admin 3D',
            'email' => 'admin3d@test.com',
            'password' => 'password123',
            'rol' => User::ROL_ADMINISTRADOR,
        ]);
    }

    protected function crearCategoria(): Categoria
    {
        return Categoria::create(['nombre' => '3D Táctil', 'descripcion' => 'Recursos tridimensionales']);
    }

    public function test_admin_puede_crear_recurso_con_archivos_3d_stl_y_glb(): void
    {
        Storage::fake('public');

        $admin = $this->crearAdmin();
        $categoria = $this->crearCategoria();

        $archivoStl = UploadedFile::fake()->create('ficha.stl', 100, 'application/sla');
        $archivoGlb = UploadedFile::fake()->create('ficha.glb', 150, 'model/gltf-binary');

        $response = $this->actingAs($admin)->post(route('recursos.store'), [
            'titulo' => 'Ficha Táctil 3D',
            'descripcion' => 'Ficha tridimensional en Braille para aprendizaje',
            'gramos_pla' => 15.5,
            'tiempo_minutos' => 45,
            'fecha_creacion' => '2026-08-20',
            'estado' => Recurso::ESTADO_ACTIVO,
            'categoria_id' => $categoria->id,
            'archivo_stl' => $archivoStl,
            'archivo_glb' => $archivoGlb,
        ]);

        $response->assertRedirect(route('recursos.index'));
        $this->assertDatabaseHas('recursos', [
            'titulo' => 'Ficha Táctil 3D',
        ]);

        $recurso = Recurso::first();
        $this->assertNotNull($recurso->archivo_stl);
        $this->assertNotNull($recurso->archivo_glb);

        Storage::disk('public')->assertExists($recurso->archivo_stl);
        Storage::disk('public')->assertExists($recurso->archivo_glb);
    }
}
