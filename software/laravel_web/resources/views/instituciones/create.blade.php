@extends('layouts.admin')

@section('title', 'Crear Institución')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Instituciones</h1>

        <!-- REQUISITO: Nombre del integrante -->
        <h5 class="text-primary mb-0">
            Integrante: [AGUILAR CASTELLON CRISTHIAN AGUILAR]
        </h5>
    </div>
@stop

@section('content')

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Formulario de Registro</h3>
    </div>

    <form action="{{ route('instituciones.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="card-body">

            {{-- Nombre --}}
            <div class="form-group">
                <label for="nombre">Nombre de la Institución</label>

                <input type="text"
                       id="nombre"
                       name="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre') }}"
                       placeholder="Ej. Instituto Tecnológico Bolivia">

                @error('nombre')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Dirección --}}
            <div class="form-group">
                <label for="direccion">Dirección</label>

                <input type="text"
                       id="direccion"
                       name="direccion"
                       class="form-control @error('direccion') is-invalid @enderror"
                       value="{{ old('direccion') }}"
                       placeholder="Ej. Av. Heroínas #123">

                @error('direccion')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Teléfono --}}
            <div class="form-group">
                <label for="telefono">Teléfono</label>

                <input type="text"
                       id="telefono"
                       name="telefono"
                       class="form-control @error('telefono') is-invalid @enderror"
                       value="{{ old('telefono') }}"
                       placeholder="Ej. 70707070">

                @error('telefono')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Director --}}
            <div class="form-group">
                <label for="director">Director</label>

                <input type="text"
                       id="director"
                       name="director"
                       class="form-control @error('director') is-invalid @enderror"
                       value="{{ old('director') }}"
                       placeholder="Ej. Juan Pérez">

                @error('director')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="row">

                {{-- Logo --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="logo">
                            Logo Institucional (JPG/PNG)
                        </label>

                        <div class="input-group">
                            <div class="custom-file">

                                <input type="file"
                                       id="logo"
                                       name="logo"
                                       class="custom-file-input @error('logo') is-invalid @enderror"
                                       accept=".jpg,.jpeg,.png">

                                <label class="custom-file-label" for="logo">
                                    Seleccionar imagen...
                                </label>
                            </div>
                        </div>

                        @error('logo')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Documento PDF --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="documento_pdf">
                            Documento PDF
                        </label>

                        <div class="input-group">
                            <div class="custom-file">

                                <input type="file"
                                       id="documento_pdf"
                                       name="documento_pdf"
                                       class="custom-file-input @error('documento_pdf') is-invalid @enderror"
                                       accept=".pdf">

                                <label class="custom-file-label" for="documento_pdf">
                                    Seleccionar PDF...
                                </label>
                            </div>
                        </div>

                        @error('documento_pdf')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

            </div>

        </div>

        <div class="card-footer">

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Guardar Institución
            </button>

            <a href="{{ route('instituciones.index') }}"
               class="btn btn-default">

                <i class="fas fa-arrow-left"></i>
                Cancelar
            </a>

        </div>

    </form>

</div>

@stop