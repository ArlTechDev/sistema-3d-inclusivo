@extends('layouts.app')

@section('titulo', 'Mis solicitudes de impresión')

@section('contenido')
    <style>
        .pedidos-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .pedidos-header h1 {
            font-family: var(--font-display);
            font-size: 2rem;
            color: var(--verde-oscuro);
            margin: 0 0 8px;
        }
        .pedidos-header p {
            color: var(--tinta-suave);
            margin: 0 0 32px;
        }
        .pedido-card {
            background: var(--blanco);
            border: 1px solid var(--linea);
            border-radius: var(--radio);
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--sombra);
        }
        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--linea);
            padding-bottom: 16px;
        }
        .pedido-titulo {
            font-family: var(--font-display);
            font-size: 1.25rem;
            color: var(--tinta);
            margin: 0 0 4px;
            font-weight: 700;
        }
        .pedido-meta {
            font-family: var(--font-mono);
            font-size: 0.85rem;
            color: var(--tinta-suave);
        }
        /* Línea de tiempo */
        .timeline {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 32px 0 16px;
            padding-top: 10px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            top: 22px;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--linea);
            z-index: 1;
            border-radius: 2px;
        }
        .timeline-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }
        .timeline-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--linea);
            color: var(--tinta-suave);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-size: 0.8rem;
            font-weight: bold;
            border: 4px solid var(--blanco);
            transition: all 0.3s ease;
        }
        .timeline-label {
            font-size: 0.85rem;
            color: var(--tinta-suave);
            font-weight: 600;
        }

        /* Estados Activos / Completados */
        .step-done .timeline-icon {
            background: var(--verde);
            color: white;
        }
        .step-done .timeline-label {
            color: var(--verde);
        }
        .step-active .timeline-icon {
            background: var(--ambar);
            color: white;
            box-shadow: 0 0 0 4px rgba(180, 83, 9, 0.2);
        }
        .step-active .timeline-label {
            color: var(--ambar-oscuro);
        }
        .step-rejected .timeline-icon {
            background: #ef4444;
            color: white;
        }
        .step-rejected .timeline-label {
            color: #ef4444;
        }

        .pedido-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .boton-cancelar {
            background: transparent;
            color: #ef4444;
            border: 1px solid #fca5a5;
            border-radius: var(--radio);
            padding: 8px 16px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .boton-cancelar:hover {
            background: #fef2f2;
            border-color: #ef4444;
        }
        .vacio { text-align: center; color: var(--tinta-suave); padding: 48px 0; font-size: 1.1rem; }
    </style>

    <div class="pedidos-container" role="region" aria-label="Historial de solicitudes de material táctil">
        <div class="pedidos-header">
            <h1>Mis Solicitudes</h1>
            <p>Rastrea el progreso de tus impresiones en tiempo real.</p>
        </div>

        @if (session('success'))
            <div role="alert" style="background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: var(--radio); margin-bottom: 24px;">
                {{ session('success') }}
            </div>
        @endif

        @if ($pedidos->isEmpty())
            <div class="pedido-card vacio">
                Aún no has realizado solicitudes de impresión. <br>
                <a href="{{ route('recursos.index') }}" style="color: var(--verde); font-weight: bold; display: inline-block; margin-top: 12px;">Explorar Catálogo</a>
            </div>
        @else
            @foreach ($pedidos as $pedido)
                @php
                    $estado = $pedido->estado;
                    $esCancelado = $pedido->trashed();

                    // Lógica para determinar el progreso (1 a 4)
                    $progreso = 0;
                    if ($estado === \App\Models\Pedido::ESTADO_PENDIENTE) $progreso = 1;
                    if ($estado === \App\Models\Pedido::ESTADO_APROBADO) $progreso = 2;
                    if ($estado === \App\Models\Pedido::ESTADO_EN_IMPRESION) $progreso = 3;
                    if ($estado === \App\Models\Pedido::ESTADO_COMPLETADO) $progreso = 4;
                @endphp

                <div class="pedido-card">
                    <div class="pedido-header">
                        <div>
                            <h2 class="pedido-titulo">{{ $pedido->detalles->first()?->recurso?->titulo ?? 'Recurso Eliminado' }}</h2>
                            <div class="pedido-meta">
                                Solicitado el {{ $pedido->fecha_solicitud->format('d/m/Y') }} •
                                {{ number_format($pedido->total_gramos_pla, 0) }}g PLA •
                                Bs. {{ number_format($pedido->costo_total, 2) }}
                            </div>
                        </div>
                        @if ($esCancelado)
                            <span style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 999px; font-weight: 600; font-size: 0.85rem;">Cancelado</span>
                        @elseif ($estado === \App\Models\Pedido::ESTADO_RECHAZADO)
                            <span style="background: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 999px; font-weight: 600; font-size: 0.85rem;">Rechazado</span>
                        @else
                            <span style="background: var(--verde); color: white; padding: 6px 12px; border-radius: 999px; font-weight: 600; font-size: 0.85rem;">{{ $estado }}</span>
                        @endif
                    </div>

                    @if (!$esCancelado && $estado !== \App\Models\Pedido::ESTADO_RECHAZADO)
                        <div class="timeline">
                            <div class="timeline-step {{ $progreso >= 1 ? ($progreso > 1 ? 'step-done' : 'step-active') : '' }}">
                                <div class="timeline-icon">✓</div>
                                <div class="timeline-label">Pendiente</div>
                            </div>
                            <div class="timeline-step {{ $progreso >= 2 ? ($progreso > 2 ? 'step-done' : 'step-active') : '' }}">
                                <div class="timeline-icon">✓</div>
                                <div class="timeline-label">Aprobado</div>
                            </div>
                            <div class="timeline-step {{ $progreso >= 3 ? ($progreso > 3 ? 'step-done' : 'step-active') : '' }}">
                                <div class="timeline-icon">⚙</div>
                                <div class="timeline-label">En Impresión</div>
                            </div>
                            <div class="timeline-step {{ $progreso >= 4 ? 'step-done' : '' }}">
                                <div class="timeline-icon">📦</div>
                                <div class="timeline-label">Completado</div>
                            </div>
                        </div>
                    @endif

                    @if ($estado === \App\Models\Pedido::ESTADO_RECHAZADO && $pedido->motivo_rechazo)
                        <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 12px; margin-top: 16px; border-radius: 4px;">
                            <strong>Motivo del rechazo:</strong> {{ $pedido->motivo_rechazo }}
                        </div>
                    @endif

                    @if (! $pedido->trashed() && $pedido->estado === \App\Models\Pedido::ESTADO_PENDIENTE)
                        <div class="pedido-footer" style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                            <a href="{{ route('pedidos.checkout', $pedido) }}" style="display: inline-flex; align-items: center; gap: 6px; background: var(--verde); color: #ffffff; padding: 8px 16px; border-radius: var(--radio); font-size: 0.9rem; font-weight: 600; text-decoration: none; box-shadow: 0 2px 6px rgba(13, 148, 136, 0.2);">
                                <i class="fas fa-qrcode"></i> Pagar con QR / Enviar Comprobante
                            </a>
                            <form method="POST" action="{{ route('pedidos.cancelar', $pedido) }}" onsubmit="return confirm('¿Estás seguro de cancelar esta solicitud?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="boton-cancelar">Cancelar Solicitud</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
@endsection
