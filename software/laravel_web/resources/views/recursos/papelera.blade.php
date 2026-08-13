@extends('layouts.admin')

@section('title', 'Papelera de Recursos 3D')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Papelera de Recursos 3D</h1>
        <a href="{{ route('recursos.index') }}" class="btn btn-secondary">Volver a recursos</a>
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
                        <th>Título</th>
                        <th>Gramos PLA</th>
                        <th>Tiempo</th>
                        <th>Estado</th>
                        <th>Eliminado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recursos as $recurso)
                        <tr>
                            <td>{{ $recurso->id }}</td>
                            <td>{{ $recurso->titulo }}</td>
                            <td>{{ $recurso->gramos_pla }}</td>
                            <td>{{ $recurso->tiempo_minutos }}</td>
                            <td>{{ $recurso->estado }}</td>
                            <td>{{ $recurso->deleted_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <form action="{{ route('recursos.restore', $recurso->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                </form>
                                <form action="{{ route('recursos.forceDestroy', $recurso->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar permanentemente este recurso?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay recursos en la papelera.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
