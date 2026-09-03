<?php

namespace Tests\Feature;

use App\Models\Recurso;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_ficha_base_sembrada_referencia_activos_disponibles(): void
    {
        $this->seed(DatabaseSeeder::class);

        $recurso = Recurso::where('titulo', 'Ficha Base Estándar (80x30 mm)')->firstOrFail();

        Storage::disk('public')->assertExists($recurso->archivo_stl);
        Storage::disk('public')->assertExists($recurso->archivo_glb);
        Storage::disk('public')->assertExists($recurso->url_imagen);
        Storage::disk('local')->assertExists($recurso->url_gcode);
    }
}
