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
use App\Services\BrailleTranslator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class PedidoController extends Controller
{
    /**
     * Transiciones de estado permitidas (UC-08). El rechazo se gestiona aparte (rechazar).
     *
     * @var array<string, array<int, string>>
     */
    public const TRANSICIONES = [
        'Pendiente' => ['En impresión', 'Rechazado'],
        'En impresión' => ['Completado', 'Rechazado'],
    ];

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
        $recursos = Recurso::where('estado', 'Activo')->get();
        $instituciones = Institucion::all();
        $recursoSeleccionado = $request->integer('recurso');

        return view('pedidos.create', compact('recursos', 'instituciones', 'recursoSeleccionado'));
    }

    /**
     * Registra la solicitud, calcula gramos/costo con configuracion_sistemas.precio_gramo_pla
     * y genera el G-Code si el solicitante ingresó texto personalizado (UC-07).
     */
    public function store(StorePedidoRequest $request)
    {
        $datos = $request->validated();

        $recurso = Recurso::where('estado', 'Activo')->findOrFail($datos['recurso_id']);
        $precioGramo = (float) (ConfiguracionSistema::where('clave', 'precio_gramo_pla')->value('valor') ?? 0.05);

        // Texto personalizado (Opción A): se valida ANTES de crear el pedido (UC-06 → UC-07).
        // Se usa el input crudo (no el sanitizado con entidades HTML) porque el traductor
        // soporta comillas (') y ("); el texto solo viaja dentro del archivo .gcode descargado.
        $textoPersonalizado = trim((string) $request->input('texto_personalizado'));

        if ($textoPersonalizado !== '') {
            $invalidos = app(BrailleTranslator::class)->validarCaracteres($textoPersonalizado);
            if ($invalidos !== []) {
                throw ValidationException::withMessages([
                    'texto_personalizado' => 'El texto contiene caracteres no soportados: '.implode(', ', $invalidos).'.',
                ]);
            }
        }

        $gramos = round($recurso->gramos_pla * $datos['cantidad'], 2);
        $costoUnitario = round($recurso->gramos_pla * $precioGramo, 2);
        $costo = round($gramos * $precioGramo, 2);

        $pedido = Pedido::create([
            'user_id' => auth()->id(),
            'institucion_id' => $datos['institucion_id'],
            'estado' => 'Pendiente',
            'fecha_solicitud' => now(),
            'total_gramos_pla' => $gramos,
            'costo_total' => $costo,
        ]);

        $pedido->detalles()->create([
            'recurso_id' => $recurso->id,
            'cantidad' => $datos['cantidad'],
            'gramos_pla' => $gramos,
            'costo_unitario' => $costoUnitario,
        ]);

        if ($textoPersonalizado !== '') {
            $gcode = app(BrailleTranslator::class)->generarGCode($textoPersonalizado, 5.0, 5.0, 0.2);
            $nombre = 'pedidos/gcode/pedido_'.$pedido->id.'_'.uniqid().'.gcode';
            Storage::disk('local')->put($nombre, $gcode);
            $pedido->update(['gcode_path' => $nombre]);
        }

        return redirect()->route('recursos.index')->with('success', 'Solicitud de impresión registrada correctamente.');
    }

    /**
     * El Administrador avanza el estado del pedido (Pendiente → En impresión → Completado).
     */
    public function update(UpdatePedidoRequest $request, Pedido $pedido)
    {
        $nuevoEstado = $request->validated()['estado'];

        if (! in_array($nuevoEstado, self::TRANSICIONES[$pedido->estado] ?? [], true)) {
            return redirect()->route('pedidos.index')->withErrors([
                'estado' => 'No se puede pasar un pedido '.$pedido->estado.' a «'.$nuevoEstado.'».',
            ]);
        }

        $pedido->update(['estado' => $nuevoEstado]);

        return redirect()->route('pedidos.index')->with('success', 'Estado del pedido actualizado.');
    }

    /**
     * El Administrador rechaza el pedido registrando un motivo obligatorio.
     */
    public function rechazar(RechazarPedidoRequest $request, Pedido $pedido)
    {
        if (! in_array('Rechazado', self::TRANSICIONES[$pedido->estado] ?? [], true)) {
            return redirect()->route('pedidos.index')->withErrors([
                'estado' => 'Un pedido '.$pedido->estado.' no puede rechazarse.',
            ]);
        }

        $pedido->update([
            'estado' => 'Rechazado',
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
        if (auth()->user()->rol === 'Administrador') {
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

        if ($pedido->estado !== 'Pendiente' || $pedido->trashed()) {
            return redirect()->route('pedidos.mis')->withErrors([
                'cancelar' => 'Solo se puede cancelar una solicitud en estado Pendiente.',
            ]);
        }

        $pedido->delete();

        return redirect()->route('pedidos.mis')->with('success', 'Solicitud cancelada correctamente.');
    }
}
