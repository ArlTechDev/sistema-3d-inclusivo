@extends('layouts.admin')

@section('title', 'Instituciones')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-university text-primary mr-2"></i>Gestión de Instituciones</h1>
    <a href="{{ route('instituciones.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Nueva Institución
    </a>
</div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Instituciones</h3>

        @if(auth()->user()->rol === 'Administrador')
            <div class="card-tools">
                <a href="{{ route('instituciones.papelera') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-trash"></i> Papelera
                </a>
                <a href="{{ route('instituciones.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nueva Institución
                </a>
            </div>
        @endif
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Director</th>
                    <th>Logo</th>
                    <th>Documento PDF</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instituciones as $institucion)
                    <tr>
                        <td>{{ $institucion->id }}</td>
                        <td>{{ $institucion->nombre }}</td>
                        <td>{{ $institucion->direccion }}</td>
                        <td>{{ $institucion->telefono }}</td>
                        <td>{{ $institucion->director ?? 'Sin director' }}</td>
                        <td>
                            @if($institucion->logo)
                                <img src="{{ asset('storage/' . $institucion->logo) }}" alt="Logo de {{ $institucion->nombre }}" width="50px" height="50px" class="img-thumbnail" style="object-fit: cover;">
                            @else
                                <span class="text-muted">Sin logo</span>
                            @endif
                        </td>
                        <td>
                            @if($institucion->documento_pdf)
                                <a href="{{ asset('storage/' . $institucion->documento_pdf) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file-pdf"></i> Ver PDF
                                </a>
                            @else
                                <span class="text-muted">Sin documento</span>
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->rol === 'Administrador')
                                <a href="{{ route('instituciones.edit', $institucion->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <form action="{{ route('instituciones.destroy', $institucion->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Enviar a la papelera?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay instituciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            <a href="{{ route('instituciones.pdf') }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('instituciones.excel') }}" class="btn btn-success">
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
