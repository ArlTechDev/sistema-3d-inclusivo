@extends('layouts.admin')

@section('title', 'Configuración del Sistema')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-cogs text-primary mr-2"></i>Configuración de Costos y Parámetros</h1>
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

    <div class="row">
        <!-- Formulario de Configuración -->
        <div class="col-md-7">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-sliders-h mr-1"></i> Parámetros de Fabricación y Costos
                    </h3>
                </div>
                <form action="{{ route('configuracion.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="moneda_simbolo">Moneda del Sistema <span class="text-danger">*</span></label>
                            <input type="text" name="moneda_simbolo" id="moneda_simbolo" 
                                   class="form-control @error('moneda_simbolo') is-invalid @enderror" 
                                   value="{{ old('moneda_simbolo', $moneda) }}" placeholder="Ej. Bs o BOB" required>
                            <small class="form-text text-muted">Símbolo monetario usado en pedidos, reportes PDF y hojas de cálculo Excel.</small>
                            @error('moneda_simbolo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="precio_gramo_pla">Costo por Gramo de Filamento PLA <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $moneda }}</span>
                                </div>
                                <input type="number" step="0.001" min="0.001" name="precio_gramo_pla" id="precio_gramo_pla" 
                                       class="form-control @error('precio_gramo_pla') is-invalid @enderror" 
                                       value="{{ old('precio_gramo_pla', $precioGramo) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">/ gramo</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                💡 <b>Ejemplo:</b> Si el rollo de filamento PLA reciclado de 1 kg (1000 g) cuesta 150 {{ $moneda }}, el costo es <b>0.15 {{ $moneda }}/g</b>.
                            </small>
                            @error('precio_gramo_pla') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="gramos_por_celda_braille">Consumo de Filamento por Celda Braille en Relieve <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.001" min="0" name="gramos_por_celda_braille" id="gramos_por_celda_braille" 
                                       class="form-control @error('gramos_por_celda_braille') is-invalid @enderror" 
                                       value="{{ old('gramos_por_celda_braille', $gramosPorCelda) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">gramos / celda</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                Consumo adicional de filamento extruido para depositar los puntos táctiles semiesféricos según la Norma ONCE.
                            </small>
                            @error('gramos_por_celda_braille') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('pedidos.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left mr-1"></i> Volver a Solicitudes
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tarjeta Informativa / Guía Metrológica -->
        <div class="col-md-5">
            <div class="card card-info card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-calculator text-info mr-1"></i> Fórmula de Estimación de Costos
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">El costo de cada pedido se calcula automáticamente mediante la fórmula:</p>
                    <div class="bg-light p-3 rounded mb-3 border">
                        <code>Costo = (Gramos Base + Celdas × Gramos Celda) × Precio/g × Cantidad</code>
                    </div>
                    <h5 class="font-weight-bold text-sm text-uppercase text-secondary">Parámetros Clave:</h5>
                    <ul class="text-muted pl-3 mb-0">
                        <li><b>Gramos Base:</b> El peso de la placa o recurso definido en el catálogo.</li>
                        <li><b>Celdas Braille:</b> Número de caracteres traducidos según el Código Braille Español.</li>
                        <li><b>PLA Reciclado:</b> Material recuperado de e-waste para economía circular y bajo costo.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop
