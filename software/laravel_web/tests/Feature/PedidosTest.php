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

        $pedido = Pedido::first();
        $response->assertRedirect(route('pedidos.checkout', $pedido));
        $this->actingAs($solicitante)->get(route('pedidos.checkout', $pedido))->assertOk();

        $this->assertDatabaseHas('pedidos', [
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => Pedido::ESTADO_PENDIENTE,
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

    public function test_solicitante_puede_registrar_pedido_sin_institucion(): void
    {
        $this->crearPrecioGramo(0.05);
        $solicitante = $this->crearSolicitante();
        $recurso = $this->crearRecurso();

        $response = $this->actingAs($solicitante)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'institucion_id' => null,
            'cantidad' => 2,
        ]);

        $pedido = Pedido::first();
        $response->assertRedirect(route('pedidos.checkout', $pedido));
        $this->assertDatabaseHas('pedidos', [
            'user_id' => $solicitante->id,
            'institucion_id' => null,
            'estado' => Pedido::ESTADO_PENDIENTE,
            'total_gramos_pla' => 20.00,
            'costo_total' => 1.00,
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

    public function test_gcode_respeta_altura_z_de_placa_del_recurso(): void
    {
        Storage::fake('local');
        $this->crearPrecioGramo();
        $solicitante = $this->crearSolicitante();
        $recurso = $this->crearRecurso();
        $recurso->update([
            'tipo_placa' => 'integrada',
            'placa_ancho' => 80,
            'placa_alto' => 30,
            'placa_z_altura' => 3,
            'max_caracteres' => 22,
        ]);

        $response = $this->actingAs($solicitante)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'cantidad' => 1,
            'texto_personalizado' => 'HOLA',
        ]);

        $response->assertSessionHasNoErrors();
        $pedido = Pedido::first();

        $gcodeContent = Storage::disk('local')->get($pedido->gcode_path);
        // Debe descender a Z = 3.00 mm (espesor de la placa), no a 0.20 mm
        $this->assertStringContainsString('G1 Z3.00', $gcodeContent);
        $this->assertStringContainsString('G1 Z3.80', $gcodeContent); // 3.0 + 0.8 relieve
    }

    public function test_texto_personalizado_que_excede_capacidad_de_placa_es_rechazado(): void
    {
        $this->crearPrecioGramo();
        $solicitante = $this->crearSolicitante();
        $recurso = $this->crearRecurso();
        $recurso->update([
            'tipo_placa' => 'integrada',
            'max_caracteres' => 5, // Capacidad máxima pequeña para probar
        ]);

        // "HOLA MUNDO" ocupa 12 celdas Braille (con mayúsculas) > 5 celdas
        $response = $this->actingAs($solicitante)->from(route('pedidos.create'))->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'cantidad' => 1,
            'texto_personalizado' => 'HOLA MUNDO',
        ]);

        $response->assertRedirect(route('pedidos.create'));
        $response->assertSessionHasErrors('texto_personalizado');
        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_texto_personalizado_calcula_filamento_braille_adicional(): void
    {
        $this->crearPrecioGramo(0.10);
        ConfiguracionSistema::create([
            'clave' => 'gramos_por_celda_braille',
            'valor' => '0.05',
            'descripcion' => 'Gramos por celda',
        ]);

        $solicitante = $this->crearSolicitante();
        $recurso = $this->crearRecurso(); // 10.00 gramos base

        // Texto: "HOLA" (4 celdas Braille) -> 4 * 0.05 = 0.20 g extra -> 10.20 g unitario
        $response = $this->actingAs($solicitante)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'cantidad' => 2,
            'texto_personalizado' => 'hola',
        ]);

        $response->assertSessionHasNoErrors();
        $pedido = Pedido::first();

        // 10.20 g unitario * 2 = 20.40 g totales
        $this->assertEquals(20.40, $pedido->total_gramos_pla);
        // 20.40 * 0.10 = 2.04 Bs costo total
        $this->assertEquals(2.04, $pedido->costo_total);
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
            'institucion_id' => 999,
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
            'estado' => Pedido::ESTADO_PENDIENTE,
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
            'estado' => Pedido::ESTADO_PENDIENTE,
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($admin)->patch(route('pedidos.update', $pedido), ['estado' => Pedido::ESTADO_APROBADO])
            ->assertRedirect(route('pedidos.index'));

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => Pedido::ESTADO_APROBADO]);

        $this->actingAs($admin)->patch(route('pedidos.update', $pedido), ['estado' => Pedido::ESTADO_EN_IMPRESION])
            ->assertRedirect(route('pedidos.index'));

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => Pedido::ESTADO_EN_IMPRESION]);

        $this->actingAs($admin)->patch(route('pedidos.update', $pedido), ['estado' => Pedido::ESTADO_COMPLETADO])
            ->assertRedirect(route('pedidos.index'));

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => Pedido::ESTADO_COMPLETADO]);
    }

    public function test_transicion_de_estado_invalida_es_bloqueada(): void
    {
        $admin = $this->crearAdmin();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();

        $pedido = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => Pedido::ESTADO_EN_IMPRESION,
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        // Volver a Pendiente no está permitido
        $this->actingAs($admin)->patch(route('pedidos.update', $pedido), ['estado' => Pedido::ESTADO_PENDIENTE])
            ->assertRedirect(route('pedidos.index'))
            ->assertSessionHasErrors('estado');

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => Pedido::ESTADO_EN_IMPRESION]);
    }

    public function test_estado_aprobado_no_puede_volver_a_pendiente(): void
    {
        $admin = $this->crearAdmin();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();

        $pedido = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => Pedido::ESTADO_APROBADO,
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($admin)->patch(route('pedidos.update', $pedido), ['estado' => Pedido::ESTADO_PENDIENTE])
            ->assertRedirect(route('pedidos.index'))
            ->assertSessionHasErrors('estado');

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => Pedido::ESTADO_APROBADO]);
    }

    public function test_rechazo_requiere_motivo_obligatorio(): void
    {
        $admin = $this->crearAdmin();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();

        $pedido = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => Pedido::ESTADO_PENDIENTE,
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($admin)->patch(route('pedidos.rechazar', $pedido), ['motivo_rechazo' => ''])
            ->assertSessionHasErrors('motivo_rechazo');

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => Pedido::ESTADO_PENDIENTE]);

        $this->actingAs($admin)->patch(route('pedidos.rechazar', $pedido), ['motivo_rechazo' => 'Filamento insuficiente'])
            ->assertRedirect(route('pedidos.index'));

        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'estado' => Pedido::ESTADO_RECHAZADO,
            'motivo_rechazo' => 'Filamento insuficiente',
        ]);
    }

    public function test_admin_puede_rechazar_desde_aprobado_y_en_impresion(): void
    {
        $admin = $this->crearAdmin();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();

        $pedidoAprobado = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => Pedido::ESTADO_APROBADO,
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($admin)->patch(route('pedidos.rechazar', $pedidoAprobado), ['motivo_rechazo' => 'Error de diseño'])
            ->assertRedirect(route('pedidos.index'));
        $this->assertDatabaseHas('pedidos', ['id' => $pedidoAprobado->id, 'estado' => Pedido::ESTADO_RECHAZADO]);

        $pedidoImpresion = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => Pedido::ESTADO_EN_IMPRESION,
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($admin)->patch(route('pedidos.rechazar', $pedidoImpresion), ['motivo_rechazo' => 'Fallo de adherencia en cama'])
            ->assertRedirect(route('pedidos.index'));
        $this->assertDatabaseHas('pedidos', ['id' => $pedidoImpresion->id, 'estado' => Pedido::ESTADO_RECHAZADO]);
    }

    public function test_rechazo_de_pedido_completado_o_ya_rechazado_es_bloqueado(): void
    {
        $admin = $this->crearAdmin();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();

        $pedidoCompletado = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => Pedido::ESTADO_COMPLETADO,
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($admin)->patch(route('pedidos.rechazar', $pedidoCompletado), ['motivo_rechazo' => 'Intento posterior'])
            ->assertRedirect(route('pedidos.index'))
            ->assertSessionHasErrors('estado');

        $this->assertDatabaseHas('pedidos', ['id' => $pedidoCompletado->id, 'estado' => Pedido::ESTADO_COMPLETADO]);
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
            'estado' => Pedido::ESTADO_PENDIENTE,
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

    public function test_solicitante_cancela_su_pedido_pendiente(): void
    {
        $this->crearPrecioGramo();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $response = $this->actingAs($solicitante)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'institucion_id' => $institucion->id,
            'cantidad' => 1,
        ]);
        $response->assertSessionHasNoErrors();

        $pedido = Pedido::first();

        $this->actingAs($solicitante)
            ->delete(route('pedidos.cancelar', $pedido))
            ->assertRedirect(route('pedidos.mis'));

        $this->assertSoftDeleted('pedidos', ['id' => $pedido->id]);
    }

    public function test_solicitante_no_cancela_pedido_de_otro_usuario(): void
    {
        $this->crearPrecioGramo();
        $solicitanteA = $this->crearSolicitante();
        $solicitanteB = User::create([
            'name' => 'Otro Docente',
            'email' => 'otro@test.com',
            'password' => bcrypt('password'),
            'rol' => 'Solicitante',
        ]);
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $this->actingAs($solicitanteA)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'institucion_id' => $institucion->id,
            'cantidad' => 1,
        ]);
        $pedido = Pedido::first();

        $this->actingAs($solicitanteB)
            ->delete(route('pedidos.cancelar', $pedido))
            ->assertForbidden();

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'deleted_at' => null]);
    }

    public function test_no_se_cancela_pedido_en_impresion(): void
    {
        $this->crearPrecioGramo();
        $solicitante = $this->crearSolicitante();
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $pedido = Pedido::create([
            'user_id' => $solicitante->id,
            'institucion_id' => $institucion->id,
            'estado' => Pedido::ESTADO_EN_IMPRESION,
            'fecha_solicitud' => now(),
            'total_gramos_pla' => 10,
            'costo_total' => 0.50,
        ]);

        $this->actingAs($solicitante)
            ->delete(route('pedidos.cancelar', $pedido))
            ->assertRedirect(route('pedidos.mis'))
            ->assertSessionHasErrors('cancelar');

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'estado' => Pedido::ESTADO_EN_IMPRESION, 'deleted_at' => null]);
    }

    public function test_solicitante_ve_sus_solicitudes_y_admin_es_redirigido(): void
    {
        $this->crearPrecioGramo();
        $solicitante = $this->crearSolicitante();
        $admin = $this->crearAdmin();
        $institucion = $this->crearInstitucion();
        $recurso = $this->crearRecurso();

        $this->actingAs($solicitante)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'institucion_id' => $institucion->id,
            'cantidad' => 1,
        ]);

        $this->actingAs($solicitante)
            ->get(route('pedidos.mis'))
            ->assertOk()
            ->assertSee('Ficha de vocabulario')
            ->assertSee('Pendiente');

        $this->actingAs($admin)
            ->get(route('pedidos.mis'))
            ->assertRedirect(route('pedidos.index'));
    }

    public function test_solicitante_puede_ver_checkout_de_su_pedido_y_no_de_otro(): void
    {
        $this->crearPrecioGramo();
        $solicitanteA = $this->crearSolicitante();
        $solicitanteB = User::create([
            'name' => 'Otro Usuario',
            'email' => 'otro_user@test.com',
            'password' => bcrypt('password'),
            'rol' => 'Solicitante',
        ]);
        $recurso = $this->crearRecurso();

        $this->actingAs($solicitanteA)->post(route('pedidos.store'), [
            'recurso_id' => $recurso->id,
            'cantidad' => 1,
        ]);
        $pedido = Pedido::first();

        // Solicitante A puede ver su checkout
        $this->actingAs($solicitanteA)
            ->get(route('pedidos.checkout', $pedido))
            ->assertOk()
            ->assertSee('Confirmación y Pago')
            ->assertSee('Ficha de vocabulario');

        // Solicitante B recibe 403 al intentar ver el checkout de Solicitante A
        $this->actingAs($solicitanteB)
            ->get(route('pedidos.checkout', $pedido))
            ->assertForbidden();
    }
}
