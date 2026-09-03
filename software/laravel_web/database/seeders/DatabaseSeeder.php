<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\ConfiguracionSistema;
use App\Models\Recurso;
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
                'valor' => '0.15',
                'descripcion' => 'Costo por gramo de PLA reciclado en Bolivianos (Bs)',
            ]
        );
        ConfiguracionSistema::updateOrCreate(
            ['clave' => 'moneda_simbolo'],
            [
                'valor' => 'Bs',
                'descripcion' => 'Símbolo de moneda nacional de Bolivia',
            ]
        );
        ConfiguracionSistema::updateOrCreate(
            ['clave' => 'gramos_por_celda_braille'],
            [
                'valor' => '0.02',
                'descripcion' => 'Gramos de filamento PLA adicionales por cada celda Braille en relieve',
            ]
        );

        // Recursos didácticos de muestra
        $catBraille = Categoria::where('nombre', 'Braille')->first();
        if ($catBraille) {
            Recurso::updateOrCreate(
                ['titulo' => 'Ficha Base Estándar (80x30 mm)'],
                [
                    'descripcion' => 'Placa base sólida en blanco (80x30 mm, 3 mm de espesor) para personalización e impresión de texto Braille táctil.',
                    'categoria_id' => $catBraille->id,
                    'gramos_pla' => 8.29,
                    'tiempo_minutos' => 11,
                    'fecha_creacion' => now()->toDateString(),
                    'estado' => Recurso::ESTADO_ACTIVO,
                    'tipo_placa' => 'integrada',
                    'placa_ancho' => 80,
                    'placa_alto' => 30,
                    'placa_z_altura' => 3,
                    'max_caracteres' => 22,
                    'archivo_stl' => 'recursos/3d/ficha_base_blanco.stl',
                    'archivo_glb' => 'recursos/3d/ficha_base_blanco.glb',
                    'url_imagen' => 'recursos/images/ficha_base_blanco_thumb.png',
                    'url_gcode' => 'recursos/3d/ficha_base_blanco_base.gcode',
                ]
            );

            Recurso::updateOrCreate(
                ['titulo' => 'Ficha Didáctica Táctil 3D (Demostración)'],
                [
                    'descripcion' => 'Cuerpo didáctico tridimensional con relieves Braille táctiles para estimulación háptica.',
                    'categoria_id' => $catBraille->id,
                    'gramos_pla' => 12.50,
                    'tiempo_minutos' => 35,
                    'fecha_creacion' => now()->toDateString(),
                    'estado' => Recurso::ESTADO_ACTIVO,
                    'archivo_glb' => 'recursos/3d/ficha_tactil_demo.glb',
                ]
            );
        }
    }
}
