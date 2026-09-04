@extends('layouts.app')

@section('titulo', 'Confirmación y Pago de Solicitud #' . $pedido->id)

@section('contenido')
<style>
    .checkout-wrapper {
        max-width: 1000px;
        margin: 0 auto 60px;
    }

    .checkout-steps {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        margin-bottom: 36px;
        font-family: var(--font-mono);
        font-size: 0.88rem;
    }

    .step-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 999px;
        background: var(--papel-oscuro);
        color: var(--tinta-suave);
        border: 1px solid var(--linea);
        font-weight: 600;
    }

    .step-badge.active {
        background: var(--verde);
        color: #ffffff;
        border-color: var(--verde-oscuro);
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
    }

    .step-arrow {
        color: var(--tinta-suave);
        opacity: 0.5;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 28px;
        align-items: start;
    }

    @media (max-width: 860px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Tarjeta Proforma */
    .invoice-card {
        background: var(--blanco);
        border: 1px solid var(--linea);
        border-radius: var(--radio);
        padding: 32px;
        box-shadow: var(--sombra);
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px dashed var(--linea);
        padding-bottom: 20px;
        margin-bottom: 24px;
    }

    .invoice-title {
        font-family: var(--font-display);
        font-size: 1.6rem;
        color: var(--verde-oscuro);
        margin: 0 0 6px;
        font-weight: 800;
    }

    .invoice-number {
        font-family: var(--font-mono);
        font-size: 0.95rem;
        color: var(--tinta-suave);
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .invoice-table th {
        text-align: left;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--tinta-suave);
        padding-bottom: 10px;
        border-bottom: 1px solid var(--linea);
    }

    .invoice-table td {
        padding: 14px 0;
        border-bottom: 1px solid var(--linea);
        color: var(--tinta);
        font-size: 0.95rem;
    }

    .invoice-total-box {
        background: var(--papel);
        border-radius: var(--radio);
        padding: 18px 20px;
        margin-top: 24px;
        border: 1px solid var(--linea);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 0.92rem;
        color: var(--tinta-suave);
    }

    .total-row.final {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--linea);
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--verde-oscuro);
        font-family: var(--font-display);
    }

    /* Tarjeta QR Simple */
    .qr-card {
        background: var(--blanco);
        border: 2px solid var(--verde);
        border-radius: var(--radio);
        padding: 32px 28px;
        box-shadow: var(--sombra);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .qr-badge-top {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, var(--verde), var(--verde-oscuro));
        color: #ffffff;
        padding: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-family: var(--font-mono);
    }

    .qr-frame {
        margin: 24px auto 18px;
        width: 220px;
        height: 220px;
        background: #ffffff;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid var(--linea);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr-frame img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .qr-meta {
        font-size: 0.88rem;
        color: var(--tinta-suave);
        line-height: 1.5;
        margin-bottom: 24px;
    }

    .btn-whatsapp-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        background: #25D366;
        color: #ffffff;
        font-weight: 700;
        font-size: 1rem;
        padding: 14px 20px;
        border-radius: var(--radio);
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(37, 211, 102, 0.35);
        transition: all 0.2s ease;
        margin-bottom: 12px;
    }

    .btn-whatsapp-action:hover {
        background: #1eb857;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.45);
    }

    .btn-secundario {
        display: block;
        width: 100%;
        text-align: center;
        padding: 10px 16px;
        color: var(--tinta-suave);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: var(--radio);
        border: 1px solid var(--linea);
        background: transparent;
        transition: all 0.2s;
    }

    .btn-secundario:hover {
        background: var(--papel);
        color: var(--tinta);
    }

    .instrucciones-pasos {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 14px;
        margin-top: 20px;
        text-align: left;
        font-size: 0.83rem;
        color: #1e3a8a;
    }

    [data-theme="dark"] .instrucciones-pasos {
        background: rgba(30, 58, 138, 0.25);
        border-color: rgba(96, 165, 250, 0.3);
        color: #93c5fd;
    }
</style>

<div class="checkout-wrapper" role="region" aria-label="Resumen de solicitud y confirmación">
    <!-- Indicador de pasos -->
    <div class="checkout-steps">
        <span class="step-badge">1. Formulario</span>
        <span class="step-arrow">➔</span>
        <span class="step-badge active" aria-current="step">2. Confirmación & Pago</span>
        <span class="step-arrow">➔</span>
        <span class="step-badge">3. Impresión 3D</span>
    </div>

    @if (session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 14px 18px; border-radius: var(--radio); margin-bottom: 24px; font-weight: 500; border-left: 4px solid var(--verde);">
            <i class="fas fa-check-circle" style="margin-right: 8px;"></i> {{ session('success') }}
        </div>
    @endif

    <div class="checkout-grid">
        <!-- Columna Izquierda: Proforma / Resumen del Pedido -->
        <div class="invoice-card">
            <div class="invoice-header">
                <div>
                    <h1 class="invoice-title">Solicitud Registrada</h1>
                    <div class="invoice-number">Orden #{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div style="text-align: right;">
                    <span style="display: inline-block; background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">
                        {{ $pedido->estado }}
                    </span>
                    <div style="font-size: 0.82rem; color: var(--tinta-suave); margin-top: 4px;">
                        {{ $pedido->fecha_solicitud?->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 20px; font-size: 0.92rem; color: var(--tinta-suave);">
                <strong>Solicitante:</strong> {{ $pedido->user?->name }} ({{ $pedido->user?->email }})<br>
                <strong>Destino:</strong> {{ $pedido->institucion?->nombre ?? 'Uso Particular / Docente' }}
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Descripción del Recurso</th>
                        <th style="text-align: center;">Cant.</th>
                        <th style="text-align: right;">PLA Estimado</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedido->detalles as $detalle)
                        <tr>
                            <td>
                                <strong>{{ $detalle->recurso?->titulo ?? 'Recurso Táctil' }}</strong>
                                <div style="font-size: 0.8rem; color: var(--tinta-suave); margin-top: 2px;">
                                    Cat.: {{ $detalle->recurso?->categoria?->nombre ?? 'Material Didáctico' }}
                                    @if($pedido->gcode_path)
                                        · <span style="color: var(--verde);">Texto Braille personalizado integrado</span>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: center; font-weight: 600;">{{ $detalle->cantidad }}</td>
                            <td style="text-align: right; font-family: var(--font-mono); font-size: 0.9rem;">
                                {{ number_format($detalle->gramos_pla, 1) }} g
                            </td>
                            <td style="text-align: right; font-weight: 700; font-family: var(--font-mono);">
                                {{ $moneda }} {{ number_format($detalle->costo_unitario * $detalle->cantidad, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="invoice-total-box">
                <div class="total-row">
                    <span>Peso total filamento PLA reciclado:</span>
                    <strong>{{ number_format($pedido->total_gramos_pla, 1) }} gramos</strong>
                </div>
                <div class="total-row">
                    <span>Costo de material y consumo energético:</span>
                    <span>{{ $moneda }} {{ number_format($pedido->costo_total, 2) }}</span>
                </div>
                <div class="total-row final">
                    <span>Total a Liquidar:</span>
                    <span>{{ $moneda }} {{ number_format($pedido->costo_total, 2) }}</span>
                </div>
            </div>

            <div style="margin-top: 24px; font-size: 0.82rem; color: var(--tinta-suave); line-height: 1.5;">
                <i class="fas fa-shield-alt" style="color: var(--verde); margin-right: 4px;"></i>
                <strong>Modelo Sociocomunitario:</strong> Los fondos cubren el reabastecimiento de insumos y mantenimiento de la impresora 3D sustentable sin fines de lucro.
            </div>
        </div>

        <!-- Columna Derecha: Pago por QR y Notificación WhatsApp -->
        <div class="qr-card">
            <div class="qr-badge-top">Pago Simple QR Bolivia</div>

            <h3 style="font-family: var(--font-display); font-size: 1.25rem; color: var(--tinta); margin: 12px 0 4px; font-weight: 700;">
                Escanea para Transferir
            </h3>
            <p style="font-size: 0.85rem; color: var(--tinta-suave); margin: 0 0 16px;">
                Monto exacto a pagar: <strong style="color: var(--verde-oscuro); font-size: 1.05rem;">{{ $moneda }} {{ number_format($pedido->costo_total, 2) }}</strong>
            </p>

            <!-- Generador de QR dinámico con fallback -->
            <div class="qr-frame">
                @php
                    $qrData = urlencode("PAGO_BRAILLE_ORDEN_" . $pedido->id . "_MONTO_" . $pedido->costo_total . "_INCOS");
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . $qrData . "&color=0f172a&bgcolor=ffffff";
                @endphp
                <img src="{{ $qrUrl }}" alt="Código QR de Pago para Pedido #{{ $pedido->id }}" loading="lazy">
            </div>

            <div class="qr-meta">
                <strong>Destinatario:</strong> Equipo Proyecto Braille 3D<br>
                <strong>Institución:</strong> Álvarez Plata Nocturno<br>
                <small style="color: var(--tinta-suave);">Válido para cualquier app bancaria (BCB / Simple)</small>
            </div>

            @php
                $mensajeWhatsapp = "Hola! Acabo de registrar la solicitud de impresión #{$pedido->id} en el Sistema Braille Inclusivo por un total de {$moneda} " . number_format($pedido->costo_total, 2) . ". Adjunto mi comprobante de transferencia para iniciar la fabricación.";
                $urlWhatsapp = "https://wa.me/{$whatsappNumero}?text=" . rawurlencode($mensajeWhatsapp);
            @endphp

            <a href="{{ $urlWhatsapp }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp-action">
                <i class="fab fa-whatsapp" style="font-size: 1.3rem;"></i>
                <span>Enviar Comprobante por WhatsApp</span>
            </a>

            <a href="{{ route('pedidos.mis') }}" class="btn-secundario">
                Ir a Mis Solicitudes
            </a>

            <div class="instrucciones-pasos">
                <strong>¿Cómo funciona?</strong>
                <ol style="margin: 6px 0 0; padding-left: 18px; line-height: 1.4;">
                    <li>Escanea el QR desde tu banca móvil y realiza el pago.</li>
                    <li>Guarda la captura del comprobante.</li>
                    <li>Toca el botón verde de WhatsApp para enviarlo al equipo.</li>
                    <li>El equipo validará tu pago y comenzará la impresión.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
