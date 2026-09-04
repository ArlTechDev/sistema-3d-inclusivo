@extends('layouts.admin')

@section('title', 'Editar Recurso Educativo')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-edit text-primary mr-2"></i>Editar Recurso Táctil</h1>
        <a href="{{ route('recursos.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
        </a>
    </div>
@stop

@section('content')
<form action="{{ route('recursos.update', $recurso->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                           id="titulo" value="{{ old('titulo', $recurso->titulo) }}" required aria-required="true">
                    @error('titulo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="categoria_id">Categoría Didáctica</label>
                    <select name="categoria_id" id="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror">
                        <option value="">-- Sin categoría asignada --</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $recurso->categoria_id) == $categoria->id ? 'selected' : '' }}>
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
                          class="form-control @error('descripcion') is-invalid @enderror" required>{{ old('descripcion', $recurso->descripcion) }}</textarea>
                @error('descripcion') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="estado">Estado de Publicación <span class="text-danger">*</span></label>
                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                        <option value="Activo" {{ old('estado', $recurso->estado) === 'Activo' ? 'selected' : '' }}>Activo (Visible en Catálogo)</option>
                        <option value="Inactivo" {{ old('estado', $recurso->estado) === 'Inactivo' ? 'selected' : '' }}>Inactivo (Oculto)</option>
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
                    @if($recurso->url_gcode)
                        <div class="mb-2">
                            <span class="badge badge-success p-2">
                                <i class="fas fa-check-circle mr-1"></i> Archivo G-Code disponible
                            </span>
                            <a href="{{ route('recursos.gcode', $recurso) }}" class="btn btn-xs btn-outline-info ml-2">
                                <i class="fas fa-download"></i> Descargar actual
                            </a>
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" name="url_gcode" class="custom-file-input @error('url_gcode') is-invalid @enderror" id="url_gcode" accept=".gcode,.txt">
                        <label class="custom-file-label" for="url_gcode">Reemplazar archivo .gcode...</label>
                    </div>
                    <small class="form-text text-muted">Dejar vacío para conservar el archivo G-Code actual.</small>
                    @error('url_gcode') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="archivo_stl">Modelo 3D CAD Técnico (.stl)</label>
                    @if($recurso->archivo_stl)
                        <div class="mb-2">
                            <span class="badge badge-info p-2">
                                <i class="fas fa-cube mr-1"></i> Archivo STL asignado ({{ basename($recurso->archivo_stl) }})
                            </span>
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" name="archivo_stl" class="custom-file-input @error('archivo_stl') is-invalid @enderror" id="archivo_stl" accept=".stl">
                        <label class="custom-file-label" for="archivo_stl">Reemplazar archivo .stl...</label>
                    </div>
                    <small class="form-text text-muted">Dejar vacío para conservar el archivo STL actual.</small>
                    @error('archivo_stl') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6 form-group">
                    <label for="gramos_pla">Consumo de Filamento PLA (Gramos) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0.1" name="gramos_pla" id="gramos_pla" 
                               class="form-control @error('gramos_pla') is-invalid @enderror" 
                               value="{{ old('gramos_pla', $recurso->gramos_pla) }}" required>
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
                               value="{{ old('tiempo_minutos', $recurso->tiempo_minutos) }}" required>
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
                    @if($recurso->url_imagen)
                        <div class="mb-2 d-flex align-items-center">
                            <img src="{{ asset('storage/' . $recurso->url_imagen) }}" alt="Imagen actual" 
                                 class="img-thumbnail mr-2" style="max-height: 70px;">
                            <small class="text-muted">Imagen actual cargada</small>
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" name="url_imagen" class="custom-file-input @error('url_imagen') is-invalid @enderror" id="url_imagen" accept=".jpg,.jpeg,.png">
                        <label class="custom-file-label" for="url_imagen">Reemplazar imagen...</label>
                    </div>
                    <small class="form-text text-muted">Dejar vacío para conservar la foto de portada actual.</small>
                    @error('url_imagen') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="archivo_glb">Modelo 3D Interactivo Web (.glb)</label>
                    @if($recurso->archivo_glb)
                        <div class="mb-2">
                            <span class="badge badge-success p-2">
                                <i class="fas fa-cube mr-1"></i> Modelo 3D Web activo ({{ basename($recurso->archivo_glb) }})
                            </span>
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" name="archivo_glb" class="custom-file-input @error('archivo_glb') is-invalid @enderror" id="archivo_glb" accept=".glb">
                        <label class="custom-file-label" for="archivo_glb">Reemplazar archivo .glb...</label>
                    </div>
                    <small class="form-text text-muted">Dejar vacío para conservar el modelo 3D actual.</small>
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
                <i class="fas fa-save mr-1"></i> Actualizar Recurso
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