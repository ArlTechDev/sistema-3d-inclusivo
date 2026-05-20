@extends('adminlte::page')

@section('title', 'Crear Institución')

@section('content_header')
    <h1>Crear Institución</h1>
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-body">
            <form action="{{ route('instituciones.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" id="direccion" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}">
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="director" class="form-label">Director</label>
                    <input type="text" id="director" name="director" class="form-control @error('director') is-invalid @enderror" value="{{ old('director') }}">
                    @error('director')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Guardar institución</button>
                <a href="{{ route('instituciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection