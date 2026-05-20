@extends('adminlte::page')

@section('title', 'Crear Recurso Educativo')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Gestión de Recursos Táctiles</h1>
        <!-- REQUISITO: Nombre del integrante -->
        <h4 class="text-primary">Integrante: [ROSALES MAMANI ARIEL EDSON]</h4>
    </div>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Formulario de Registro</h3>
    </div>
    <form action="{{ route('recursos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            
            <div class="form-group">
                <label for="titulo">Título del Recurso</label>
                <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo') }}" placeholder="Ej. Mapa de Bolivia Braille">
                @error('titulo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="url_imagen">Imagen (JPG/PNG - Máx 2MB)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="url_imagen" class="custom-file-input @error('url_imagen') is-invalid @enderror" id="url_imagen" accept=".jpg,.jpeg,.png">
                            <label class="custom-file-label" for="url_imagen">Seleccionar archivo...</label>
                        </div>
                    </div>
                    @error('url_imagen') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="url_gcode">Archivo G-code</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="url_gcode" class="custom-file-input @error('url_gcode') is-invalid @enderror" id="url_gcode" accept=".gcode,.txt">
                            <label class="custom-file-label" for="url_gcode">Seleccionar archivo...</label>
                        </div>
                    </div>
                    @error('url_gcode') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
                <div class="col-md-3 form-group">
                    <label for="gramos_pla">Gramos de PLA</label>
                    <input type="number" step="0.01" name="gramos_pla" class="form-control @error('gramos_pla') is-invalid @enderror" value="{{ old('gramos_pla') }}">
                    @error('gramos_pla') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="tiempo_minutos">Tiempo (Minutos)</label>
                    <input type="number" name="tiempo_minutos" class="form-control @error('tiempo_minutos') is-invalid @enderror" value="{{ old('tiempo_minutos') }}">
                    @error('tiempo_minutos') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="fecha_creacion">Fecha</label>
                    <input type="date" name="fecha_creacion" class="form-control @error('fecha_creacion') is-invalid @enderror" value="{{ old('fecha_creacion') }}">
                    @error('fecha_creacion') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" class="form-control @error('estado') is-invalid @enderror">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Guardar Recurso</button>
            <a href="{{ route('recursos.index') }}" class="btn btn-default">Cancelar</a>
        </div>
    </form>
</div>
@stop