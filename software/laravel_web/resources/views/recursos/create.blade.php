@extends('layouts.admin')

@section('title', 'Crear Recurso Educativo')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-cubes text-primary mr-2"></i>Nuevo Recurso Táctil</h1>
        <a href="{{ route('recursos.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
        </a>
    </div>
@stop

@section('content')
<form action="{{ route('recursos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Bloque 1: Información General y Didáctica -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-book-open mr-1"></i> 1. Información General y Didáctica
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 form-group">
                    <label for="titulo">Título del Recurso <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" 
                           id="titulo" value="{{ old('titulo') }}" placeholder="Ej. Ficha Táctil Alfabeto Braille" required aria-required="true">
                    @error('titulo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="categoria_id">Categoría Didáctica</label>
                    <select name="categoria_id" id="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror">
                        <option value="">-- Sin categoría asignada --</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción Pedagógica <span class="text-danger">*</span></label>
                <textarea name="descripcion" id="descripcion" rows="3" 
                          class="form-control @error('descripcion') is-invalid @enderror" 
                          placeholder="Describe el objetivo didáctico, contenido táctil y grupo de edad..." required>{{ old('descripcion') }}</textarea>
                @error('descripcion') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="estado">Estado de Publicación <span class="text-danger">*</span></label>
                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                        <option value="Activo" {{ old('estado', 'Activo') === 'Activo' ? 'selected' : '' }}>Activo (Visible en Catálogo)</option>
                        <option value="Inactivo" {{ old('estado') === 'Inactivo' ? 'selected' : '' }}>Inactivo (Oculto)</option>
                    </select>
                    @error('estado') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Bloque 2: Archivos para Impresión y Producción 3D (Para el Operador) -->
    <div class="card card-outline card-info shadow-sm mb-4">
        <div class="card-header bg-white">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-print text-info mr-1"></i> 2. Parámetros Técnicos y Archivos de Fabricación
            </h3>
            <span class="badge badge-info float-right">Para Operador de Impresora 3D / CNC</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="url_gcode">Archivo de Código Máquina (.gcode)</label>
                    <div class="custom-file">
                        <input type="file" name="url_gcode" class="custom-file-input @error('url_gcode') is-invalid @enderror" id="url_gcode" accept=".gcode,.txt">
                        <label class="custom-file-label" for="url_gcode">Seleccionar .gcode...</label>
                    </div>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle text-info"></i> Archivo que el operador descargará directamente para mandar a imprimir en la máquina.
                    </small>
                    @error('url_gcode') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="archivo_stl">Modelo 3D CAD Técnico (.stl)</label>
                    <div class="custom-file">
                        <input type="file" name="archivo_stl" class="custom-file-input @error('archivo_stl') is-invalid @enderror" id="archivo_stl" accept=".stl">
                        <label class="custom-file-label" for="archivo_stl">Seleccionar archivo .stl...</label>
                    </div>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle text-info"></i> Geometría CAD exportable para software de laminado (PrusaSlicer).
                    </small>
                    @error('archivo_stl') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6 form-group">
                    <label for="gramos_pla">Consumo de Filamento PLA (Gramos) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0.1" name="gramos_pla" id="gramos_pla" 
                               class="form-control @error('gramos_pla') is-invalid @enderror" 
                               value="{{ old('gramos_pla', '10.00') }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text">gramos</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">Base para calcular el costo total de impresión en cada pedido.</small>
                    @error('gramos_pla') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="tiempo_minutos">Tiempo Estimado de Impresión <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" min="1" name="tiempo_minutos" id="tiempo_minutos" 
                               class="form-control @error('tiempo_minutos') is-invalid @enderror" 
                               value="{{ old('tiempo_minutos', '30') }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text">minutos</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">Duración aproximada para estimar la cola de impresión.</small>
                    @error('tiempo_minutos') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Bloque 3: Visualización Web y Multimedia (Para Docentes/Solicitantes) -->
    <div class="card card-outline card-success shadow-sm mb-4">
        <div class="card-header bg-white">
            <h3 class="card-title font-weight-bold text-dark">
                <i class="fas fa-eye text-success mr-1"></i> 3. Visualización Web y Multimedia
            </h3>
            <span class="badge badge-success float-right">Para Catálogo Escolar</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="url_imagen">Imagen de Portada 2D (JPG / PNG)</label>
                    <div class="custom-file">
                        <input type="file" name="url_imagen" class="custom-file-input @error('url_imagen') is-invalid @enderror" id="url_imagen" accept=".jpg,.jpeg,.png">
                        <label class="custom-file-label" for="url_imagen">Seleccionar imagen...</label>
                    </div>
                    <small class="form-text text-muted">Foto estática para visualización rápida y ligera en tarjetas del catálogo.</small>
                    @error('url_imagen') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="archivo_glb">Modelo 3D Interactivo Web (.glb)</label>
                    <div class="custom-file">
                        <input type="file" name="archivo_glb" class="custom-file-input @error('archivo_glb') is-invalid @enderror" id="archivo_glb" accept=".glb">
                        <label class="custom-file-label" for="archivo_glb">Seleccionar archivo .glb...</label>
                    </div>
                    <small class="form-text text-muted">Permite a los docentes explorar el relieve táctil en 360° en el navegador.</small>
                    @error('archivo_glb') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-body d-flex justify-content-between align-items-center">
            <a href="{{ route('recursos.index') }}" class="btn btn-default">
                <i class="fas fa-times mr-1"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-save mr-1"></i> Guardar Recurso
            </button>
        </div>
    </div>
</form>
@stop

@section('js')
<script>
    // Actualizar nombre de archivo en inputs custom-file de Bootstrap/AdminLTE
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : 'Seleccionar archivo...';
            var label = e.target.nextElementSibling;
            if (label) {
                label.innerText = fileName;
            }
        });
    });
</script>
@stop