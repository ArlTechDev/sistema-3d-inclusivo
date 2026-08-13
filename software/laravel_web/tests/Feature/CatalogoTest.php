<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Recurso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatalogoTest extends TestCase
{
    use RefreshDatabase;

    protected function crearSolicitante(): User
    {
        return User::create([
            'name' => 'Docente Prueba',
            'email' => 'docente@test.com',
            'password' => bcrypt('password'),
            'rol' => 'Solicitante',
        ]);
    }

    protected function crearAdmin(): User
    {
        return User::create([
            'name' => 'Admin Prueba',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'rol' => 'Administrador',
        ]);
    }

    protected function crearRecurso(string $titulo, string $estado = 'Activo'): Recurso
    {
        $categoria = Categoria::create(['nombre' => 'Braille', 'descripcion' => 'Fichas']);

        return Recurso::create([
            'titulo' => $titulo,
            'descripcion' => 'Descripción de prueba',
            'gramos_pla' => 10.00,
            'tiempo_minutos' => 30,
            'estado' => $estado,
            'categoria_id' => $categoria->id,
        ]);
    }

    public function test_solicitante_ve_el_catalogo_publico_con_boton_de_solicitud(): void
    {
        $solicitante = $this->crearSolicitante();
        $recurso = $this->crearRecurso('Ficha de vocabulario');

        $response = $this->actingAs($solicitante)->get(route('recursos.index'));

        $response->assertOk();
        $response->assertViewIs('recursos.catalogo');
        $response->assertSee('El material táctil, impreso para tu aula.');
        $response->assertSee('Ficha de vocabulario');
        $response->assertSee('Solicitar Impresión');
        $response->assertSee(route('pedidos.create', ['recurso' => $recurso->id]));
    }

    public function test_solicitante_no_ve_recursos_inactivos(): void
    {
        $solicitante = $this->crearSolicitante();
        $this->crearRecurso('Activo visible');
        $this->crearRecurso('Inactivo oculto', 'Inactivo');

        $response = $this->actingAs($solicitante)->get(route('recursos.index'));

        $response->assertOk();
        $response->assertSee('Activo visible');
        $response->assertDontSee('Inactivo oculto');
    }

    public function test_solicitante_puede_filtrar_por_categoria(): void
    {
        $solicitante = $this->crearSolicitante();
        $categoria = Categoria::create(['nombre' => 'Geografía', 'descripcion' => 'Mapas']);
        $recurso = Recurso::create([
            'titulo' => 'Mapa de Bolivia',
            'descripcion' => 'Mapa táctil',
            'gramos_pla' => 25.00,
            'tiempo_minutos' => 60,
            'estado' => 'Activo',
            'categoria_id' => $categoria->id,
        ]);

        $response = $this->actingAs($solicitante)
            ->get(route('recursos.index', ['categoria' => $categoria->id]));

        $response->assertOk();
        $response->assertViewHas('recursos', fn ($recursos) => $recursos->contains('id', $recurso->id));
    }

    public function test_administrador_ve_la_tabla_de_gestion_no_el_catalogo(): void
    {
        $admin = $this->crearAdmin();
        $this->crearRecurso('Ficha administrada');

        $response = $this->actingAs($admin)->get(route('recursos.index'));

        $response->assertOk();
        $response->assertViewIs('recursos.index');
        $response->assertSee('Ficha administrada');
        // El catálogo público no se renderiza para el Administrador
        $response->assertDontSee('El material táctil, impreso para tu aula.');
    }

    public function test_formulario_de_solicitud_usa_el_layout_publico(): void
    {
        $solicitante = $this->crearSolicitante();
        $recurso = $this->crearRecurso('Ficha de vocabulario');

        $response = $this->actingAs($solicitante)
            ->get(route('pedidos.create', ['recurso' => $recurso->id]));

        $response->assertOk();
        $response->assertSee('Solicitar Impresión de Recurso');
        $response->assertDontSee('adminlte');
    }

    public function test_descarga_de_gcode_del_recurso_solo_para_administrador(): void
    {
        Storage::fake('local');

        $recurso = $this->crearRecurso('Recurso con G-Code');
        $ruta = 'recursos/gcode/ejemplo.gcode';
        Storage::disk('local')->put($ruta, "G21\nG28\n");
        $recurso->update(['url_gcode' => $ruta]);

        $solicitante = User::factory()->create(['rol' => 'Solicitante']);
        $this->actingAs($solicitante)->get(route('recursos.gcode', $recurso))->assertForbidden();

        $admin = $this->crearAdmin();
        $this->actingAs($admin)->get(route('recursos.gcode', $recurso))
            ->assertOk()
            ->assertDownload('ejemplo.gcode');
    }
}
