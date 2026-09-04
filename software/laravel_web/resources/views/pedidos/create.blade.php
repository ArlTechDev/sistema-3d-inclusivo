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
            top: 76px;
        }
        .preview-viewer-container {
            width: 100%;
            height: clamp(220px, 32vw, 340px);
            background: var(--viewer-bg);
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
            font-size: clamp(1.15rem, 2.5vw, 1.35rem);
            margin: 0;
            line-height: 1.25;
        }
        .preview-desc {
            color: var(--tinta-suave);
            font-size: 0.9rem;
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
            font-size: clamp(1.25rem, 3vw, 1.5rem);
            line-height: 1.25;
        }
        .panel-form .sub {
            margin: 0 0 20px;
            color: var(--tinta-suave);
            font-size: .9rem;
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
        .acciones { display: flex; gap: 10px; align-items: center; margin-top: 24px; flex-wrap: wrap; }

        @media (max-width: 860px) {
            .layout-solicitud {
                grid-template-columns: 1fr;
                gap: 20px;
                margin-bottom: 24px;
            }
            .panel-preview {
                position: static;
                padding: 18px;
            }
            .preview-viewer-container {
                height: 220px;
            }
            .panel-form {
                padding: 20px 16px;
            }
        }

        @media (max-width: 520px) {
            .acciones {
                flex-direction: column;
                width: 100%;
            }
            .acciones .boton {
                width: 100%;
            }
            .preview-specs {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                padding: 10px;
            }
            .spec-item .val {
                font-size: 0.95rem;
            }
        }
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
                                    data-img="{{ $recurso->url_imagen ? asset('storage/'.$recurso->url_imagen) : '' }}"
                                    data-placa-ancho="{{ $recurso->placa_ancho ?? '' }}"
                                    data-placa-alto="{{ $recurso->placa_alto ?? '' }}"
                                    data-placa-z="{{ $recurso->placa_z_altura ?? '' }}"
                                    data-max-caracteres="{{ $recurso->max_caracteres ?? '' }}"
                                    data-tipo-placa="{{ $recurso->tipo_placa ?? 'sin_placa' }}">
                                {{ $recurso->titulo }} ({{ $recurso->gramos_pla }} g, ≈ {{ $recurso->tiempo_minutos }} min)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label for="institucion_id">Institución de destino (Opcional)</label>
                    <select name="institucion_id" id="institucion_id">
                        <option value="">— Ninguna / Solicitud Particular (Opcional) —</option>
                        @foreach($instituciones as $institucion)
                            <option value="{{ $institucion->id }}" @selected(old('institucion_id') == $institucion->id)>{{ $institucion->nombre }}</option>
                        @endforeach
                    </select>
                    <span class="ayuda">Si perteneces a una unidad educativa o centro del IBC, selecciónala aquí.</span>
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
                              maxlength="200" oninput="actualizarCostos()"
                              placeholder="Ej.: HOLA MUNDO — El sistema generará el G-Code automáticamente en tiempo real">{{ old('texto_personalizado') }}</textarea>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                        <span class="ayuda" style="margin: 0;">💡 Generación dinámica de puntos de relieve Braille Grado 1 (ONCE).</span>
                        <strong id="contador-braille" style="font-size: 0.8rem; color: var(--tinta-suave); white-space: nowrap; margin-left: 8px;">0 celdas</strong>
                    </div>

                    <!-- Contenedor del Preview Visual de la Placa Braille (Canvas 2D) -->
                    <div id="contenedor-canvas-braille" aria-live="polite" style="margin-top: 14px; display: none;">
                        <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--tinta-suave); margin-bottom:6px;">
                            👁️ Previsualización del Relieve Braille en la Placa:
                        </label>
                        <div style="background: var(--papel); padding: 14px; border-radius: 8px; border: 1px solid var(--linea); text-align: center; overflow-x: auto;">
                            <canvas id="braille-preview" style="max-width: 100%; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);"></canvas>
                        </div>
                    </div>
                </div>

                <div class="acciones">
                    <button type="submit" class="boton" aria-label="Registrar solicitud de material táctil">Registrar Solicitud</button>
                    <a href="{{ route('recursos.index') }}" class="boton boton-sutil">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const PRECIO_GRAMO = {{ $precioGramo ?? 0.15 }};
        const MONEDA_SIMBOLO = "{{ $moneda ?? 'Bs' }}";
        const GRAMOS_POR_CELDA = {{ $gramosPorCelda ?? 0.02 }};

        // Mapa Código Braille Español (ONCE) Grado 1
        // Coordenadas [columna (0-1), fila (0-2)] de los puntos 1 a 6
        const BRAILLE_MAPA = {
            'a': [[0,0]],
            'b': [[0,0],[0,1]],
            'c': [[0,0],[1,0]],
            'd': [[0,0],[1,0],[1,1]],
            'e': [[0,0],[1,1]],
            'f': [[0,0],[0,1],[1,0]],
            'g': [[0,0],[0,1],[1,0],[1,1]],
            'h': [[0,0],[0,1],[1,1]],
            'i': [[0,1],[1,0]],
            'j': [[0,1],[1,0],[1,1]],
            'k': [[0,0],[0,2]],
            'l': [[0,0],[0,1],[0,2]],
            'm': [[0,0],[0,2],[1,0]],
            'n': [[0,0],[0,2],[1,0],[1,1]],
            'o': [[0,0],[0,2],[1,1]],
            'p': [[0,0],[0,1],[0,2],[1,0]],
            'q': [[0,0],[0,1],[0,2],[1,0],[1,1]],
            'r': [[0,0],[0,1],[0,2],[1,1]],
            's': [[0,1],[0,2],[1,0]],
            't': [[0,1],[0,2],[1,0],[1,1]],
            'u': [[0,0],[0,2],[1,2]],
            'v': [[0,0],[0,1],[0,2],[1,2]],
            'w': [[0,1],[1,0],[1,1],[1,2]],
            'x': [[0,0],[0,2],[1,0],[1,2]],
            'y': [[0,0],[0,2],[1,0],[1,1],[1,2]],
            'z': [[0,0],[0,2],[1,1],[1,2]],
            // ñ española (puntos 1,2,4,5,6)
            'ñ': [[0,0],[0,1],[1,0],[1,1],[1,2]],
            // Vocales acentuadas
            'á': [[0,0],[1,2]],
            'é': [[0,0],[0,1],[1,2]],
            'í': [[0,2],[1,0]],
            'ó': [[0,2],[1,0],[1,2]],
            'ú': [[0,0],[1,1],[1,2]],
            'ü': [[0,0],[0,1],[1,1],[1,2]],
            // Dígitos (representación base)
            '1': [[0,0]], '2': [[0,0],[0,1]], '3': [[0,0],[1,0]], '4': [[0,0],[1,0],[1,1]], '5': [[0,0],[1,1]],
            '6': [[0,0],[0,1],[1,0]], '7': [[0,0],[0,1],[1,0],[1,1]], '8': [[0,0],[0,1],[1,1]], '9': [[0,1],[1,0]], '0': [[0,1],[1,0],[1,1]],
            // Puntuación
            '.': [[0,1],[1,1],[1,2]], ',': [[0,1]], ';': [[0,1],[0,2]], ':': [[0,1],[1,1]],
            '?': [[0,1],[0,2],[1,2]], '¿': [[0,1],[0,2],[1,2]], '!': [[0,1],[0,2],[1,1]], '¡': [[0,1],[0,2],[1,1]],
            '-': [[0,2],[1,2]], "'": [[0,2]], '"': [[0,1],[0,2],[1,2]],
            '(': [[0,0],[0,1],[0,2],[1,1],[1,2]], ')': [[0,1],[0,2],[1,0],[1,1],[1,2]]
        };

        const SIGNO_NUMERAL = [[0,2],[1,0],[1,1],[1,2]]; // ⠼ (puntos 3,4,5,6)
        const SIGNO_MAYUSCULA = [[1,2]];                 // ⠠ (punto 6)

        function traducirTextoACeldas(texto) {
            const celdas = [];
            let enNumero = false;

            for (let i = 0; i < texto.length; i++) {
                const char = texto[i];

                if (/\s/.test(char)) {
                    celdas.push({ tipo: 'espacio', puntos: [] });
                    enNumero = false;
                    continue;
                }

                const esDigito = /[0-9]/.test(char);
                if (esDigito && !enNumero) {
                    celdas.push({ tipo: 'numeral', puntos: SIGNO_NUMERAL });
                    enNumero = true;
                } else if (!esDigito) {
                    enNumero = false;
                }

                const charMin = char.toLowerCase();
                const esMayus = (char !== charMin && /[a-zñáéíóúü]/i.test(char));

                if (esMayus) {
                    celdas.push({ tipo: 'mayuscula', puntos: SIGNO_MAYUSCULA });
                }

                if (BRAILLE_MAPA[charMin]) {
                    celdas.push({ tipo: 'letra', char: char, puntos: BRAILLE_MAPA[charMin] });
                }
            }

            return celdas;
        }

        function dibujarPlacaBraille(celdas, anchoMm, altoMm, maxCapacidad) {
            const canvas = document.getElementById('braille-preview');
            const cont = document.getElementById('contenedor-canvas-braille');
            if (!canvas || !cont) return;

            if (!anchoMm || !altoMm) {
                cont.style.display = 'none';
                return;
            }

            cont.style.display = 'block';

            // Escala de dibujo: 1mm = 5px
            const ESCALA = 5;
            const wPx = Math.round(anchoMm * ESCALA);
            const hPx = Math.round(altoMm * ESCALA);

            canvas.width = wPx;
            canvas.height = hPx;

            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, wPx, hPx);

            // Fondo de la placa sólida (con esquinas redondeadas)
            ctx.fillStyle = '#E8F5E9';
            ctx.strokeStyle = '#2E7D32';
            ctx.lineWidth = 2;
            if (ctx.roundRect) {
                ctx.beginPath();
                ctx.roundRect(1, 1, wPx - 2, hPx - 2, 8);
                ctx.fill();
                ctx.stroke();
            } else {
                ctx.fillRect(1, 1, wPx - 2, hPx - 2);
                ctx.strokeRect(1, 1, wPx - 2, hPx - 2);
            }

            // Parámetros Braille en px (ONCE)
            const margenXPx = 5.0 * ESCALA;
            const margenYPx = 5.0 * ESCALA;
            const pasoX = 2.5 * ESCALA;
            const pasoY = 2.5 * ESCALA;
            const avanceCelda = 6.0 * ESCALA;
            const avanceLinea = 10.0 * ESCALA;
            const radioPunto = 0.85 * ESCALA;

            const maxCeldasPorFila = Math.max(1, Math.floor((wPx - 2 * margenXPx) / avanceCelda));
            const maxFilas = Math.max(1, Math.floor((hPx - 2 * margenYPx) / avanceLinea) + 1);

            let fila = 0;
            let col = 0;

            celdas.forEach((c) => {
                if (col >= maxCeldasPorFila) {
                    fila++;
                    col = 0;
                }

                if (fila < maxFilas) {
                    const cx = margenXPx + (col * avanceCelda);
                    const cy = margenYPx + (fila * avanceLinea);

                    if (c.puntos && c.puntos.length > 0) {
                        c.puntos.forEach(([pCol, pFila]) => {
                            const px = cx + (pCol * pasoX);
                            const py = cy + (pFila * pasoY);

                            ctx.beginPath();
                            ctx.arc(px, py, radioPunto, 0, Math.PI * 2);
                            ctx.fillStyle = '#1B5E20';
                            ctx.fill();
                            ctx.strokeStyle = '#0A3D14';
                            ctx.lineWidth = 1;
                            ctx.stroke();
                        });
                    }
                }

                col++;
            });
        }

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
                document.getElementById('preview-costo').innerText = `${MONEDA_SIMBOLO} 0.00`;
                document.getElementById('contenedor-canvas-braille').style.display = 'none';
                return;
            }

            const titulo = selectedOpt.dataset.titulo || '';
            const desc = selectedOpt.dataset.descripcion || '';
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
            const textoInput = document.getElementById('texto_personalizado');
            const texto = textoInput ? textoInput.value.trim() : '';

            if (!selectedOpt || !selectedOpt.value) return;

            const gramosBase = parseFloat(selectedOpt.dataset.gramos || 0);
            const tiempoBase = parseInt(selectedOpt.dataset.tiempo || 0);
            const placaAncho = parseFloat(selectedOpt.dataset.placaAncho || 0);
            const placaAlto = parseFloat(selectedOpt.dataset.placaAlto || 0);
            const maxCapacidad = parseInt(selectedOpt.dataset.maxCaracteres || 0);

            // Traducción precisa en el cliente
            const celdas = traducirTextoACeldas(texto);
            const totalCeldas = celdas.length;
            const gramosExtraBraille = totalCeldas * GRAMOS_POR_CELDA;

            const gramosUnitarios = gramosBase + gramosExtraBraille;
            const gramosTotales = (gramosUnitarios * cantidad).toFixed(2);
            const tiempoTotal = tiempoBase * cantidad;
            const costoTotal = (gramosTotales * PRECIO_GRAMO).toFixed(2);

            document.getElementById('preview-gramos').innerText = `${gramosTotales} g PLA`;
            document.getElementById('preview-tiempo').innerText = `≈ ${tiempoTotal} min`;
            document.getElementById('preview-costo').innerText = `${MONEDA_SIMBOLO} ${costoTotal}`;

            // Contador de celdas
            const contadorEl = document.getElementById('contador-braille');
            if (maxCapacidad > 0) {
                contadorEl.innerText = `${totalCeldas} / ${maxCapacidad} celdas Braille`;
                contadorEl.style.color = totalCeldas > maxCapacidad ? '#B71C1C' : 'var(--tinta-suave)';
            } else {
                contadorEl.innerText = `${totalCeldas} celdas Braille`;
                contadorEl.style.color = 'var(--tinta-suave)';
            }

            // Dibujar Canvas 2D interactivo
            if (placaAncho > 0 && placaAlto > 0) {
                dibujarPlacaBraille(celdas, placaAncho, placaAlto, maxCapacidad);
            } else {
                const cont = document.getElementById('contenedor-canvas-braille');
                if (cont) cont.style.display = 'none';
            }
        }

        // Ejecutar al cargar la página si ya viene un recurso preseleccionado
        document.addEventListener('DOMContentLoaded', function() {
            actualizarPrevisualizacion();
        });
    </script>
@endsection
