@extends('layouts.app')

@section('titulo', 'Catálogo de Recursos Táctiles')

@section('contenido')
    <style>
        /* Hero: la tesis de la página — el material táctil como recurso educativo */
        .hero {
            position: relative;
            overflow: hidden;
            background: var(--verde);
            color: #fff;
            border-radius: var(--radio);
            padding: 40px 32px;
            margin-bottom: 28px;
        }
        .hero::after {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 260px;
            height: 260px;
            background-image: radial-gradient(circle, rgba(255,255,255,.18) 9px, transparent 10px);
            background-size: 40px 46px;
            pointer-events: none;
        }
        .hero h1 {
            margin: 0 0 8px;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: clamp(1.6rem, 3.4vw, 2.4rem);
            line-height: 1.15;
            max-width: 640px;
        }
        .hero p {
            margin: 0 0 18px;
            max-width: 560px;
            color: rgba(255,255,255,.92);
            font-size: 1.02rem;
        }
        .hero .eyebrow {
            font-family: var(--font-mono);
            font-size: .72rem;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: rgba(255,255,255,.85);
            margin-bottom: 12px;
            display: block;
        }
        .hero .stats {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            margin-top: 6px;
        }
        .hero .stats b {
            font-family: var(--font-display);
            font-size: 1.5rem;
            display: block;
            line-height: 1.1;
        }
        .hero .stats span { font-size: .85rem; color: rgba(255,255,255,.9); }

        /* Filtro por categorías */
        .filtros {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }
        .filtro {
            padding: 7px 14px;
            border-radius: 999px;
            border: 1px solid var(--linea);
            background: var(--blanco);
            color: var(--tinta);
            font-size: .88rem;
            font-family: var(--font-mono);
            text-decoration: none;
        }
        .filtro:hover { border-color: var(--verde); color: var(--verde); text-decoration: none; }
        .filtro.activo {
            background: var(--verde);
            border-color: var(--verde);
            color: #fff;
        }
        .filtro:focus-visible { outline-color: var(--ambar); }

        /* Rejilla de tarjetas */
        .rejilla {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 22px;
        }
        .tarjeta {
            background: var(--blanco);
            border: 1px solid var(--linea);
            border-radius: var(--radio);
            box-shadow: var(--sombra);
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: transform .15s ease, box-shadow .15s ease;
            position: relative;
        }
        .tarjeta:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(30,42,50,.12); }
        @media (prefers-reduced-motion: reduce) {
            .tarjeta { transition: none; transform: none !important; }
        }
        .tarjeta .imagen-contenedor {
            height: 160px;
            border-radius: 8px;
            background: linear-gradient(135deg, #E3EBE8, #D3DFDA);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .tarjeta .imagen-contenedor img { width: 100%; height: 100%; object-fit: cover; }
        .tarjeta .badge-3d {
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
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 4px;
            backdrop-filter: blur(4px);
        }
        .tarjeta .sin-imagen {
            color: var(--verde);
            font-family: var(--font-mono);
            font-size: .8rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            text-align: center;
        }
        .tarjeta h2 {
            margin: 2px 0 0;
            font-family: var(--font-display);
            font-size: 1.18rem;
            line-height: 1.25;
        }
        .tarjeta .descripcion {
            margin: 0;
            color: var(--tinta-suave);
            font-size: .9rem;
            flex: 1;
        }
        .tarjeta .metadatos {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            font-family: var(--font-mono);
            font-size: .74rem;
            color: var(--tinta-suave);
        }
        .tarjeta .metadatos span {
            background: var(--papel);
            border: 1px solid var(--linea);
            border-radius: 6px;
            padding: 3px 8px;
        }
        .tarjeta .acciones-tarjeta {
            display: flex;
            gap: 8px;
            margin-top: 4px;
        }
        .tarjeta .acciones-tarjeta .boton {
            flex: 1;
            justify-content: center;
            text-align: center;
            padding: 9px 12px;
            font-size: 0.88rem;
        }
        .tarjeta .boton-3d {
            background: var(--papel);
            color: var(--verde);
            border: 1px solid var(--verde);
        }
        .tarjeta .boton-3d:hover {
            background: var(--verde);
            color: #fff;
            text-decoration: none;
        }

        /* Modal 3D */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(6px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal-overlay.activo { display: flex; }
        .modal-card {
            background: var(--blanco);
            width: 100%;
            max-width: 680px;
            border-radius: var(--radio);
            border: 1px solid var(--linea);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: modalIn .2s ease-out;
        }
        @keyframes modalIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--linea);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--papel);
        }
        .modal-header h3 { margin: 0; font-size: 1.15rem; font-family: var(--font-display); color: var(--tinta); }
        .modal-close {
            background: transparent;
            border: none;
            font-size: 1.4rem;
            cursor: pointer;
            color: var(--tinta-suave);
            padding: 0 4px;
        }
        .modal-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: var(--blanco);
        }
        .modal-viewer-container {
            width: 100%;
            height: 420px;
            background: var(--viewer-bg);
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }
        .modal-viewer-container model-viewer {
            width: 100%;
            height: 100%;
        }
        .modal-footer {
            padding: 14px 20px;
            border-top: 1px solid var(--linea);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--papel);
        }
        .modal-tip {
            font-size: 0.8rem;
            color: var(--tinta-suave);
            font-family: var(--font-mono);
        }

        .vacio {
            text-align: center;
            padding: 48px 20px;
            color: var(--tinta-suave);
        }
        .vacio b { color: var(--tinta); }
    </style>

    <section class="hero">
        <span class="eyebrow">Catálogo educativo táctil · Código Braille Español · Grado 1</span>
        <h1>El material táctil, impreso para tu aula.</h1>
        <p>Recursos educativos en relieve —mapas, figuras geométricas, reglas y fichas Braille— producidos con impresora 3D y materiales reciclados, para estudiantes con discapacidad visual.</p>
        <div class="stats">
            <div><b>{{ $recursos->count() }}</b><span>recursos disponibles</span></div>
            <div><b>{{ $categorias->count() }}</b><span>categorías</span></div>
            <div><b>{{ $moneda ?? 'Bs' }} {{ number_format($precioGramo ?? 0.15, 2) }}</b><span>tarifa por gramo PLA</span></div>
            <div><b>+{{ $gramosPorCelda ?? 0.02 }} g</b><span>relieve Braille/celda</span></div>
        </div>
    </section>

    <nav class="filtros" aria-label="Filtrar por categoría">
        <a href="{{ route('recursos.index') }}" class="filtro @if(! request('categoria')) activo @endif">Todos</a>
        @foreach($categorias as $categoria)
            <a href="{{ route('recursos.index', ['categoria' => $categoria->id]) }}"
               class="filtro @if(request('categoria') == $categoria->id) activo @endif">
                {{ $categoria->nombre }}
            </a>
        @endforeach
    </nav>

    @if($recursos->isNotEmpty())
        <div class="rejilla">
            @foreach($recursos as $recurso)
                <article class="tarjeta">
                    <div class="imagen-contenedor">
                        @if($recurso->archivo_glb)
                            <span class="badge-3d">🧊 3D Interactivo</span>
                        @endif

                        @if($recurso->url_imagen)
                            <img src="{{ asset('storage/'.$recurso->url_imagen) }}" alt="Imagen de {{ $recurso->titulo }}" loading="lazy">
                        @else
                            <div class="sin-imagen">
                                <span style="font-size: 2rem; display: block; margin-bottom: 4px;">⠃⠗</span>
                                Recurso táctil
                            </div>
                        @endif
                    </div>

                    <h2>{{ $recurso->titulo }}</h2>
                    <p class="descripcion">{{ $recurso->descripcion }}</p>

                    <div class="metadatos">
                        <span><b>{{ $recurso->gramos_pla }} g</b> PLA</span>
                        <span><b>≈ {{ $moneda ?? 'Bs' }} {{ number_format($recurso->gramos_pla * ($precioGramo ?? 0.15), 2) }}</b></span>
                        <span>≈ {{ $recurso->tiempo_minutos }} min</span>
                        @if($recurso->categoria)
                            <span>{{ $recurso->categoria->nombre }}</span>
                        @endif
                    </div>

                    <div class="acciones-tarjeta">
                        @if($recurso->archivo_glb)
                            <button type="button" class="boton boton-3d" 
                                    onclick="abrirModal3D('{{ asset('storage/'.$recurso->archivo_glb) }}', '{{ addslashes($recurso->titulo) }}', '{{ route('pedidos.create', ['recurso' => $recurso->id]) }}')">
                                👁️ Vista 3D
                            </button>
                        @endif
                        <a href="{{ route('pedidos.create', ['recurso' => $recurso->id]) }}" class="boton">
                            Solicitar Impresión
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="vacio">
            <b>No hay recursos en esta categoría todavía.</b>
            <p>Vuelve pronto: el catálogo crece con los modelos didácticos validados.</p>
        </div>
    @endif

    <!-- Modal Visor 3D Amplio -->
    <div id="modal3d" class="modal-overlay" onclick="cerrarModal3DFuera(event)">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modal3d-titulo">Explorador Tridimensional</h3>
                <button type="button" class="modal-close" onclick="cerrarModal3D()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-viewer-container">
                    <model-viewer id="modal-viewer-el" 
                                  src="" 
                                  alt="Modelo 3D" 
                                  camera-controls 
                                  auto-rotate 
                                  shadow-intensity="1.2"
                                  touch-action="pan-y">
                    </model-viewer>
                </div>
            </div>
            <div class="modal-footer">
                <span class="modal-tip">💡 Arrastra para rotar 360° · Usa la rueda para zoom</span>
                <a id="modal3d-btn-solicitar" href="#" class="boton">
                    Solicitar este Recurso
                </a>
            </div>
        </div>
    </div>

    <script>
        function abrirModal3D(glbUrl, titulo, urlSolicitar) {
            document.getElementById('modal3d-titulo').innerText = titulo;
            document.getElementById('modal-viewer-el').setAttribute('src', glbUrl);
            document.getElementById('modal3d-btn-solicitar').setAttribute('href', urlSolicitar);
            document.getElementById('modal3d').classList.add('activo');
        }

        function cerrarModal3D() {
            document.getElementById('modal3d').classList.remove('activo');
            document.getElementById('modal-viewer-el').setAttribute('src', '');
        }

        function cerrarModal3DFuera(e) {
            if (e.target.id === 'modal3d') {
                cerrarModal3D();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') cerrarModal3D();
        });
    </script>
@endsection
