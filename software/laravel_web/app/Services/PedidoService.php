<?php

namespace App\Services;

use App\Models\ConfiguracionSistema;
use App\Models\Pedido;
use App\Models\Recurso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PedidoService
{
    public function __construct(
        private readonly BrailleTranslator $brailleTranslator
    ) {}

    /**
     * Registra un nuevo pedido con sus detalles y genera su G-Code si aplica.
     *
     * @param  array<string, mixed>  $datos
     */
    public function crearPedido(array $datos, int $userId): Pedido
    {
        return DB::transaction(function () use ($datos, $userId) {
            $recurso = Recurso::where('estado', Recurso::ESTADO_ACTIVO)->findOrFail($datos['recurso_id']);
            $textoPersonalizado = trim((string) ($datos['texto_personalizado'] ?? ''));
            $costos = $this->calcularCostos($recurso, (int) $datos['cantidad'], $textoPersonalizado);

            $pedido = Pedido::create([
                'user_id' => $userId,
                'institucion_id' => $datos['institucion_id'] ?? null,
                'estado' => Pedido::ESTADO_PENDIENTE,
                'fecha_solicitud' => now(),
                'total_gramos_pla' => $costos['gramos'],
                'costo_total' => $costos['costo'],
            ]);

            $pedido->detalles()->create([
                'recurso_id' => $recurso->id,
                'cantidad' => $datos['cantidad'],
                'gramos_pla' => $costos['gramos'],
                'costo_unitario' => $costos['costo_unitario'],
            ]);

            if ($textoPersonalizado !== '') {
                $gcodePath = $this->generarYGuardarGCode($pedido, $textoPersonalizado);
                $pedido->update(['gcode_path' => $gcodePath]);
            }

            return $pedido;
        });
    }

    /**
     * Calcula los gramos totales de PLA y los costos (unitario y total) del pedido,
     * incorporando opcionalmente el consumo adicional de filamento del relieve Braille.
     *
     * @return array{gramos: float, costo_unitario: float, costo: float}
     */
    public function calcularCostos(Recurso $recurso, int $cantidad, string $textoPersonalizado = ''): array
    {
        $precioGramo = (float) (ConfiguracionSistema::where('clave', 'precio_gramo_pla')->value('valor') ?? 0.15);
        $gramosPorCelda = (float) (ConfiguracionSistema::where('clave', 'gramos_por_celda_braille')->value('valor') ?? 0.02);

        $gramosExtra = 0.0;
        if ($textoPersonalizado !== '') {
            $celdas = count($this->brailleTranslator->traducir($textoPersonalizado));
            $gramosExtra = round($celdas * $gramosPorCelda, 3);
        }

        $gramosUnitario = round($recurso->gramos_pla + $gramosExtra, 2);
        $gramos = round($gramosUnitario * $cantidad, 2);
        $costoUnitario = round($gramosUnitario * $precioGramo, 2);
        $costo = round($gramos * $precioGramo, 2);

        return [
            'gramos' => $gramos,
            'costo_unitario' => $costoUnitario,
            'costo' => $costo,
        ];
    }

    /**
     * Genera el G-Code para un pedido con texto personalizado y lo guarda en almacenamiento local.
     *
     * @return string Ruta del archivo G-Code generado
     */
    public function generarYGuardarGCode(Pedido $pedido, string $textoPersonalizado): string
    {
        $gcode = $this->brailleTranslator->generarGCode($textoPersonalizado, 5.0, 5.0, 0.2);
        $nombre = 'pedidos/gcode/pedido_'.$pedido->id.'_'.uniqid().'.gcode';
        Storage::disk('local')->put($nombre, $gcode);

        return $nombre;
    }
}
