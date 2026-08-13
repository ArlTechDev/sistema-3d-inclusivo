@extends('layouts.admin')

@section('title', 'Papelera de Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Papelera de Usuarios</h1>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge bg-{{ $usuario->rol === 'Administrador' ? 'danger' : 'info' }}">
                                    {{ $usuario->rol }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('usuarios.restore', $usuario->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">Restaurar</button>
                                </form>

                                <form action="{{ route('usuarios.forceDestroy', $usuario->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar permanentemente?');">Eliminar Permanente</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay usuarios en papelera.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {{ $usuarios->links() }}
    </div>
@endsection
