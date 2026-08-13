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
            /* Firma: patrón sutil de celdas Braille (6 puntos) en el fondo */
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
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .tarjeta {
            background: var(--blanco);
            border: 1px solid var(--linea);
            border-radius: var(--radio);
            box-shadow: var(--sombra);
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .tarjeta:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(30,42,50,.12); }
        @media (prefers-reduced-motion: reduce) {
            .tarjeta { transition: none; transform: none !important; }
        }
        .tarjeta .imagen {
            height: 120px;
            border-radius: 8px;
            background: linear-gradient(135deg, #E3EBE8, #D3DFDA);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .tarjeta .imagen img { width: 100%; height: 100%; object-fit: cover; }
        .tarjeta .imagen .sin-imagen {
            color: var(--verde);
            font-family: var(--font-mono);
            font-size: .72rem;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .tarjeta h2 {
            margin: 2px 0 0;
            font-family: var(--font-display);
            font-size: 1.12rem;
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
        .tarjeta .boton { justify-content: center; margin-top: 4px; }

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
                    <div class="imagen">
                        @if($recurso->url_imagen)
                            <img src="{{ asset('storage/'.$recurso->url_imagen) }}" alt="Imagen de {{ $recurso->titulo }}" loading="lazy">
                        @else
                            <span class="sin-imagen">Recurso táctil</span>
                        @endif
                    </div>
                    <h2>{{ $recurso->titulo }}</h2>
                    <p class="descripcion">{{ $recurso->descripcion }}</p>
                    <div class="metadatos">
                        <span>{{ $recurso->gramos_pla }} g PLA</span>
                        <span>≈ {{ $recurso->tiempo_minutos }} min</span>
                        @if($recurso->categoria)
                            <span>{{ $recurso->categoria->nombre }}</span>
                        @endif
                    </div>
                    <a href="{{ route('pedidos.create', ['recurso' => $recurso->id]) }}" class="boton">
                        Solicitar Impresión
                    </a>
                </article>
            @endforeach
        </div>
    @else
        <div class="vacio">
            <b>No hay recursos en esta categoría todavía.</b>
            <p>Vuelve pronto: el catálogo crece con los modelos didácticos validados.</p>
        </div>
    @endif
@endsection
