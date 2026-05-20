@extends('adminlte::page')

@section('title', 'Catálogo de Recursos')

@section('content_header')
<div class="d-flex justify-content-between">
    <h1>Gestión de Recursos Táctiles</h1>
    <!-- REQUISITO: Nombre del integrante -->
    <h4 class="text-primary">Integrante: [ROSALES MAMANI ARIEL EDSON]</h4>
</div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <a href="{{ route('recursos.create') }}" class="btn btn-primary">Nuevo Recurso</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Gramos PLA</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recursos as $recurso)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $recurso->titulo }}</td>
                        <td>{{ $recurso->gramos_pla }} g</td>
                        <td><span
                                class="badge bg-{{ $recurso->estado == 'Activo' ? 'success' : 'danger' }}">{{ $recurso->estado }}</span>
                        </td>
                        <td>
                            <a href="{{ route('recursos.edit', $recurso->id) }}" class="btn btn-xs btn-info">Editar</a>

                            <form action="{{ route('recursos.destroy', $recurso->id) }}" method="POST"
                                style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"
                                    onclick="return confirm('¿Eliminar?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('recursos.pdf') }}" class="btn btn-danger" target="_blank">
    <i class="fas fa-file-pdf"></i> Exportar PDF
</a>
<a href="{{ route('recursos.excel') }}" class="btn btn-success">
    <i class="fas fa-file-excel"></i> Exportar Excel
</a>
@stop