@extends('layouts.app')
@section('titulo', 'Ayuda y Contacto')

@section('contenido')
<style>
    .help-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .help-header h1 {
        font-family: var(--font-display);
        color: var(--verde-oscuro);
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .faq-container {
        max-width: 800px;
        margin: 0 auto 3rem auto;
    }
    .faq-item {
        background: var(--blanco);
        border: 1px solid var(--linea);
        border-radius: var(--radio);
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .faq-question {
        padding: 1.2rem 1.5rem;
        font-weight: 600;
        color: var(--tinta);
        background: var(--papel);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0;
    }
    .faq-answer {
        padding: 1.5rem;
        color: var(--tinta-suave);
        border-top: 1px solid var(--linea);
        line-height: 1.6;
    }

    /* Tarjeta de Contacto Directo */
    .contact-card {
        background: linear-gradient(135deg, var(--verde) 0%, var(--verde-oscuro) 100%);
        color: white;
        padding: 36px 28px;
        border-radius: 20px;
        text-align: center;
        max-width: 720px;
        margin: 0 auto;
        box-shadow: var(--sombra);
    }
    .contact-card h2 {
        margin-top: 0;
        color: white;
        font-family: var(--font-display);
        font-size: 1.9rem;
    }
    .contact-card p {
        color: rgba(255,255,255,0.92);
        margin-bottom: 1.8rem;
        font-size: 1.02rem;
        line-height: 1.55;
    }
    .contact-buttons-group {
        display: flex;
        gap: 14px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn-whatsapp {
        background: #25D366;
        color: white !important;
        padding: 12px 24px;
        border-radius: var(--radio);
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-whatsapp:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(37, 211, 102, 0.5);
        text-decoration: none;
    }
    .contact-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.2);
        text-align: left;
    }
    .info-item {
        font-size: 0.88rem;
        color: rgba(255,255,255,0.9);
    }
    .info-item strong {
        display: block;
        color: white;
        font-size: 0.95rem;
        margin-bottom: 2px;
    }
</style>

<div class="help-header">
    <h1>Centro de Ayuda y Soporte</h1>
    <p style="color: var(--tinta-suave); max-width: 600px; margin: 0 auto;">Encuentra respuestas rápidas o coordina pedidos especiales directamente con el equipo desarrollador del proyecto.</p>
</div>

<div class="faq-container">
    <h2 style="font-family: var(--font-display); color: var(--tinta); margin-bottom: 1.5rem; font-size: 1.6rem;">Preguntas Frecuentes (FAQ)</h2>

    <div class="faq-item">
        <h3 class="faq-question">¿Quién puede solicitar material didáctico?</h3>
        <div class="faq-answer">
            El sistema está abierto a docentes, instituciones de educación especial, estudiantes y público interesado. Puedes registrarte gratuitamente para acceder a las fichas didácticas, placas y modelos tridimensionales en relieve Braille.
        </div>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">¿Tienen algún costo las impresiones 3D?</h3>
        <div class="faq-answer">
            Las piezas del catálogo están subvencionadas gracias al modelo de economía circular del proyecto, financiado mediante la recolección, reacondicionamiento y reciclaje de Chatarra Electrónica (RAEE) en el <strong>Instituto Técnico "Federico Álvarez Plata" Nocturno</strong>.
        </div>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">¿Cuánto tiempo tarda en procesarse una solicitud?</h3>
        <div class="faq-answer">
            El tiempo de impresión y preparación es generalmente de 3 a 5 días hábiles, dependiendo del volumen de filamento y la demanda en taller. Puedes hacer seguimiento en tiempo real desde tu panel en <em>"Mis Solicitudes"</em>.
        </div>
    </div>

    <div class="faq-item">
        <h3 class="faq-question">¿Cómo coordino un pedido grande por lote o una pieza personalizada?</h3>
        <div class="faq-answer">
            Para lotes institucionales (más de 10 piezas) o modelos con texto Braille específico fuera de catálogo, puedes contactar de forma directa al equipo de desarrollo por WhatsApp para coordinar medidas, parámetros de G-Code y plazos de entrega.
        </div>
    </div>
</div>

<!-- Tarjeta de Contacto Directo con el Equipo -->
<div class="contact-card">
    <h2>¿Necesitas un Pedido Especial o Donar Chatarra?</h2>
    <p>Coordina directamente con el equipo desarrollador para solicitudes por lote, material educativo personalizado o convenios institucionales.</p>

    <div class="contact-buttons-group">
        <a href="https://wa.me/59160774117?text=Hola,%20me%20contacto%20a%20trav%C3%A9s%20del%20Sistema%20Braille%20Inclusivo.%20Quisiera%20consultar%20sobre%20un%20pedido%20especial%20de%20material%20did%C3%A1ctico%20/%20donaci%C3%B3n%20de%20chatarra." target="_blank" class="btn-whatsapp">
            <span>💬</span> Contactar por WhatsApp (+591 60774117)
        </a>
    </div>

    <div class="contact-info-grid">
        <div class="info-item">
            <strong>Atención y Soporte:</strong>
            Equipo de Desarrollo del Proyecto
        </div>
        <div class="info-item">
            <strong>Teléfono / WhatsApp:</strong>
            +591 60774117
        </div>
        <div class="info-item">
            <strong>Institución:</strong>
            Inst. Técnico "Federico Álvarez Plata" Nocturno
        </div>
        <div class="info-item">
            <strong>Ubicación:</strong>
            Av. Ayacucho esq. Jordán, Cochabamba – Bolivia
        </div>
    </div>
</div>

@endsection
