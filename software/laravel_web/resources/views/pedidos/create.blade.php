@extends('layouts.app')

@section('titulo', 'Solicitar Impresión')

@section('contenido')
    <style>
        .layout-solicitud {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            max-width: 1080px;
            margin: 0 auto 40px auto;
            align-items: start;
        }
        @media (max-width: 860px) {
            .layout-solicitud { grid-template-columns: 1fr; }
        }

        /* Panel Izquierdo: Vista Previa y 3D */
        .panel-preview {
            background: var(--blanco);
            border: 1px solid var(--linea);
            border-radius: var(--radio);
            box-shadow: var(--sombra);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: sticky;
            top: 20px;
        }
        .preview-viewer-container {
            width: 100%;
            height: 340px;
            background: radial-gradient(circle, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 1px solid var(--linea);
        }
        .preview-viewer-container model-viewer {
            width: 100%;
            height: 100%;
        }
        .preview-viewer-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .preview-sin-archivo {
            text-align: center;
            color: var(--tinta-suave);
            font-family: var(--font-mono);
            font-size: 0.85rem;
            padding: 20px;
        }
        .preview-titulo {
            font-family: var(--font-display);
            font-size: 1.35rem;
            margin: 0;
            line-height: 1.25;
        }
        .preview-desc {
            color: var(--tinta-suave);
            font-size: 0.92rem;
            margin: 0;
            line-height: 1.5;
        }
        .preview-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            background: var(--papel);
            border: 1px solid var(--linea);
            border-radius: 8px;
            padding: 12px 14px;
        }
        .spec-item { display: flex; flex-direction: column; gap: 2px; }
        .spec-item .label {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--tinta-suave);
        }
        .spec-item .val {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--tinta);
        }
        .badge-preview-3d {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(20, 108, 90, 0.92);
            color: #fff;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
            z-index: 10;
        }

        /* Panel Derecho: Formulario */
        .panel-form {
            background: var(--blanco);
            border: 1px solid var(--linea);
            border-radius: var(--radio);
            box-shadow: var(--sombra);
            padding: 28px;
        }
        .panel-form h1 {
            margin: 0 0 4px;
            font-family: var(--font-display);
            font-size: 1.5rem;
        }
        .panel-form .sub {
            margin: 0 0 20px;
            color: var(--tinta-suave);
            font-size: .92rem;
        }
        .campo { margin-bottom: 18px; }
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
            transition: border-color .15s ease;
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
        .acciones { display: flex; gap: 10px; align-items: center; margin-top: 24px; }
    </style>

    <div class="layout-solicitud">
        <!-- Panel Izquierdo: Vista Previa y 3D en Vivo -->
        <aside class="panel-preview">
            <div class="preview-viewer-container" id="preview-container">
                <span class="badge-preview-3d" id="badge-3d" style="display: none;">🧊 Vista 3D en Vivo</span>
                <model-viewer id="preview-model-viewer" 
                              src="" 
                              alt="Vista 3D del recurso" 
                              camera-controls 
                              auto-rotate 
                              shadow-intensity="1.2" 
                              style="display: none;">
                </model-viewer>
                <img id="preview-imagen" src="" alt="Portada del recurso" style="display: none;">
                <div id="preview-sin-archivo" class="preview-sin-archivo">
                    <span style="font-size: 2.5rem; display: block; margin-bottom: 6px;">⠃⠗</span>
                    Selecciona un recurso para ver su modelo 3D
                </div>
            </div>

            <div>
                <h2 class="preview-titulo" id="preview-titulo">Recurso Táctil</h2>
                <p class="preview-desc" id="preview-descripcion">Los detalles y especificaciones del material educativo aparecerán aquí.</p>
            </div>

            <div class="preview-specs">
                <div class="spec-item">
                    <span class="label">Filamento</span>
                    <span class="val" id="preview-gramos">0 g PLA</span>
                </div>
                <div class="spec-item">
                    <span class="label">Tiempo Estimado</span>
                    <span class="val" id="preview-tiempo">0 min</span>
                </div>
                <div class="spec-item">
                    <span class="label">Categoría</span>
                    <span class="val" id="preview-categoria">—</span>
                </div>
                <div class="spec-item">
                    <span class="label">Costo Estimado</span>
                    <span class="val" id="preview-costo" style="color: var(--verde);">$0.00</span>
                </div>
            </div>
        </aside>

        <!-- Panel Derecho: Formulario de Registro -->
        <div class="panel-form">
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
                    <label for="recurso_id">Recurso del catálogo <span class="text-danger">*</span></label>
                    <select name="recurso_id" id="recurso_id" required onchange="actualizarPrevisualizacion()">
                        <option value="">— Seleccione un recurso —</option>
                        @foreach($recursos as $recurso)
                            <option value="{{ $recurso->id }}" 
                                    @selected($recursoSeleccionado === $recurso->id)
                                    data-titulo="{{ $recurso->titulo }}"
                                    data-descripcion="{{ $recurso->descripcion }}"
                                    data-gramos="{{ $recurso->gramos_pla }}"
                                    data-tiempo="{{ $recurso->tiempo_minutos }}"
                                    data-categoria="{{ $recurso->categoria?->nombre ?? 'General' }}"
                                    data-glb="{{ $recurso->archivo_glb ? asset('storage/'.$recurso->archivo_glb) : '' }}"
                                    data-img="{{ $recurso->url_imagen ? asset('storage/'.$recurso->url_imagen) : '' }}">
                                {{ $recurso->titulo }} ({{ $recurso->gramos_pla }} g, ≈ {{ $recurso->tiempo_minutos }} min)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label for="institucion_id">Institución de destino <span class="text-danger">*</span></label>
                    <select name="institucion_id" id="institucion_id" required>
                        <option value="">— Seleccione su institución —</option>
                        @foreach($instituciones as $institucion)
                            <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label for="cantidad">Cantidad de unidades <span class="text-danger">*</span></label>
                    <input type="number" name="cantidad" id="cantidad" min="1" max="100"
                           value="{{ old('cantidad', 1) }}" required oninput="actualizarCostos()">
                    <span class="ayuda">Número de piezas táctiles que se enviarán a fabricar.</span>
                </div>

                <div class="campo">
                    <label for="texto_personalizado">Texto Personalizado Braille (Opcional)</label>
                    <textarea name="texto_personalizado" id="texto_personalizado" rows="3"
                              maxlength="200" placeholder="Ej.: ÑANDÚ — El sistema generará el G-Code automáticamente en tiempo real">{{ old('texto_personalizado') }}</textarea>
                    <span class="ayuda">💡 Si especificas texto, el motor de traducción Braille creará el código de relieve para esta pieza. Si lo dejas vacío, se usará el G-Code predeterminado del catálogo.</span>
                </div>

                <div class="acciones">
                    <button type="submit" class="boton">Registrar Solicitud</button>
                    <a href="{{ route('recursos.index') }}" class="boton boton-sutil">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const PRECIO_GRAMO = {{ $precioGramo ?? 0.05 }};

        function actualizarPrevisualizacion() {
            const select = document.getElementById('recurso_id');
            const selectedOpt = select.options[select.selectedIndex];

            const mv = document.getElementById('preview-model-viewer');
            const img = document.getElementById('preview-imagen');
            const placeholder = document.getElementById('preview-sin-archivo');
            const badge3d = document.getElementById('badge-3d');

            if (!selectedOpt || !selectedOpt.value) {
                mv.style.display = 'none';
                img.style.display = 'none';
                badge3d.style.display = 'none';
                placeholder.style.display = 'block';

                document.getElementById('preview-titulo').innerText = 'Recurso Táctil';
                document.getElementById('preview-descripcion').innerText = 'Los detalles y especificaciones del material educativo aparecerán aquí.';
                document.getElementById('preview-gramos').innerText = '0 g PLA';
                document.getElementById('preview-tiempo').innerText = '0 min';
                document.getElementById('preview-categoria').innerText = '—';
                document.getElementById('preview-costo').innerText = '$0.00';
                return;
            }

            const titulo = selectedOpt.dataset.titulo || '';
            const desc = selectedOpt.dataset.descripcion || '';
            const gramos = parseFloat(selectedOpt.dataset.gramos || 0);
            const tiempo = selectedOpt.dataset.tiempo || 0;
            const categoria = selectedOpt.dataset.categoria || 'General';
            const glb = selectedOpt.dataset.glb || '';
            const imgUrl = selectedOpt.dataset.img || '';

            document.getElementById('preview-titulo').innerText = titulo;
            document.getElementById('preview-descripcion').innerText = desc;
            document.getElementById('preview-categoria').innerText = categoria;

            // Render 3D vs 2D vs Placeholder
            placeholder.style.display = 'none';
            if (glb) {
                mv.setAttribute('src', glb);
                mv.style.display = 'block';
                img.style.display = 'none';
                badge3d.style.display = 'block';
            } else if (imgUrl) {
                mv.style.display = 'none';
                img.setAttribute('src', imgUrl);
                img.style.display = 'block';
                badge3d.style.display = 'none';
            } else {
                mv.style.display = 'none';
                img.style.display = 'none';
                badge3d.style.display = 'none';
                placeholder.style.display = 'block';
            }

            actualizarCostos();
        }

        function actualizarCostos() {
            const select = document.getElementById('recurso_id');
            const selectedOpt = select.options[select.selectedIndex];
            const cantInput = document.getElementById('cantidad');
            const cantidad = parseInt(cantInput.value) || 1;

            if (!selectedOpt || !selectedOpt.value) return;

            const gramosBase = parseFloat(selectedOpt.dataset.gramos || 0);
            const tiempoBase = parseInt(selectedOpt.dataset.tiempo || 0);

            const gramosTotales = (gramosBase * cantidad).toFixed(2);
            const tiempoTotal = tiempoBase * cantidad;
            const costoTotal = (gramosTotales * PRECIO_GRAMO).toFixed(2);

            document.getElementById('preview-gramos').innerText = `${gramosTotales} g PLA`;
            document.getElementById('preview-tiempo').innerText = `≈ ${tiempoTotal} min`;
            document.getElementById('preview-costo').innerText = `$${costoTotal}`;
        }

        // Ejecutar al cargar la página si ya viene un recurso preseleccionado
        document.addEventListener('DOMContentLoaded', function() {
            actualizarPrevisualizacion();
        });
    </script>
@endsection
