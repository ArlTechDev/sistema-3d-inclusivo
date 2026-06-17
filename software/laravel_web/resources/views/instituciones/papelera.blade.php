@extends('adminlte::page')

@section('title', 'Papelera de Instituciones')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Papelera de Instituciones</h1>
        <a href="{{ route('instituciones.index') }}" class="btn btn-secondary">Volver</a>
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
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Director</th>
                        <th>Eliminado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instituciones as $institucion)
                        <tr>
                            <td>{{ $institucion->id }}</td>
                            <td>{{ $institucion->nombre }}</td>
                            <td>{{ $institucion->direccion }}</td>
                            <td>{{ $institucion->telefono }}</td>
                            <td>{{ $institucion->director ?? '-' }}</td>
                            <td>{{ $institucion->deleted_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <form action="{{ route('instituciones.restore', $institucion->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                </form>
                                <form action="{{ route('instituciones.forceDestroy', $institucion->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar permanentemente?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay instituciones en la papelera.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
