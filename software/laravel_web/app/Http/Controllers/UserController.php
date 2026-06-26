<?php

namespace App\Http\Controllers;

use App\Exports\UsuariosExport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    private array $reglas = [
        'name' => 'required|string|max:100',
        'email' => 'required|email',
        'password' => 'nullable|string|min:8|confirmed',
        'rol' => 'required|in:Administrador,Solicitante',
        'foto_perfil' => 'nullable|image|max:2048',
    ];

    private array $mensajes = [
        'name.required' => 'El nombre es obligatorio.',
        'name.string' => 'El nombre debe ser texto.',
        'name.max' => 'El nombre no puede exceder 100 caracteres.',
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'El email debe ser válido.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'rol.required' => 'El rol es obligatorio.',
        'rol.in' => 'El rol no es válido.',
        'foto_perfil.image' => 'La foto de perfil debe ser una imagen.',
        'foto_perfil.max' => 'La foto de perfil no puede superar 2 MB.',
    ];

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

    public function store(Request $request)
    {
        $reglas = array_merge($this->reglas, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = $request->validate($reglas, $this->mensajes);

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

    public function update(Request $request, User $usuario)
    {
        $reglas = array_merge($this->reglas, [
            'email' => 'required|email|unique:users,email,' . $usuario->id,
        ]);

        $data = $request->validate($reglas, $this->mensajes);

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
