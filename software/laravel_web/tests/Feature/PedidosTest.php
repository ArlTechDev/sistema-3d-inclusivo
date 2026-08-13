<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\ConfiguracionSistema;
use App\Models\Institucion;
use App\Models\Pedido;
use App\Models\Recurso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PedidosTest extends TestCase
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

    protected function crearRecurso(): Recurso
    {
        $categoria = Categoria::create(['nombre' => 'Braille', 'descripcion' => 'Fichas y alfabeto']);

        return Recurso::create([
            'titulo' => 'Ficha de vocabulario',
            'descripcion' => 'Ficha táctil con texto en Braille',
            'gramos_pla' => 10.00,
            'tiempo_minutos' => 30,
            'estado' => 'Activo',
            'categoria_id' => $categoria->id,
        ]);
    }

    protected function crearInstitucion(): Institucion
    {
        return Institucion::create([
            'nombre' => 'Instituto de Educación Especial',
            'direccion' => 'Av. Prueba 123',
            'telefono' => '4412345',
            'director' => 'Lic. Prueba',
        ]);
    }

    protected function crearPrecioGramo(float $precio = 0.05): void
    {
        ConfiguracionSistema::create([
            'clave' => 'precio_gramo_pla',
            'valor' => (string) $precio,
            'descripcion' => 'Precio del gramo de PLA en USD',
        ]);
    }

    public function test_solicitante_puede_registrar_pedido_con_costos_calculados(): void
    {
        $this->crearPrecioGramo(0.05);
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $response = $this->actingAs($solicitante)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'institucion_id' => $institucion->id,
            'cantidad' => 3,
        ]);

        $response->assertRedirect(route('recursos.index'));
        $this->actingAs($solicitante)->get(route('recursos.index'))->assertOk();

        $this->assertDatabaseHas('pedidos', [
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => 'Pendiente',
            'total_gramos_pla' => 30.00,   // 10 g × 3
            'costo_total' => 1.50,         // 30 g × 0.05
        ]);

        $this->assertDatabaseHas('detalle_pedidos', [
            'recurso_id' => $recurso->id,
            'cantidad' => 3,
            'gramos_pla' => 30.00,
            'costo_unitario' => 0.50,      // 10 g × 0.05 (costo unitario por unidad)
        ]);
    }

    public function test_texto_personalizado_genera_archivo_gcode(): void
    {
        Storage::fake('local');
        $this->crearPrecioGramo();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $response = $this->actingAs($solicitante)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'institucion_id' => $institucion->id,
            'cantidad' => 1,
            'texto_personalizado' => 'ÑANDÚ',
        ]);

        $response->assertSessionHasNoErrors();
        $pedido = Pedido::first();

        $this->assertNotNull($pedido->gcode_path);
        Storage::disk('local')->assertExists($pedido->gcode_path);
        $this->assertStringContainsString('G28', Storage::disk('local')->get($pedido->gcode_path));
    }

    public function test_texto_personalizado_con_caracteres_invalidos_no_genera_pedido(): void
    {
        $this->crearPrecioGramo();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $response = $this->actingAs($solicitante)->from(route('pedidos.create'))->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'institucion_id' => $institucion->id,
            'cantidad' => 1,
            'texto_personalizado' => 'hola@mundo',
        ]);

        $response->assertRedirect(route('pedidos.create'));
        $response->assertSessionHasErrors('texto_personalizado');
        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_texto_personalizado_acepta_puntuacion_con_comillas(): void
    {
        $this->crearPrecioGramo();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $response = $this->actingAs($solicitante)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'institucion_id' => $institucion->id,
            'cantidad' => 1,
            'texto_personalizado' => '¿Dijo "hola"?',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('pedidos', 1);
    }

    public function test_registro_requiere_datos_validos(): void
    {
        $this->crearPrecioGramo();
        $solicitante = $this->crearSolicitante();

        $response = $this->actingAs($solicitante)->post(route('pedidos.store'), [
            'recurso_id' => 999,
            'cantidad' => 0,
        ]);

        $response->assertSessionHasErrors(['recurso_id', 'institucion_id', 'cantidad']);
        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_solicitante_no_puede_ver_el_panel_de_pedidos(): void
    {
        $solicitante = $this->crearSolicitante();

        $this->actingAs($solicitante)->get(route('pedidos.index'))
            ->assertForbidden();
    }

    public function test_solicitante_no_puede_descargar_gcode(): void
    {
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $pedido = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => 'Pendiente',
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($solicitante)->get(route('pedidos.gcode', $pedido))
            ->assertForbidden();
    }

    public function test_admin_puede_avanzar_estados_del_pedido(): void
    {
        $admin = $this->crearAdmin();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();

        $pedido = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => 'Pendiente',
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($admin)->patch(route('pedidos.update', $pedido), ['estado' => 'En impresión'])
            ->assertRedirect(route('pedidos.index'));

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => 'En impresión']);

        $this->actingAs($admin)->patch(route('pedidos.update', $pedido), ['estado' => 'Completado'])
            ->assertRedirect(route('pedidos.index'));

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => 'Completado']);
    }

    public function test_transicion_de_estado_invalida_es_bloqueada(): void
    {
        $admin = $this->crearAdmin();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();

        $pedido = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => 'En impresión',
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        // Volver a Pendiente no está permitido (solo Pendiente → En impresión → Completado)
        $this->actingAs($admin)->patch(route('pedidos.update', $pedido), ['estado' => 'Pendiente'])
            ->assertRedirect(route('pedidos.index'))
            ->assertSessionHasErrors('estado');

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => 'En impresión']);
    }

    public function test_rechazo_requiere_motivo_obligatorio(): void
    {
        $admin = $this->crearAdmin();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();

        $pedido = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => 'Pendiente',
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($admin)->patch(route('pedidos.rechazar', $pedido), ['motivo_rechazo' => ''])
            ->assertSessionHasErrors('motivo_rechazo');

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => 'Pendiente']);

        $this->actingAs($admin)->patch(route('pedidos.rechazar', $pedido), ['motivo_rechazo' => 'Filamento insuficiente'])
            ->assertRedirect(route('pedidos.index'));

        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'estado' => 'Rechazado',
            'motivo_rechazo' => 'Filamento insuficiente',
        ]);
    }

    public function test_admin_puede_descargar_gcode_del_pedido(): void
    {
        Storage::fake('local');
        $admin = $this->crearAdmin();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $rutaGcode = 'recursos/gcode/prueba.gcode';
        Storage::disk('local')->put($rutaGcode, "G21\nG28\nM84\n");
        $recurso->update(['url_gcode' => $rutaGcode]);

        $pedido = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => 'Pendiente',
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);
        $pedido->detalles()->create([
            'recurso_id' => $recurso->id,
            'cantidad' => 1,
            'gramos_pla' => 10.00,
            'costo_unitario' => 0.50,
        ]);

        $response = $this->actingAs($admin)->get(route('pedidos.gcode', $pedido));

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=prueba.gcode');
    }
}
