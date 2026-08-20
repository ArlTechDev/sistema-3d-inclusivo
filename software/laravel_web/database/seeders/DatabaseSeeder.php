<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\ConfiguracionSistema;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Usuarios
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'rol' => 'Administrador',
            ]
        );

        User::updateOrCreate(
            ['email' => 'docente@test.com'],
            [
                'name' => 'Solicitante',
                'password' => Hash::make('12345678'),
                'rol' => 'Solicitante',
            ]
        );

        // Categorías del catálogo de recursos
        $categorias = [
            ['nombre' => 'Matemáticas', 'descripcion' => 'Figuras geométricas, reglas de medición, operaciones matemáticas'],
            ['nombre' => 'Geografía', 'descripcion' => 'Mapas topográficos, continentes, relieve'],
            ['nombre' => 'Braille', 'descripcion' => 'Fichas de vocabulario, alfabeto, números en Braille'],
            ['nombre' => 'Ciencias', 'descripcion' => 'Diagramas científicos, anatomía, naturaleza'],
        ];
        foreach ($categorias as $cat) {
            Categoria::updateOrCreate(['nombre' => $cat['nombre']], $cat);
        }

        // Configuración del sistema
        ConfiguracionSistema::updateOrCreate(
            ['clave' => 'precio_gramo_pla'],
            [
                'valor' => '0.05',
                'descripcion' => 'Costo por gramo de PLA en dólares (USD)',
            ]
        );

        // Recursos didácticos de muestra
        $catBraille = Categoria::where('nombre', 'Braille')->first();
        if ($catBraille) {
            \App\Models\Recurso::updateOrCreate(
                ['titulo' => 'Ficha Didáctica Táctil 3D (Demostración)'],
                [
                    'descripcion' => 'Cuerpo didáctico tridimensional con relieves Braille táctiles para estimulación háptica.',
                    'categoria_id' => $catBraille->id,
                    'gramos_pla' => 12.50,
                    'tiempo_minutos' => 35,
                    'fecha_creacion' => now()->toDateString(),
                    'estado' => \App\Models\Recurso::ESTADO_ACTIVO,
                    'archivo_glb' => 'recursos/3d/ficha_tactil_demo.glb',
                ]
            );
        }
    }
}
