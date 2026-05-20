<?php

namespace App\Http\Controllers;

use App\Exports\InstitucionesExport;
use App\Models\Institucion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InstitucionController extends Controller
{
    private array $rules = [
        'nombre'    => 'required|string|min:3|max:100',
        'direccion' => 'required|string|min:5|max:200',
        'telefono'  => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
        'director'  => 'nullable|string|max:100',
        'logo' => 'nullable|image|max:2048',
        'documento_pdf' => 'nullable|file|mimes:pdf|max:4096',
    ];

    private array $messages = [
        'nombre.required'    => 'El nombre de la institución es obligatorio.',
        'nombre.min'         => 'El nombre debe tener al menos 3 caracteres.',
        'direccion.required' => 'La dirección es obligatoria.',
        'direccion.min'      => 'La dirección debe tener al menos 5 caracteres.',
        'telefono.required'  => 'El teléfono es obligatorio.',
        'telefono.regex'     => 'El teléfono solo puede contener números, espacios, +, guiones o paréntesis.',
        'logo.image'         => 'El logo debe ser una imagen.',
        'logo.max'           => 'El logo no puede superar 2 MB.',
        'documento_pdf.mimes' => 'El documento debe ser un archivo PDF.',
        'documento_pdf.max'  => 'El documento PDF no puede superar 4 MB.',
    ];

    public function index()
    {
        $instituciones = Institucion::all();
        return view('instituciones.index', compact('instituciones'));
    }

    public function exportarPdf()
    {
        $instituciones = Institucion::all();
        $pdf = Pdf::loadView('instituciones.pdf', compact('instituciones'));

        return $pdf->stream('instituciones.pdf');
    }

    public function exportarExcel()
    {
        return Excel::download(new InstitucionesExport, 'instituciones.xlsx');
    }

    public function create()
    {
        return view('instituciones.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules, $this->messages);

        if ($request->hasFile('logo')) {
            $data['logo'] = Storage::disk('public')
                ->putFile('instituciones/logos', $request->file('logo'));
        }

        if ($request->hasFile('documento_pdf')) {
            $data['documento_pdf'] = Storage::disk('public')
                ->putFile('instituciones/documentos', $request->file('documento_pdf'));
        }

        Institucion::create($data);

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución creada exitosamente.');
    }

    public function edit(Institucion $institucion)
    {
        return view('instituciones.edit', compact('institucion'));
    }

    public function update(Request $request, Institucion $institucion)
    {
        $data = $request->validate($this->rules, $this->messages);

        if ($request->hasFile('logo')) {
            $this->eliminarArchivo($institucion->logo);

            $data['logo'] = Storage::disk('public')
                ->putFile('instituciones/logos', $request->file('logo'));
        }

        if ($request->hasFile('documento_pdf')) {
            $this->eliminarArchivo($institucion->documento_pdf);

            $data['documento_pdf'] = Storage::disk('public')
                ->putFile('instituciones/documentos', $request->file('documento_pdf'));
        }

        $institucion->update($data);

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución actualizada exitosamente.');
    }

    public function destroy(Institucion $institucion)
    {
        $institucion->delete();

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución enviada a la papelera.');
    }

    public function papelera()
    {
        $instituciones = Institucion::onlyTrashed()->get();
        return view('instituciones.papelera', compact('instituciones'));
    }

    public function restore($id)
    {
        $institucion = Institucion::onlyTrashed()->findOrFail($id);
        $institucion->restore();

        return redirect()->route('instituciones.papelera')
            ->with('success', 'Institución restaurada correctamente.');
    }

    public function forceDestroy($id)
    {
        $institucion = Institucion::onlyTrashed()->findOrFail($id);

        $this->eliminarArchivo($institucion->logo);
        $this->eliminarArchivo($institucion->documento_pdf);

        $institucion->forceDelete();

        return redirect()->route('instituciones.papelera')
            ->with('success', 'Institución eliminada permanentemente.');
    }

    private function eliminarArchivo(?string $ruta): void
    {
        if ($ruta && Storage::disk('public')->exists($ruta)) {
            Storage::disk('public')->delete($ruta);
        }
    }
}
