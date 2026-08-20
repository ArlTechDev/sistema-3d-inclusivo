@extends('layouts.admin')

@section('title', 'Solicitudes de Impresión')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-tasks text-primary mr-2"></i>Gestión de Solicitudes de Impresión</h1>
</div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Solicitudes</h3>
        <div class="card-tools">
            <a href="{{ route('pedidos.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('pedidos.excel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('pedidos.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="estado" class="form-control form-control-sm">
                    <option value="">— Todos los estados —</option>
                    @foreach([
                        \App\Models\Pedido::ESTADO_PENDIENTE,
                        \App\Models\Pedido::ESTADO_APROBADO,
                        \App\Models\Pedido::ESTADO_EN_IMPRESION,
                        \App\Models\Pedido::ESTADO_COMPLETADO,
                        \App\Models\Pedido::ESTADO_RECHAZADO,
                    ] as $estado)
                        <option value="{{ $estado }}" @selected(request('estado') === $estado)>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="institucion_id" class="form-control form-control-sm">
                    <option value="">— Todas las instituciones —</option>
                    @foreach($instituciones as $institucion)
                        <option value="{{ $institucion->id }}" @selected(request('institucion_id') == $institucion->id)>
                            {{ $institucion->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filtrar</button>
                <a href="{{ route('pedidos.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
            </div>
        </form>

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Solicitante</th>
                    <th>Institución</th>
                    <th>Fecha</th>
                    <th>Gramos PLA</th>
                    <th>Costo (Bs)</th>
                    <th>Estado</th>
                    <th style="width: 280px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->user?->name ?? '-' }}</td>
                        <td>{{ $pedido->institucion?->nombre ?? '-' }}</td>
                        <td>{{ $pedido->fecha_solicitud?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $pedido->total_gramos_pla }}</td>
                        <td>{{ number_format($pedido->costo_total, 2) }}</td>
                        <td>
                            @php
                                $clase = match ($pedido->estado) {
                                    \App\Models\Pedido::ESTADO_PENDIENTE => 'warning',
                                    \App\Models\Pedido::ESTADO_APROBADO => 'primary',
                                    \App\Models\Pedido::ESTADO_EN_IMPRESION => 'info',
                                    \App\Models\Pedido::ESTADO_COMPLETADO => 'success',
                                    \App\Models\Pedido::ESTADO_RECHAZADO => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $clase }}">{{ $pedido->estado }}</span>
                            @if($pedido->motivo_rechazo)
                                <small class="d-block text-danger">{{ $pedido->motivo_rechazo }}</small>
                            @endif
                        </td>
                        <td>
                            @if($pedido->estado !== \App\Models\Pedido::ESTADO_COMPLETADO && $pedido->estado !== \App\Models\Pedido::ESTADO_RECHAZADO)
                                <form method="POST" action="{{ route('pedidos.update', $pedido) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="estado" class="form-select form-select-sm d-inline-block w-auto"
                                            onchange="this.form.submit()">
                                        <option value="">Cambiar estado…</option>
                                        @foreach(\App\Models\Pedido::TRANSICIONES[$pedido->estado] ?? [] as $estado)
                                            @if($estado !== \App\Models\Pedido::ESTADO_RECHAZADO)
                                                <option value="{{ $estado }}">{{ $estado }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </form>

                                <form method="POST" action="{{ route('pedidos.rechazar', $pedido) }}"
                                      class="d-inline"
                                      onsubmit="const m=prompt('Motivo del rechazo (obligatorio):');if(!m)return false;this.querySelector('[name=motivo_rechazo]').value=m;return true;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="motivo_rechazo" value="">
                                    <button class="btn btn-danger btn-sm" title="Rechazar pedido">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('pedidos.gcode', $pedido) }}" class="btn btn-dark btn-sm" title="Descargar G-Code">
                                <i class="fas fa-file-code"></i> G-Code
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay solicitudes registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
