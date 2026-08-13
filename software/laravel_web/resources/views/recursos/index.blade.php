@extends('layouts.admin')

@section('title', 'Catálogo de Recursos')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Gestión de Recursos Táctiles</h1>
    <h4 class="text-primary mb-0">Integrante: [ROSALES MAMANI ARIEL EDSON]</h4>
</div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Recursos</h3>

        @if(auth()->user()->rol === 'Administrador')
            <div class="card-tools">
                <a href="{{ route('recursos.papelera') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-trash"></i> Papelera
                </a>
                <a href="{{ route('recursos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Recurso
                </a>
            </div>
        @endif
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Imagen</th>
                    <th>Gramos PLA</th>
                    <th>Estado</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recursos as $recurso)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $recurso->titulo }}</td>
                        <td>
                            @if($recurso->url_imagen)
                                <img src="{{ asset('storage/' . $recurso->url_imagen) }}" alt="Imagen de {{ $recurso->titulo }}" width="50px" height="50px" class="img-thumbnail" style="object-fit: cover;">
                            @else
                                <span class="text-muted">Sin imagen</span>
                            @endif
                        </td>
                        <td>{{ $recurso->gramos_pla }} g</td>
                        <td>
                            <span class="badge bg-{{ $recurso->estado == 'Activo' ? 'success' : 'danger' }}">
                                {{ $recurso->estado }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('pedidos.create', ['recurso' => $recurso->id]) }}"
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-print"></i> Solicitar Impresión
                            </a>

                            @if(auth()->user()->rol === 'Administrador')
                                <a href="{{ route('recursos.edit', $recurso->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <form action="{{ route('recursos.destroy', $recurso->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay recursos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            <a href="{{ route('recursos.pdf') }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('recursos.excel') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('.alert-success').delay(3000).fadeOut('slow');
    });
</script>
@stop
