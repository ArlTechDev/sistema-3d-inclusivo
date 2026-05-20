<?php

namespace App\Http\Controllers;

use App\Exports\RecursosExport;
use App\Models\Recurso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class RecursoController extends Controller
{
    private array $reglas = [
        'titulo'          => 'required|string|min:5|max:150',
        'descripcion'     => 'required|string|min:10',
        'gramos_pla'      => 'required|numeric|min:0.1',
        'tiempo_minutos'  => 'required|integer|min:1',
        'fecha_creacion'  => 'required|date',
        'estado'          => 'required|in:Activo,Inactivo',
        'url_imagen'      => 'nullable|image|max:2048',
        'url_gcode'       => 'nullable|file|mimes:gcode,txt',
    ];

    private array $mensajes = [
        'titulo.required'         => 'El título es obligatorio.',
        'titulo.min'              => 'El título debe tener al menos 5 caracteres.',
        'titulo.max'              => 'El título no puede superar 150 caracteres.',
        'descripcion.required'    => 'La descripción es obligatoria.',
        'descripcion.min'         => 'La descripción debe tener al menos 10 caracteres.',
        'gramos_pla.required'     => 'Los gramos de PLA son obligatorios.',
        'gramos_pla.numeric'      => 'Los gramos de PLA deben ser un valor numérico.',
        'gramos_pla.min'          => 'Los gramos de PLA deben ser mayor a 0.',
        'tiempo_minutos.required' => 'El tiempo en minutos es obligatorio.',
        'tiempo_minutos.integer'  => 'El tiempo debe ser un número entero.',
        'tiempo_minutos.min'      => 'El tiempo debe ser mayor a 0.',
        'fecha_creacion.required' => 'La fecha es obligatoria.',
        'fecha_creacion.date'     => 'Debe ingresar una fecha válida.',
        'estado.required'         => 'El estado es obligatorio.',
        'estado.in'               => 'El estado seleccionado no es válido.',
    ];

    public function index()
    {
        $recursos = Recurso::all();
        return view('recursos.index', compact('recursos'));
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
        return view('recursos.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->reglas, $this->mensajes);

        $data = $request->except(['url_imagen', 'url_gcode']);

        if ($request->hasFile('url_imagen')) {
            $data['url_imagen'] = $request->file('url_imagen')
                ->store('recursos/images', 'public');
        }

        if ($request->hasFile('url_gcode')) {
            $data['url_gcode'] = $request->file('url_gcode')
                ->store('recursos/gcode', 'public');
        }

        Recurso::create($data);

        return redirect()->route('recursos.index')
            ->with('success', 'Recurso educativo registrado exitosamente.');
    }

    public function edit(Recurso $recurso)
    {
        return view('recursos.edit', compact('recurso'));
    }

    public function update(Request $request, Recurso $recurso)
    {
        $request->validate($this->reglas, $this->mensajes);

        $data = $request->except(['url_imagen', 'url_gcode']);

        if ($request->hasFile('url_imagen')) {
            if ($recurso->url_imagen) {
                Storage::disk('public')->delete($recurso->url_imagen);
            }

            $data['url_imagen'] = $request->file('url_imagen')
                ->store('recursos/images', 'public');
        }

        if ($request->hasFile('url_gcode')) {
            if ($recurso->url_gcode) {
                Storage::disk('public')->delete($recurso->url_gcode);
            }

            $data['url_gcode'] = $request->file('url_gcode')
                ->store('recursos/gcode', 'public');
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
        $recurso = Recurso::withTrashed()->findOrFail($id);

        if ($recurso->url_imagen) {
            Storage::disk('public')->delete($recurso->url_imagen);
        }

        if ($recurso->url_gcode) {
            Storage::disk('public')->delete($recurso->url_gcode);
        }

        $recurso->forceDelete();

        return redirect()->route('recursos.papelera')
            ->with('success', 'Recurso eliminado permanentemente.');
    }
}