<?php

namespace App\Http\Controllers;

use App\Exports\RecursosExport;
use App\Http\Requests\StoreRecursoRequest;
use App\Http\Requests\UpdateRecursoRequest;
use App\Models\Categoria;
use App\Models\ConfiguracionSistema;
use App\Models\Recurso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RecursoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('recursos')->get();

        // El Solicitante ve el catálogo público (cards); el Administrador, la tabla de gestión.
        if (auth()->check() && auth()->user()->rol === 'Administrador') {
            $recursos = Recurso::all();

            return view('recursos.index', compact('recursos', 'categorias'));
        }

        $recursos = Recurso::with('categoria')
            ->where('estado', Recurso::ESTADO_ACTIVO)
            ->when(request('categoria'), fn ($q, $id) => $q->where('categoria_id', $id))
            ->get();

        $precioGramo = (float) (ConfiguracionSistema::where('clave', 'precio_gramo_pla')->value('valor') ?? 0.15);
        $moneda = (string) (ConfiguracionSistema::where('clave', 'moneda_simbolo')->value('valor') ?? 'Bs');
        $gramosPorCelda = (float) (ConfiguracionSistema::where('clave', 'gramos_por_celda_braille')->value('valor') ?? 0.02);

        return view('recursos.catalogo', compact('recursos', 'categorias', 'precioGramo', 'moneda', 'gramosPorCelda'));
    }

    public function exportarPdf()
    {
        $recursos = Recurso::all();
        $pdf = Pdf::loadView('recursos.pdf', compact('recursos'));

        return $pdf->stream();
    }

    public function exportarExcel()
    {
        return Excel::download(new RecursosExport, 'recursos.xlsx');
    }

    public function create()
    {
        $categorias = Categoria::all();

        return view('recursos.create', compact('categorias'));
    }

    public function store(StoreRecursoRequest $request)
    {
        $data = $request->except(['url_imagen', 'url_gcode', 'archivo_stl', 'archivo_glb']);

        if ($request->hasFile('url_imagen')) {
            $data['url_imagen'] = $request->file('url_imagen')
                ->store('recursos/images', 'public');
        }

        if ($request->hasFile('url_gcode')) {
            $data['url_gcode'] = $request->file('url_gcode')
                ->store('recursos/gcode', 'local');
        }

        if ($request->hasFile('archivo_stl')) {
            $data['archivo_stl'] = $request->file('archivo_stl')
                ->store('recursos/3d', 'public');
        }

        if ($request->hasFile('archivo_glb')) {
            $data['archivo_glb'] = $request->file('archivo_glb')
                ->store('recursos/3d', 'public');
        }

        Recurso::create($data);

        return redirect()->route('recursos.index')
            ->with('success', 'Recurso educativo registrado exitosamente.');
    }

    public function edit(Recurso $recurso)
    {
        $categorias = Categoria::all();

        return view('recursos.edit', compact('recurso', 'categorias'));
    }

    public function update(UpdateRecursoRequest $request, Recurso $recurso)
    {
        $data = $request->except(['url_imagen', 'url_gcode', 'archivo_stl', 'archivo_glb']);

        if ($request->hasFile('url_imagen')) {
            if ($recurso->url_imagen) {
                Storage::disk('public')->delete($recurso->url_imagen);
            }

            $data['url_imagen'] = $request->file('url_imagen')
                ->store('recursos/images', 'public');
        }

        if ($request->hasFile('url_gcode')) {
            if ($recurso->url_gcode) {
                Storage::disk('local')->delete($recurso->url_gcode);
            }

            $data['url_gcode'] = $request->file('url_gcode')
                ->store('recursos/gcode', 'local');
        }

        if ($request->hasFile('archivo_stl')) {
            if ($recurso->archivo_stl) {
                Storage::disk('public')->delete($recurso->archivo_stl);
            }

            $data['archivo_stl'] = $request->file('archivo_stl')
                ->store('recursos/3d', 'public');
        }

        if ($request->hasFile('archivo_glb')) {
            if ($recurso->archivo_glb) {
                Storage::disk('public')->delete($recurso->archivo_glb);
            }

            $data['archivo_glb'] = $request->file('archivo_glb')
                ->store('recursos/3d', 'public');
        }

        $recurso->update($data);

        return redirect()->route('recursos.index')
            ->with('success', 'Recurso actualizado exitosamente.');
    }

    public function destroy(Recurso $recurso)
    {
        $recurso->delete();

        return redirect()->route('recursos.index')
            ->with('success', 'Recurso enviado a la papelera.');
    }

    /**
     * Descarga del archivo G-Code del recurso, exclusiva del Administrador.
     * El archivo vive en el disco local (privado); no se expone por URL pública.
     */
    public function descargarGCode(Recurso $recurso)
    {
        abort_if(! $recurso->url_gcode || ! Storage::disk('local')->exists($recurso->url_gcode), 404, 'El archivo G-Code no está disponible.');

        return Storage::disk('local')->download($recurso->url_gcode);
    }

    public function papelera()
    {
        $recursos = Recurso::onlyTrashed()->get();

        return view('recursos.papelera', compact('recursos'));
    }

    public function restore($id)
    {
        $recurso = Recurso::onlyTrashed()->findOrFail($id);
        $recurso->restore();

        return redirect()->route('recursos.papelera')
            ->with('success', 'Recurso restaurado correctamente.');
    }

    public function forceDestroy($id)
    {
        $recurso = Recurso::onlyTrashed()->findOrFail($id);

        if ($recurso->url_imagen) {
            Storage::disk('public')->delete($recurso->url_imagen);
        }

        if ($recurso->url_gcode) {
            Storage::disk('local')->delete($recurso->url_gcode);
        }

        if ($recurso->archivo_stl) {
            Storage::disk('public')->delete($recurso->archivo_stl);
        }

        if ($recurso->archivo_glb) {
            Storage::disk('public')->delete($recurso->archivo_glb);
        }

        $recurso->forceDelete();

        return redirect()->route('recursos.papelera')
            ->with('success', 'Recurso eliminado permanentemente.');
    }
}
