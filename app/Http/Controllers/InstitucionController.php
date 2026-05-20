<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Illuminate\Http\Request;

class InstitucionController extends Controller
{
    private array $rules = [
        'nombre'    => 'required|string|min:3|max:100',
        'direccion' => 'required|string|min:5|max:200',
        'telefono'  => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
        'director'  => 'nullable|string|max:100',
    ];

    private array $messages = [
        'nombre.required'    => 'El nombre de la institución es obligatorio.',
        'nombre.min'         => 'El nombre debe tener al menos 3 caracteres.',
        'direccion.required' => 'La dirección es obligatoria.',
        'direccion.min'      => 'La dirección debe tener al menos 5 caracteres.',
        'telefono.required'  => 'El teléfono es obligatorio.',
        'telefono.regex'     => 'El teléfono solo puede contener números, espacios, +, guiones o paréntesis.',
    ];

    public function index()
    {
        $instituciones = Institucion::all();
        return view('instituciones.index', compact('instituciones'));
    }

    public function create()
    {
        return view('instituciones.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules, $this->messages);

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
        $institucion->forceDelete();

        return redirect()->route('instituciones.papelera')
            ->with('success', 'Institución eliminada permanentemente.');
    }
}