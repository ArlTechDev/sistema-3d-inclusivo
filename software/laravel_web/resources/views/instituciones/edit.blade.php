@extends('layouts.admin')

@section('title', 'Editar Institución')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-edit text-primary mr-2"></i>Editar Institución</h1>
        <a href="{{ route('instituciones.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
        </a>
    </div>
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-body">
            <form action="{{ route('instituciones.update', $institucion) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" aria-required="true" class="form-control @error('nombre') is-invalid @enderror"
                        value="{{ old('nombre', $institucion->nombre) }}">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" id="direccion" name="direccion"
                        class="form-control @error('direccion') is-invalid @enderror"
                        value="{{ old('direccion', $institucion->direccion) }}">
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" id="telefono" name="telefono"
                        class="form-control @error('telefono') is-invalid @enderror"
                        value="{{ old('telefono', $institucion->telefono) }}">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="director" class="form-label">Director</label>
                    <input type="text" id="director" name="director"
                        class="form-control @error('director') is-invalid @enderror"
                        value="{{ old('director', $institucion->director) }}">
                    @error('director')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Logo</label>
                    <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror"
                        accept="image/*">
                    @if($institucion->logo)
                        <small class="text-muted">Archivo actual: {{ $institucion->logo }}</small>
                    @endif
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="documento_pdf" class="form-label">Documento PDF</label>
                    <input type="file" id="documento_pdf" name="documento_pdf"
                        class="form-control @error('documento_pdf') is-invalid @enderror" accept="application/pdf">
                    @if($institucion->documento_pdf)
                        <small class="text-muted">Archivo actual: {{ $institucion->documento_pdf }}</small>
                    @endif
                    @error('documento_pdf')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Actualizar institución</button>
                <a href="{{ route('instituciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection