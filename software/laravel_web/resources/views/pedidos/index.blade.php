@extends('layouts.admin')

@section('title', 'Solicitudes de Impresión')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-tasks text-primary mr-2"></i>Gestión de Solicitudes de Impresión</h1>
    <div>
        <a href="{{ route('configuracion.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
            <i class="fas fa-cogs mr-1"></i> Costos y Parámetros
        </a>
    </div>
</div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
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

<!-- Tarjetas Resumen / KPIs de Producción -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning shadow-sm">
            <div class="inner">
                <h3>{{ $stats['pendientes'] ?? 0 }}</h3>
                <p>Pendientes por Aprobar</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('pedidos.index', ['estado' => 'Pendiente']) }}" class="small-box-footer">
                Filtrar pendientes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info shadow-sm">
            <div class="inner">
                <h3>{{ $stats['en_impresion'] ?? 0 }}</h3>
                <p>En Impresora 3D (Activos)</p>
            </div>
            <div class="icon">
                <i class="fas fa-print"></i>
            </div>
            <a href="{{ route('pedidos.index', ['estado' => 'En impresión']) }}" class="small-box-footer">
                Filtrar activos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success shadow-sm">
            <div class="inner">
                <h3>{{ $stats['completados'] ?? 0 }}</h3>
                <p>Piezas Fabricadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-double"></i>
            </div>
            <a href="{{ route('pedidos.index', ['estado' => 'Completado']) }}" class="small-box-footer">
                Filtrar completados <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary shadow-sm">
            <div class="inner">
                <h3>{{ number_format(($stats['total_gramos'] ?? 0) / 1000, 2) }} <sup style="font-size: 16px">kg</sup></h3>
                <p>PLA Reciclado ({{ $stats['moneda'] ?? 'Bs' }} {{ number_format($stats['total_costo'] ?? 0, 2) }})</p>
            </div>
            <div class="icon">
                <i class="fas fa-recycle"></i>
            </div>
            <a href="{{ route('pedidos.excel') }}" class="small-box-footer">
                Descargar reporte total <i class="fas fa-file-excel"></i>
            </a>
        </div>
    </div>
</div>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-list mr-1"></i> Cola de Fabricación y Solicitudes
        </h3>
        <div class="card-tools">
            <a href="{{ route('pedidos.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
            </a>
            <a href="{{ route('pedidos.excel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Exportar Excel
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Barra de Filtros -->
        <form method="GET" action="{{ route('pedidos.index') }}" class="row g-2 mb-4 bg-light p-3 rounded border">
            <div class="col-md-3">
                <label class="text-xs text-muted text-uppercase mb-1">Estado</label>
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
                <label class="text-xs text-muted text-uppercase mb-1">Institución</label>
                <select name="institucion_id" class="form-control form-control-sm">
                    <option value="">— Todas las instituciones —</option>
                    @foreach($instituciones as $institucion)
                        <option value="{{ $institucion->id }}" @selected(request('institucion_id') == $institucion->id)>
                            {{ $institucion->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="text-xs text-muted text-uppercase mb-1">Fecha</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter"></i> Filtrar</button>
                <a href="{{ route('pedidos.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Solicitante</th>
                        <th>Recurso y Relieve</th>
                        <th>Institución</th>
                        <th>Fecha</th>
                        <th>Consumo PLA</th>
                        <th>Costo ({{ $stats['moneda'] ?? 'Bs' }})</th>
                        <th>Estado</th>
                        <th style="width: 260px;">Acciones Operador</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr>
                            <td class="font-weight-bold text-muted">{{ $pedido->id }}</td>
                            <td>
                                <b>{{ $pedido->user?->name ?? 'Usuario' }}</b>
                                <small class="text-muted d-block">{{ $pedido->user?->email }}</small>
                            </td>
                            <td>
                                @php
                                    $detalle = $pedido->detalles->first();
                                    $recurso = $detalle?->recurso;
                                @endphp
                                @if($recurso)
                                    <b>{{ $recurso->titulo }}</b>
                                    <span class="badge badge-secondary ml-1">×{{ $detalle->cantidad }}</span>
                                    @if($pedido->gcode_path && str_contains($pedido->gcode_path, 'pedidos/gcode/'))
                                        <span class="badge badge-info d-block mt-1" style="width: fit-content;">
                                            <i class="fas fa-braille mr-1"></i> Texto Braille Generado
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted">Recurso no disponible</span>
                                @endif
                            </td>
                            <td>
                                @if($pedido->institucion)
                                    <span class="badge badge-light border">{{ $pedido->institucion->nombre }}</span>
                                @else
                                    <span class="text-muted font-italic">Particular</span>
                                @endif
                            </td>
                            <td>{{ $pedido->fecha_solicitud?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                <b>{{ $pedido->total_gramos_pla }} g</b>
                            </td>
                            <td>
                                <span class="text-success font-weight-bold">
                                    {{ $stats['moneda'] ?? 'Bs' }} {{ number_format($pedido->costo_total, 2) }}
                                </span>
                            </td>
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
                                <span class="badge badge-{{ $clase }} p-2">{{ $pedido->estado }}</span>
                                @if($pedido->motivo_rechazo)
                                    <small class="d-block text-danger font-italic mt-1">Motivo: {{ $pedido->motivo_rechazo }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($pedido->estado !== \App\Models\Pedido::ESTADO_COMPLETADO && $pedido->estado !== \App\Models\Pedido::ESTADO_RECHAZADO)
                                        <form method="POST" action="{{ route('pedidos.update', $pedido) }}" class="d-inline mr-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="estado" class="form-control form-control-sm d-inline-block w-auto"
                                                    onchange="this.form.submit()">
                                                <option value="">Avanzar estado…</option>
                                                @foreach(\App\Models\Pedido::TRANSICIONES[$pedido->estado] ?? [] as $estado)
                                                    @if($estado !== \App\Models\Pedido::ESTADO_RECHAZADO)
                                                        <option value="{{ $estado }}">{{ $estado }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </form>

                                        <form method="POST" action="{{ route('pedidos.rechazar', $pedido) }}"
                                              class="d-inline mr-1"
                                              onsubmit="const m=prompt('Motivo del rechazo (obligatorio):');if(!m)return false;this.querySelector('[name=motivo_rechazo]').value=m;return true;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="motivo_rechazo" value="">
                                            <button class="btn btn-outline-danger btn-sm" title="Rechazar solicitud">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('pedidos.gcode', $pedido) }}" class="btn btn-dark btn-sm" title="Descargar G-Code máquina">
                                        <i class="fas fa-download mr-1"></i> G-Code
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No hay solicitudes registradas con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
