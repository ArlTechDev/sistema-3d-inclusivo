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
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .encabezado-interno {
            max-width: 1120px;
            margin: 0 auto;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
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
            text-decoration: none !important;
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
        .usuario {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .92rem;
            flex-wrap: wrap;
        }
        .usuario .nombre {
            color: var(--tinta-suave);
            font-weight: 500;
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .boton {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: var(--radio);
            border: 0;
            font-family: var(--font-body);
            font-weight: 600;
            font-size: .92rem;
            cursor: pointer;
            color: #fff;
            background: var(--ambar);
            text-decoration: none;
            transition: background .15s ease, transform .15s ease;
            white-space: nowrap;
        }
        .boton:hover { background: var(--ambar-oscuro); color: #fff; text-decoration: none; }
        .boton-secundario { background: var(--verde); }
        .boton-secundario:hover { background: var(--verde-oscuro); }
        .boton-sutil {
            background: transparent;
            color: var(--tinta);
            border: 1px solid var(--linea);
        }
        .boton-sutil:hover { background: var(--papel); color: var(--tinta); text-decoration: none; }

        /* Botón de alternar Tema Oscuro / Claro */
        .boton-tema {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 999px;
            border: 1px solid var(--linea);
            background: var(--papel);
            color: var(--tinta);
            font-family: var(--font-mono);
            font-size: 0.82rem;
            cursor: pointer;
            transition: all .15s ease;
        }
        .boton-tema:hover {
            border-color: var(--verde);
            color: var(--verde);
        }

        @media (max-width: 640px) {
            .encabezado-interno {
                padding: 10px 14px;
                gap: 8px;
            }
            .marca {
                font-size: 1.02rem;
                gap: 8px;
            }
            .marca small { font-size: 0.58rem; }
            .usuario {
                gap: 6px;
                font-size: 0.82rem;
            }
            .usuario .nombre { display: none; }
            .boton {
                padding: 7px 12px;
                font-size: 0.84rem;
            }
            .boton-tema {
                padding: 6px 9px;
            }
            .boton-tema .texto-tema { display: none; }
            .contenido {
                padding: 18px 14px 48px;
            }
            .pie-interno {
                flex-direction: column;
                text-align: center;
                gap: 8px;
                padding: 16px 14px;
            }
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
            align-items: center;
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
            <a href="{{ route('home') }}" class="marca" aria-label="Ir a Inicio - Táctil3D">
                <div style="width: 38px; height: 38px; flex-shrink: 0;">
                    <x-logo-svg size="38" />
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center; line-height: 1.1;">
                    <span style="font-size: 1.25rem; font-weight: 800; letter-spacing: 0.5px; color: var(--tinta);">
                        TÁCTIL<span style="color: var(--verde);">3D</span>
                    </span>
                    <small style="font-size: 0.62rem; font-weight: 700; color: var(--tinta-suave); letter-spacing: 0.1em; text-transform: uppercase;">
                        Sistema Braille Inclusivo
                    </small>
                </div>
            </a>
            <div class="usuario">
                <div class="nav-links" style="display: flex; gap: 4px; margin-right: 8px;">
                    <a href="{{ route('recursos.index') }}" class="boton boton-sutil" style="border-color: transparent; padding: 7px 12px;">Catálogo</a>
                    <a href="{{ route('pages.about') }}" class="boton boton-sutil" style="border-color: transparent; padding: 7px 12px;">Acerca del Proyecto</a>
                    <a href="{{ route('pages.help') }}" class="boton boton-sutil" style="border-color: transparent; padding: 7px 12px;">Ayuda</a>
                </div>

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
            <span>Proyecto Sociocomunitario Productivo · Inst. Técnico "Federico Álvarez Plata" Nocturno</span>
            <span class="mono">Inclusión educativa · Impresión 3D · Cochabamba, Bolivia</span>
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
