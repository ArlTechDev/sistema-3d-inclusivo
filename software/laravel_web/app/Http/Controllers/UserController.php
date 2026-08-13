<?php

namespace App\Http\Controllers;

use App\Exports\UsuariosExport;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    public function exportarPdf()
    {
        $usuarios = User::all();
        $pdf = Pdf::loadView('usuarios.pdf', compact('usuarios'));

        return $pdf->stream('usuarios.pdf');
    }

    public function exportarExcel()
    {
        return Excel::download(new UsuariosExport, 'usuarios.xlsx');
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(StoreUsuarioRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = Storage::disk('public')
                ->putFile('usuarios/fotos', $request->file('foto_perfil'));
        }

        User::create($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(UpdateUsuarioRequest $request, User $usuario)
    {
        $data = $request->validated();

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('foto_perfil')) {
            $this->eliminarArchivo($usuario->foto_perfil);

            $data['foto_perfil'] = Storage::disk('public')
                ->putFile('usuarios/fotos', $request->file('foto_perfil'));
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario enviado a papelera.');
    }

    public function papelera()
    {
        $usuarios = User::onlyTrashed()->paginate(10);

        return view('usuarios.papelera', compact('usuarios'));
    }

    public function restore($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('usuarios.papelera')
            ->with('success', 'Usuario restaurado exitosamente.');
    }

    public function forceDestroy($id)
    {
        $usuario = User::onlyTrashed()->findOrFail($id);

        $this->eliminarArchivo($usuario->foto_perfil);

        $usuario->forceDelete();

        return redirect()->route('usuarios.papelera')
            ->with('success', 'Usuario eliminado permanentemente.');
    }

    private function eliminarArchivo(?string $ruta): void
    {
        if ($ruta && Storage::disk('public')->exists($ruta)) {
            Storage::disk('public')->delete($ruta);
        }
    }
}
