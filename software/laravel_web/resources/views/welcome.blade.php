@extends('layouts.app')
@section('titulo', 'Táctil3D · Sistema Braille Inclusivo')

@section('contenido')
<style>
    /* Hero Section */
    .hero-container {
        padding: 40px 0 64px;
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 48px;
        align-items: center;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 999px;
        background: rgba(16, 185, 129, 0.1);
        color: var(--verde);
        font-family: var(--font-mono);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 20px;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }
    .hero-title {
        font-family: var(--font-display);
        font-size: 3.1rem;
        line-height: 1.15;
        color: var(--tinta);
        margin: 0 0 20px 0;
        letter-spacing: -0.02em;
        font-weight: 800;
    }
    .hero-title .destacado-gradiente {
        background: linear-gradient(135deg, #0284c7 0%, #10b981 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero-subtitle {
        font-size: 1.12rem;
        line-height: 1.6;
        color: var(--tinta-suave);
        margin: 0 0 32px 0;
        max-width: 580px;
    }
    .hero-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 36px;
    }
    .btn-hero-primary {
        background: var(--verde);
        color: #ffffff !important;
        padding: 13px 26px;
        border-radius: var(--radio);
        font-weight: 700;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
    }
    .btn-hero-primary:hover {
        background: var(--verde-oscuro);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
        text-decoration: none;
    }
    .btn-hero-secondary {
        background: var(--blanco);
        color: var(--tinta) !important;
        border: 1px solid var(--linea);
        padding: 13px 22px;
        border-radius: var(--radio);
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn-hero-secondary:hover {
        background: var(--papel);
        border-color: var(--tinta-suave);
        text-decoration: none;
    }

    /* Stats bar in Hero */
    .hero-stats-bar {
        display: flex;
        gap: 28px;
        border-top: 1px solid var(--linea);
        padding-top: 24px;
        flex-wrap: wrap;
    }
    .stat-hero-item .stat-num {
        font-family: var(--font-mono);
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-hero-item .stat-label {
        font-size: 0.75rem;
        color: var(--tinta-suave);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    /* Hero Visual Showcase */
    .showcase-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .showcase-glow {
        position: absolute;
        width: 90%;
        height: 90%;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, rgba(56, 189, 248, 0.1) 50%, transparent 70%);
        filter: blur(40px);
        z-index: 1;
    }
    .showcase-frame {
        position: relative;
        z-index: 2;
        background: var(--blanco);
        border: 1px solid var(--linea);
        border-radius: 20px;
        padding: 16px;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        width: 100%;
        max-width: 460px;
    }
    .showcase-img {
        width: 100%;
        height: auto;
        border-radius: 14px;
        display: block;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .showcase-floating-badge {
        position: absolute;
        bottom: 28px;
        left: 28px;
        right: 28px;
        background: rgba(11, 17, 33, 0.88);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(56, 189, 248, 0.3);
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #ffffff;
    }

    /* Sección de Pilares */
    .section-pills {
        padding: 56px 0;
        border-top: 1px solid var(--linea);
    }
    .section-intro {
        text-align: center;
        max-width: 680px;
        margin: 0 auto 48px;
    }
    .section-intro h2 {
        font-family: var(--font-display);
        font-size: 2.1rem;
        color: var(--tinta);
        margin: 0 0 12px 0;
        font-weight: 800;
    }
    .section-intro p {
        color: var(--tinta-suave);
        font-size: 1.05rem;
        margin: 0;
    }
    .pillars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }
    .pillar-card {
        background: var(--blanco);
        border: 1px solid var(--linea);
        border-radius: var(--radio);
        padding: 32px 24px;
        box-shadow: var(--sombra);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .pillar-card:hover {
        transform: translateY(-4px);
        border-color: var(--verde);
    }
    .pillar-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        margin-bottom: 20px;
        background: var(--papel);
        border: 1px solid var(--linea);
    }
    .pillar-card h3 {
        font-family: var(--font-display);
        font-size: 1.25rem;
        margin: 0 0 10px 0;
        color: var(--tinta);
        font-weight: 700;
    }
    .pillar-card p {
        color: var(--tinta-suave);
        font-size: 0.94rem;
        line-height: 1.55;
        margin: 0;
    }

    /* Flujo de 3 Pasos */
    .flow-section {
        background: var(--blanco);
        border: 1px solid var(--linea);
        border-radius: 20px;
        padding: 48px 36px;
        margin: 48px 0;
        box-shadow: var(--sombra);
    }
    .flow-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 32px;
        margin-top: 36px;
    }
    .flow-step {
        position: relative;
    }
    .step-num {
        font-family: var(--font-mono);
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--ambar);
        line-height: 1;
        margin-bottom: 8px;
        opacity: 0.85;
    }
    .flow-step h4 {
        font-size: 1.1rem;
        color: var(--tinta);
        margin: 0 0 8px 0;
        font-weight: 700;
    }
    .flow-step p {
        color: var(--tinta-suave);
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0;
    }

    /* Banner Final */
    .cta-banner {
        background: linear-gradient(135deg, var(--verde) 0%, var(--verde-oscuro) 100%);
        border-radius: 20px;
        padding: 48px 36px;
        color: white;
        text-align: center;
        margin-top: 56px;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.25);
    }
    .cta-banner h2 {
        font-family: var(--font-display);
        font-size: 2.3rem;
        color: white;
        margin: 0 0 12px 0;
        font-weight: 800;
    }
    .cta-banner p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto 28px;
    }
    .btn-cta-white {
        background: white;
        color: var(--verde-oscuro) !important;
        font-weight: 700;
        font-size: 1.02rem;
        padding: 14px 28px;
        border-radius: var(--radio);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-cta-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    @media (max-width: 860px) {
        .hero-container {
            grid-template-columns: 1fr;
            padding: 24px 0 40px;
            gap: 32px;
        }
        .hero-title {
            font-size: 2.3rem;
        }
        .flow-section {
            padding: 32px 20px;
        }
        .hero-stats-bar {
            gap: 18px;
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-container">
    <div>
        <div class="hero-badge">
            <span>🟢</span> PROYECTO SOCIOCOMUNITARIO PRODUCTIVO (PSCP)
        </div>
        <h1 class="hero-title">
            Democratizando el <span class="destacado-gradiente">Material Braille</span> con Tecnología 3D
        </h1>
        <p class="hero-subtitle">
            Plataforma digital y maquinaria electromecánica sustentable fabricada a partir de componentes recuperados (e-waste) para la producción de recursos táctiles en Bolivia.
        </p>
        <div class="hero-actions">
            <a href="{{ route('recursos.index') }}" class="btn-hero-primary">
                <span>📚</span> Explorar Catálogo
            </a>
            <a href="{{ route('pages.about') }}" class="btn-hero-secondary">
                <span>⚙️</span> Conocer el Proyecto
            </a>
        </div>

        <!-- Estadísticas Rápidas del Proyecto -->
        <div class="hero-stats-bar">
            <div class="stat-hero-item">
                <div class="stat-num" style="color: #0284c7;">15 kg</div>
                <div class="stat-label">RAEE Reciclado</div>
            </div>
            <div class="stat-hero-item">
                <div class="stat-num" style="color: var(--verde);">120+</div>
                <div class="stat-label">Piezas Fabricadas</div>
            </div>
            <div class="stat-hero-item">
                <div class="stat-num" style="color: var(--ambar);">0.00 Bs</div>
                <div class="stat-label">Costo Solicitante</div>
            </div>
        </div>
    </div>

    <!-- Render 3D Visual Showcase -->
    <div class="showcase-wrapper">
        <div class="showcase-glow"></div>
        <div class="showcase-frame">
            <img src="{{ asset('images/render-braille-hero.jpg') }}" alt="Render 3D de celda táctil Braille Táctil3D" class="showcase-img" onerror="this.src='{{ asset('images/cubo-tactil3d.jpg') }}'">

            <div class="showcase-floating-badge">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 28px; height: 28px;">
                        <x-logo-svg size="28" />
                    </div>
                    <div>
                        <div style="font-size: 0.85rem; font-weight: 700; letter-spacing: 0.5px;">TÁCTIL<span style="color: #10b981;">3D</span></div>
                        <div style="font-size: 0.65rem; color: #94a3b8; text-transform: uppercase;">Celda ONCE · Impresión CNC</div>
                    </div>
                </div>
                <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 0.72rem; padding: 3px 8px; border-radius: 999px; font-weight: 700;">Grado 1</span>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Pilares del Proyecto -->
<div class="section-pills">
    <div class="section-intro">
        <h2>Pilares de Innovación Social</h2>
        <p>Una solución integral que combina tecnología accesible, inclusión real y economía circular.</p>
    </div>

    <div class="pillars-grid">
        <div class="pillar-card">
            <div class="pillar-icon">👨‍🦯</div>
            <h3>Inclusión Educativa Real</h3>
            <p>Diseñado especialmente para estudiantes con discapacidad visual y educadores, facilitando el aprendizaje de matemáticas, geografía y ciencias mediante el tacto.</p>
        </div>

        <div class="pillar-card">
            <div class="pillar-icon">♻️</div>
            <h3>Economía Circular (RAEE)</h3>
            <p>Aprovechamos chatarra electrónica de impresoras y escáneres en desuso para ensamblar nuestras máquinas CNC/3D, reduciendo la contaminación ambiental.</p>
        </div>

        <div class="pillar-card">
            <div class="pillar-icon">⚡</div>
            <h3>Traducción Directa a G-Code</h3>
            <p>Algoritmo desarrollado en PHP puro que convierte directamente el texto estándar a coordenadas de impresión y relieve Braille sin herramientas costosas.</p>
        </div>
    </div>
</div>

<!-- Flujo Paso a Paso -->
<div class="flow-section">
    <div style="text-align: center; max-width: 600px; margin: 0 auto;">
        <h2 style="font-family: var(--font-display); font-size: 1.85rem; color: var(--tinta); margin: 0 0 8px; font-weight: 800;">¿Cómo Funciona el Proceso?</h2>
        <p style="color: var(--tinta-suave); margin: 0;">Desde la solicitud digital hasta el material táctil terminado en el aula.</p>
    </div>

    <div class="flow-steps">
        <div class="flow-step">
            <div class="step-num">01</div>
            <h4>Selección en Catálogo</h4>
            <p>El docente o solicitante explora los recursos didácticos y examina la pieza mediante el visor interactivo 3D.</p>
        </div>
        <div class="flow-step">
            <div class="step-num">02</div>
            <h4>Generación Braille</h4>
            <p>El sistema genera las trayectorias de impresión y calcula los parámetros óptimos de filamento y relieve táctil.</p>
        </div>
        <div class="flow-step">
            <div class="step-num">03</div>
            <h4>Impresión y Entrega</h4>
            <p>La pieza es manufacturada en la impresora 3D comunitaria y entregada para su uso inmediato en clases.</p>
        </div>
    </div>
</div>

<!-- Banner Final de Registro / Exploración -->
<div class="cta-banner">
    <h2>Democratizando el Aprendizaje Táctil</h2>
    <p>Solicita material educativo para tu institución o coordina un pedido especial con el equipo de desarrollo.</p>
    <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
        @auth
            <a href="{{ route('recursos.index') }}" class="btn-cta-white">
                Ir al Catálogo de Recursos →
            </a>
        @else
            <a href="{{ route('register') }}" class="btn-cta-white">
                Crear Cuenta Gratuita →
            </a>
        @endauth
        <a href="https://wa.me/59160774117?text=Hola,%20me%20comunico%20desde%20la%20p%C3%A1gina%20principal%20de%20T%C3%A1ctil3D." target="_blank" class="btn-cta-white" style="background: #25D366; color: white !important;">
            <span>💬</span> WhatsApp (+591 60774117)
        </a>
    </div>
</div>
@endsection
