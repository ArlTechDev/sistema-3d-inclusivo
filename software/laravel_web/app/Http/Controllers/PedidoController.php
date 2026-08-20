<?php

namespace App\Http\Controllers;

use App\Exports\PedidosExport;
use App\Http\Requests\RechazarPedidoRequest;
use App\Http\Requests\StorePedidoRequest;
use App\Http\Requests\UpdatePedidoRequest;
use App\Models\ConfiguracionSistema;
use App\Models\Institucion;
use App\Models\Pedido;
use App\Models\Recurso;
use App\Models\User;
use App\Services\PedidoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PedidoController extends Controller
{
    /**
     * Alias de compatibilidad hacia las transiciones definidas en el modelo Pedido.
     */
    public const TRANSICIONES = Pedido::TRANSICIONES;

    /**
     * Panel del Administrador: listado de solicitudes filtrable por estado,
     * institución y fecha (UC-08).
     */
    public function index(Request $request)
    {
        $pedidos = Pedido::with(['user', 'institucion', 'detalles.recurso'])
            ->when($request->get('estado'), fn ($q, $estado) => $q->where('estado', $estado))
            ->when($request->get('institucion_id'), fn ($q, $id) => $q->where('institucion_id', $id))
            ->when($request->get('fecha'), fn ($q, $fecha) => $q->whereDate('fecha_solicitud', $fecha))
            ->latest('fecha_solicitud')
            ->get();

        $instituciones = Institucion::all();

        return view('pedidos.index', compact('pedidos', 'instituciones'));
    }

    /**
     * Formulario del Solicitante para solicitar la impresión de un recurso (UC-07).
     */
    public function create(Request $request)
    {
        $recursos = Recurso::with('categoria')
            ->where('estado', Recurso::ESTADO_ACTIVO)
            ->get();
        $instituciones = Institucion::all();
        $recursoSeleccionado = $request->integer('recurso');
        $precioGramo = (float) (ConfiguracionSistema::where('clave', 'precio_gramo_pla')->value('valor') ?? 0.05);

        return view('pedidos.create', compact('recursos', 'instituciones', 'recursoSeleccionado', 'precioGramo'));
    }

    public function store(StorePedidoRequest $request, PedidoService $pedidoService)
    {
        $pedidoService->crearPedido($request->validated(), (int) auth()->id());

        return redirect()->route('recursos.index')->with('success', 'Solicitud de impresión registrada correctamente.');
    }

    /**
     * El Administrador avanza el estado del pedido (Pendiente → En impresión → Completado).
     */
    public function update(UpdatePedidoRequest $request, Pedido $pedido)
    {
        $nuevoEstado = $request->validated()['estado'];

        if (! $pedido->puedeTransicionarA($nuevoEstado)) {
            return redirect()->route('pedidos.index')->withErrors([
                'estado' => 'No se puede pasar un pedido '.$pedido->estado.' a «'.$nuevoEstado.'».',
            ]);
        }

        $pedido->update(['estado' => $nuevoEstado]);

        return redirect()->route('pedidos.index')->with('success', 'Estado del pedido actualizado.');
    }

    public function rechazar(RechazarPedidoRequest $request, Pedido $pedido)
    {
        if (! $pedido->puedeTransicionarA(Pedido::ESTADO_RECHAZADO)) {
            return redirect()->route('pedidos.index')->withErrors([
                'estado' => 'Un pedido '.$pedido->estado.' no puede rechazarse.',
            ]);
        }

        $pedido->update([
            'estado' => Pedido::ESTADO_RECHAZADO,
            'motivo_rechazo' => $request->validated()['motivo_rechazo'],
        ]);

        return redirect()->route('pedidos.index')->with('success', 'Pedido rechazado correctamente.');
    }

    /**
     * Descarga del archivo G-Code, exclusiva del Administrador (UC-09).
     * Usa el gcode_path del pedido o, en su defecto, el G-Code del recurso del catálogo.
     */
    public function descargarGCode(Pedido $pedido)
    {
        $ruta = $pedido->gcode_path;

        if (! $ruta) {
            $detalle = $pedido->detalles->first();
            $ruta = $detalle?->recurso?->url_gcode;
        }

        abort_if(! $ruta || ! Storage::disk('local')->exists($ruta), 404, 'El archivo G-Code no está disponible.');

        return Storage::disk('local')->download($ruta);
    }

    public function exportarPdf()
    {
        $pedidos = Pedido::with(['user', 'institucion'])->get();
        $pdf = Pdf::loadView('pedidos.pdf', compact('pedidos'));

        return $pdf->stream('pedidos.pdf');
    }

    public function exportarExcel()
    {
        return Excel::download(new PedidosExport, 'pedidos.xlsx');
    }

    /**
     * «Mis solicitudes» del Solicitante: sus pedidos, con los cancelados visibles (SoftDelete).
     */
    public function mis()
    {
        // El panel de gestión es del Administrador (pedidos.index).
        if (auth()->user()->rol === User::ROL_ADMINISTRADOR) {
            return redirect()->route('pedidos.index');
        }

        $pedidos = Pedido::with(['detalles.recurso'])
            ->where('user_id', auth()->id())
            ->withTrashed()
            ->latest('fecha_solicitud')
            ->get();

        return view('pedidos.mis', compact('pedidos'));
    }

    /**
     * El Solicitante cancela su propia solicitud Pendiente (UC-08, SoftDelete).
     */
    public function cancelar(Pedido $pedido)
    {
        abort_if($pedido->user_id !== auth()->id(), 403, 'No puedes cancelar una solicitud de otro usuario.');

        if ($pedido->estado !== Pedido::ESTADO_PENDIENTE || $pedido->trashed()) {
            return redirect()->route('pedidos.mis')->withErrors([
                'cancelar' => 'Solo se puede cancelar una solicitud en estado Pendiente.',
            ]);
        }

        $pedido->delete();

        return redirect()->route('pedidos.mis')->with('success', 'Solicitud cancelada correctamente.');
    }
}
