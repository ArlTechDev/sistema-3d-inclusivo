{{-- Layout público (Solicitante / catálogo) — diseño frontend-design aplicado al proyecto:
     inclusión educativa, material táctil, impresión 3D con materiales reciclados.
     Firma visual: la celda Braille de 6 puntos como marca. Paleta papel/verde reciclado/ámbar de filamento.
     Soporte nativo de Modo Claro / Modo Oscuro con persistencia y accesibilidad WCAG AAA. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Catálogo de Recursos Táctiles') · Sistema Braille Inclusivo</title>
    
    {{-- Anti-flicker: Aplicar tema guardado antes de renderizar el DOM --}}
    <script>
        (function() {
            const temaGuardado = localStorage.getItem('sistema_braille_tema');
            const prefiereOscuro = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (temaGuardado === 'dark' || (!temaGuardado && prefiereOscuro)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>

    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
    
    <style>
        :root {
            /* Tema Claro (Default) */
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
            --viewer-bg: radial-gradient(circle, #f8fafc 0%, #e2e8f0 100%);
            --font-display: Georgia, 'Times New Roman', serif;
            --font-body: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            --font-mono: ui-monospace, 'Cascadia Mono', Menlo, Consolas, monospace;
        }

        /* Tema Oscuro (Accesibilidad / Baja Visión) */
        [data-theme="dark"] {
            --papel: #0F172A;       /* Slate 900 */
            --blanco: #1E293B;      /* Slate 800 (Superficies) */
            --tinta: #F8FAFC;       /* Slate 50 (Texto nítido) */
            --tinta-suave: #94A3B8; /* Slate 400 */
            --verde: #10B981;       /* Esmeralda vibrante */
            --verde-oscuro: #059669;
            --ambar: #F59E0B;       /* Ámbar luminoso */
            --ambar-oscuro: #D97706;
            --linea: #334155;       /* Slate 700 */
            --punto: #10B981;
            --sombra: 0 4px 20px rgba(0, 0, 0, 0.45);
            --viewer-bg: radial-gradient(circle, #1e293b 0%, #090d16 100%);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: var(--font-body);
            color: var(--tinta);
            background: var(--papel);
            line-height: 1.6;
            transition: background-color .2s ease, color .2s ease;
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
            transition: background-color .2s ease, border-color .2s ease;
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
        .usuario .nombre { color: var(--tinta-suave); font-weight: 500; }

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

        /* Botón de alternar Tema Oscuro / Claro */
        .boton-tema {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 999px;
            border: 1px solid var(--linea);
            background: var(--papel);
            color: var(--tinta);
            font-family: var(--font-mono);
            font-size: 0.84rem;
            cursor: pointer;
            transition: all .15s ease;
        }
        .boton-tema:hover {
            border-color: var(--verde);
            color: var(--verde);
        }
        @media (max-width: 580px) {
            .boton-tema .texto-tema { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            .boton, body, .encabezado, .pie { transition: none !important; transform: none !important; }
        }

        .contenido { max-width: 1120px; margin: 0 auto; padding: 32px 20px 64px; }

        .pie {
            border-top: 1px solid var(--linea);
            background: var(--blanco);
            color: var(--tinta-suave);
            font-size: .85rem;
            transition: background-color .2s ease, border-color .2s ease;
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
                <!-- Botón de Modo Oscuro / Claro -->
                <button type="button" id="btn-tema" class="boton-tema" aria-label="Alternar tema visual" title="Cambiar a tema claro/oscuro">
                    <span id="icono-tema">🌙</span>
                    <span id="texto-tema" class="texto-tema">Oscuro</span>
                </button>

                @auth
                    <a href="{{ route('pedidos.mis') }}" class="boton boton-sutil">Mis solicitudes</a>
                    <span class="nombre">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="boton boton-sutil">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="boton boton-sutil">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="boton">Registrarse</a>
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

    {{-- Controlador de Tema Oscuro --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnTema = document.getElementById('btn-tema');
            const iconoTema = document.getElementById('icono-tema');
            const textoTema = document.getElementById('texto-tema');

            function sincronizarBoton(esOscuro) {
                if (esOscuro) {
                    if (iconoTema) iconoTema.innerText = '☀️';
                    if (textoTema) textoTema.innerText = 'Claro';
                    if (btnTema) btnTema.setAttribute('title', 'Cambiar a modo claro');
                } else {
                    if (iconoTema) iconoTema.innerText = '🌙';
                    if (textoTema) textoTema.innerText = 'Oscuro';
                    if (btnTema) btnTema.setAttribute('title', 'Cambiar a modo oscuro');
                }
            }

            const esOscuroInicial = document.documentElement.getAttribute('data-theme') === 'dark';
            sincronizarBoton(esOscuroInicial);

            if (btnTema) {
                btnTema.addEventListener('click', function() {
                    const actualmenteOscuro = document.documentElement.getAttribute('data-theme') === 'dark';
                    if (actualmenteOscuro) {
                        document.documentElement.removeAttribute('data-theme');
                        localStorage.setItem('sistema_braille_tema', 'light');
                        sincronizarBoton(false);
                    } else {
                        document.documentElement.setAttribute('data-theme', 'dark');
                        localStorage.setItem('sistema_braille_tema', 'dark');
                        sincronizarBoton(true);
                    }
                });
            }
        });
    </script>
</body>
</html>
