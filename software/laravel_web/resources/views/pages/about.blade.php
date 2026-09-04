@extends('layouts.app')
@section('titulo', 'Acerca del Proyecto')

@section('contenido')
<style>
    .about-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .about-header h1 {
        font-family: var(--font-display);
        color: var(--verde-oscuro);
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .about-header p {
        font-size: 1.15rem;
        color: var(--tinta-suave);
        max-width: 750px;
        margin: 0 auto;
        line-height: 1.6;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3.5rem;
    }
    .stat-card {
        background: var(--blanco);
        border: 1px solid var(--linea);
        border-radius: var(--radio);
        padding: 2rem;
        text-align: center;
        box-shadow: var(--sombra);
        transition: transform 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
    }
    .stat-icon {
        font-size: 2.8rem;
        margin-bottom: 0.75rem;
    }
    .stat-value {
        font-family: var(--font-mono);
        font-size: 2.3rem;
        font-weight: 800;
        color: var(--verde);
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .stat-label {
        font-size: 0.88rem;
        color: var(--tinta-suave);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }
    .section-title {
        font-family: var(--font-display);
        color: var(--tinta);
        font-size: 1.65rem;
        margin-bottom: 1rem;
        position: relative;
    }

    /* Equipo */
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
        margin-bottom: 3.5rem;
    }
    .team-card {
        background: var(--blanco);
        border: 1px solid var(--linea);
        border-radius: var(--radio);
        padding: 24px;
        text-align: center;
        box-shadow: var(--sombra);
        transition: transform 0.2s ease;
    }
    .team-card:hover {
        transform: translateY(-4px);
    }
    .team-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--verde) 0%, var(--verde-oscuro) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0 auto 16px;
    }
    .team-card h3 {
        margin: 0 0 6px 0;
        font-size: 1.1rem;
        color: var(--tinta);
        font-family: var(--font-display);
    }
    .team-degree {
        font-family: var(--font-mono);
        font-size: 0.76rem;
        color: var(--verde);
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .team-role {
        margin: 0;
        color: var(--tinta-suave);
        font-size: 0.88rem;
        line-height: 1.45;
    }

    .cta-team-contact {
        background: var(--papel);
        border: 1px solid var(--linea);
        border-radius: var(--radio);
        padding: 28px;
        text-align: center;
        margin-top: 2rem;
    }
</style>

<div class="about-header">
    <h1>Inclusión a través de la Innovación</h1>
    <p>
        El <strong>Sistema Braille Inclusivo</strong> es un Proyecto Sociocomunitario Productivo (PSCP) desarrollado en el
        <strong>Instituto Técnico "Federico Álvarez Plata" Nocturno (Cochabamba – Bolivia)</strong> para democratizar el acceso a recursos didácticos tridimensionales y en relieve Braille.
    </p>
</div>

<!-- Panel de Métricas de Impacto -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" aria-hidden="true">♻️</div>
        <div class="stat-value">15 kg</div>
        <div class="stat-label">Chatarra Electrónica Reciclada</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" aria-hidden="true">🖨️</div>
        <div class="stat-value">120+</div>
        <div class="stat-label">Piezas Táctiles Producidas</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" aria-hidden="true">👨‍🦯</div>
        <div class="stat-value">5</div>
        <div class="stat-label">Instituciones Beneficiadas</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" aria-hidden="true">🏷️</div>
        <div class="stat-value">0.00 Bs</div>
        <div class="stat-label">Costo para Solicitantes</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem; margin-bottom: 3.5rem;">
    <div style="background: var(--blanco); padding: 28px; border-radius: var(--radio); border: 1px solid var(--linea);">
        <h2 class="section-title">Nuestra Misión</h2>
        <p style="color: var(--tinta-suave); line-height: 1.6; margin: 0;">
            Proveer a educadores, estudiantes e instituciones de educación especial de herramientas didácticas tridimensionales duraderas.
            Utilizamos tecnología de impresión 3D FDM para traducir textos y conceptos científicos a Braille Grado 1 (según la norma de la Comisión Braille Española / ONCE) y formas palpables, derribando las barreras del aprendizaje visual.
        </p>
    </div>
    <div style="background: var(--blanco); padding: 28px; border-radius: var(--radio); border: 1px solid var(--linea);">
        <h2 class="section-title">Economía Circular (RAEE)</h2>
        <p style="color: var(--tinta-suave); line-height: 1.6; margin: 0;">
            El proyecto promueve la sostenibilidad activa mediante el rescate y reacondicionamiento de Residuos de Aparatos Eléctricos y Electrónicos (RAEE).
            Los motores paso a paso, fuentes de poder de 12V y varillas calibradas extraídos de impresoras y escáneres en desuso son recuperados para ensamblar impresoras 3D cartesianas comunitarias, minimizando el impacto ecológico.
        </p>
    </div>
</div>

<!-- Equipo de Desarrollo Real -->
<h2 class="section-title" style="text-align: center;">Equipo de Desarrollo (Postulantes al Título)</h2>
<p style="text-align: center; color: var(--tinta-suave); margin-top: -8px;">
    Carrera de Informática · Instituto Técnico "Federico Álvarez Plata" Nocturno
</p>

<div class="team-grid">
    <div class="team-card">
        <div class="team-avatar" aria-hidden="true">AR</div>
        <h3>Rosales Mamani Ariel Edson</h3>
        <div class="team-degree">Postulante al Título</div>
        <p class="team-role">Software Backend, Arquitectura de Base de Datos y Algoritmo de Traducción Braille a G-Code en PHP.</p>
    </div>

    <div class="team-card">
        <div class="team-avatar" aria-hidden="true">CA</div>
        <h3>Aguilar Castellon Cristhian Alessandro</h3>
        <div class="team-degree">Postulante al Título</div>
        <p class="team-role">Hardware, Electromecánica, Calibración de Firmware Marlin (RAMPS/Mega) y Ensamblaje CNC 3D.</p>
    </div>

    <div class="team-card">
        <div class="team-avatar" aria-hidden="true">JM</div>
        <h3>Aramayo Eguino Jose Matias</h3>
        <div class="team-degree">Postulante al Título</div>
        <p class="team-role">Software Frontend, Diseño UI/UX, Accesibilidad Web (WCAG AAA) y Validación con Usuarios.</p>
    </div>
</div>

<div class="cta-team-contact">
    <h3 style="margin: 0 0 8px 0; font-family: var(--font-display); color: var(--tinta);">¿Deseas contactar directamente con el equipo?</h3>
    <p style="color: var(--tinta-suave); margin: 0 0 16px 0; font-size: 0.95rem;">Estamos disponibles para coordinar solicitudes por lote, demostraciones o convenios de donación de chatarra.</p>
    <a rel="noopener noreferrer" aria-label="Coordinar con equipo de desarrollo por WhatsApp" href="https://wa.me/59160774117?text=Hola,%20me%20comunico%20para%20coordinar%20con%20el%20Equipo%20de%20Desarrollo%20del%20Sistema%20Braille%20Inclusivo." target="_blank" class="boton" style="background: #25D366; text-decoration: none;">
        <span>💬</span> WhatsApp Directo (+591 60774117)
    </a>
</div>

@endsection
