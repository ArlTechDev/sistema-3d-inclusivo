@extends('layouts.app')

@section('titulo', 'Solicitar Impresión')

@section('contenido')
    <style>
        .formulario {
            max-width: 640px;
            background: var(--blanco);
            border: 1px solid var(--linea);
            border-radius: var(--radio);
            box-shadow: var(--sombra);
            padding: 28px;
        }
        .formulario h1 {
            margin: 0 0 4px;
            font-family: var(--font-display);
            font-size: 1.5rem;
        }
        .formulario .sub {
            margin: 0 0 20px;
            color: var(--tinta-suave);
            font-size: .92rem;
        }
        .campo { margin-bottom: 16px; }
        .campo label {
            display: block;
            font-weight: 600;
            font-size: .9rem;
            margin-bottom: 6px;
        }
        .campo select, .campo input, .campo textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--linea);
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: .95rem;
            color: var(--tinta);
            background: var(--papel);
        }
        .campo select:focus, .campo input:focus, .campo textarea:focus {
            outline: 3px solid var(--verde);
            outline-offset: 1px;
            border-color: var(--verde);
        }
        .campo .ayuda { display: block; margin-top: 4px; font-size: .8rem; color: var(--tinta-suave); }
        .errores {
            background: #FBE9E7;
            border: 1px solid #E8B4AC;
            color: #7C2D12;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 18px;
        }
        .errores ul { margin: 0; padding-left: 18px; }
        .acciones { display: flex; gap: 10px; align-items: center; margin-top: 22px; }
    </style>

    <div class="formulario">
        <h1>Solicitar Impresión de Recurso</h1>
        <p class="sub">Completa los datos y el Administrador recibirá tu solicitud para imprimirla.</p>

        @if($errors->any())
            <div class="errores" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pedidos.store') }}">
            @csrf

            <div class="campo">
                <label for="recurso_id">Recurso del catálogo *</label>
                <select name="recurso_id" id="recurso_id" required>
                    <option value="">— Seleccione un recurso —</option>
                    @foreach($recursos as $recurso)
                        <option value="{{ $recurso->id }}" @selected($recursoSeleccionado === $recurso->id)>
                            {{ $recurso->titulo }} ({{ $recurso->gramos_pla }} g, ≈ {{ $recurso->tiempo_minutos }} min)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="campo">
                <label for="institucion_id">Institución de origen *</label>
                <select name="institucion_id" id="institucion_id" required>
                    <option value="">— Seleccione su institución —</option>
                    @foreach($instituciones as $institucion)
                        <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="campo">
                <label for="cantidad">Cantidad *</label>
                <input type="number" name="cantidad" id="cantidad" min="1" max="100"
                       value="{{ old('cantidad', 1) }}" required>
            </div>

            <div class="campo">
                <label for="texto_personalizado">Texto personalizado (opcional — ficha Braille)</label>
                <textarea name="texto_personalizado" id="texto_personalizado" rows="3"
                          maxlength="200" placeholder="Ej.: ÑANDÚ — el sistema generará el G-Code automáticamente">{{ old('texto_personalizado') }}</textarea>
                <span class="ayuda">Si lo deja vacío, se usará el G-Code del catálogo del recurso.</span>
            </div>

            <div class="acciones">
                <button type="submit" class="boton">Registrar Solicitud</button>
                <a href="{{ route('recursos.index') }}" class="boton boton-sutil">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
