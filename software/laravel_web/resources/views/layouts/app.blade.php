{{-- Layout público (Solicitante / catálogo) — diseño frontend-design aplicado al proyecto:
     inclusión educativa, material táctil, impresión 3D con materiales reciclados.
     Firma visual: la celda Braille de 6 puntos como marca. Paleta papel/verde reciclado/ámbar de filamento.
     Autocontenido (sin CDN ni build) para despliegue offline. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Catálogo de Recursos Táctiles') · Sistema Braille Inclusivo</title>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
    <style>
        :root {
            /* Papel de imprenta fresco (no el crema por defecto) */
            --papel: #F5F7F6;
            --blanco: #FFFFFF;
            --tinta: #1E2A32;
            --tinta-suave: #4A5A63;
            --verde: #146C5A;
            --verde-oscuro: #0F5244;
            --ambar: #B45309;
            --ambar-oscuro: #92400E;
            --linea: #D9E0DE;
            --punto: #146C5A;
            --radio: 12px;
            --sombra: 0 1px 3px rgba(30, 42, 50, .08), 0 8px 24px rgba(30, 42, 50, .06);
            --font-display: Georgia, 'Times New Roman', serif;
            --font-body: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            --font-mono: ui-monospace, 'Cascadia Mono', Menlo, Consolas, monospace;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: var(--font-body);
            color: var(--tinta);
            background: var(--papel);
            line-height: 1.6;
        }

        a { color: var(--verde); text-decoration: none; }
        a:hover { color: var(--verde-oscuro); text-decoration: underline; }

        /* Foco visible (accesibilidad por teclado) */
        :focus-visible {
            outline: 3px solid var(--verde);
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* Salto al contenido (accesibilidad por teclado) */
        .salto {
            position: absolute;
            left: -9999px;
            top: 0;
            background: var(--verde);
            color: #fff;
            padding: 10px 16px;
            border-radius: 0 0 8px 0;
            z-index: 50;
        }
        .salto:focus-visible { left: 0; }

        .encabezado {
            background: var(--blanco);
            border-bottom: 1px solid var(--linea);
        }
        .encabezado-interno {
            max-width: 1120px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .marca {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--tinta);
        }
        .marca .celda-braille { flex: 0 0 auto; }
        .marca small {
            display: block;
            font-family: var(--font-mono);
            font-weight: 400;
            font-size: .62rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--tinta-suave);
        }
        .usuario { display: flex; align-items: center; gap: 12px; font-size: .92rem; }
        .usuario .nombre { color: var(--tinta-suave); }

        .boton {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: var(--radio);
            border: 0;
            font-family: var(--font-body);
            font-weight: 600;
            font-size: .95rem;
            cursor: pointer;
            color: #fff;
            background: var(--ambar);
            text-decoration: none;
            transition: background .15s ease, transform .15s ease;
        }
        .boton:hover { background: var(--ambar-oscuro); color: #fff; text-decoration: none; }
        .boton-secundario { background: var(--verde); }
        .boton-secundario:hover { background: var(--verde-oscuro); }
        .boton-sutil {
            background: transparent;
            color: var(--tinta);
            border: 1px solid var(--linea);
        }
        .boton-sutil:hover { background: var(--papel); color: var(--tinta); }

        @media (prefers-reduced-motion: reduce) {
            .boton { transition: none; transform: none !important; }
        }

        .contenido { max-width: 1120px; margin: 0 auto; padding: 32px 20px 64px; }

        .pie {
            border-top: 1px solid var(--linea);
            background: var(--blanco);
            color: var(--tinta-suave);
            font-size: .85rem;
        }
        .pie-interno {
            max-width: 1120px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .pie .mono {
            font-family: var(--font-mono);
            font-size: .72rem;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <a class="salto" href="#contenido">Saltar al contenido</a>
    <header class="encabezado">
        <div class="encabezado-interno">
            <a href="{{ route('recursos.index') }}" class="marca" aria-label="Catálogo de recursos táctiles">
                <svg class="celda-braille" width="26" height="34" viewBox="0 0 26 34" aria-hidden="true" focusable="false">
                    <g fill="var(--punto)">
                        <circle cx="7"  cy="7"  r="3.4" />
                        <circle cx="19" cy="7"  r="3.4" />
                        <circle cx="7"  cy="17" r="3.4" />
                        <circle cx="19" cy="17" r="3.4" />
                        <circle cx="7"  cy="27" r="3.4" />
                        <circle cx="19" cy="27" r="3.4" />
                    </g>
                </svg>
                <span>Recursos Táctiles<small>Material educativo · Braille Grado 1</small></span>
            </a>
            <div class="usuario">
                @auth
                    <a href="{{ route('pedidos.mis') }}" class="boton boton-sutil">Mis solicitudes</a>
                    <span class="nombre">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="boton boton-sutil">Salir</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <main class="contenido" id="contenido">
        @yield('contenido')
    </main>

    <footer class="pie">
        <div class="pie-interno">
            <span>Proyecto Sociocomunitario Productivo · Sistema Web e Impresora 3D con Materiales Reciclados</span>
            <span class="mono">Inclusión educativa · Impresión 3D · Economía circular</span>
        </div>
    </footer>
</body>
</html>
