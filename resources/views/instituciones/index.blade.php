@extends('adminlte::page')

@section('title', 'Instituciones')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Instituciones</h1>
        <div>
            <a href="{{ route('instituciones.papelera') }}" class="btn btn-secondary">Papelera</a>
            <a href="{{ route('instituciones.create') }}" class="btn btn-primary">Nueva institución</a>
        </div>
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
                            <td>
                                <a href="{{ route('instituciones.edit', $institucion) }}" class="btn btn-sm btn-secondary">Editar</a>
                                <form action="{{ route('instituciones.destroy', $institucion) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Enviar esta institución a la papelera?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay instituciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection