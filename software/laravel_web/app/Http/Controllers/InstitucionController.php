<?php

namespace App\Http\Controllers;

use App\Exports\InstitucionesExport;
use App\Http\Requests\StoreInstitucionRequest;
use App\Http\Requests\UpdateInstitucionRequest;
use App\Models\Institucion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InstitucionController extends Controller
{
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

    public function store(StoreInstitucionRequest $request)
    {
        $data = $request->validated();

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

    public function update(UpdateInstitucionRequest $request, Institucion $institucion)
    {
        $data = $request->validated();

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
