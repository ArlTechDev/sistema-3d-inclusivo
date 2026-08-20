@extends('layouts.app')

@section('titulo', 'Mis solicitudes de impresión')

@section('contenido')
    <style>
        .tabla-pedidos {
            max-width: 860px;
            margin: 0 auto;
        }
        .tabla-pedidos h1 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            margin: 0 0 4px;
        }
        .tabla-pedidos .sub {
            color: var(--texto-suave, #6b7280);
            margin: 0 0 20px;
        }
        .tabla-pedidos table {
            width: 100%;
            border-collapse: collapse;
            background: var(--blanco);
            border: 1px solid var(--linea);
            border-radius: var(--radio);
            box-shadow: var(--sombra);
            overflow: hidden;
        }
        .tabla-pedidos th,
        .tabla-pedidos td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--linea);
            text-align: left;
            font-size: 0.92rem;
        }
        .tabla-pedidos th {
            background: var(--fondo-suave, #f3f4f6);
        }
        .etiqueta {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .etiqueta-pendiente   { background: #fef3c7; color: #92400e; }
        .etiqueta-aprobado    { background: #e0f2fe; color: #0369a1; }
        .etiqueta-impresion   { background: #dbeafe; color: #1e40af; }
        .etiqueta-completado  { background: #d1fae5; color: #065f46; }
        .etiqueta-rechazado   { background: #fee2e2; color: #991b1b; }
        .etiqueta-cancelada   { background: #e5e7eb; color: #374151; }
        .motivo { color: #6b7280; font-size: 0.85rem; }
        .acciones form { display: inline; }
        .boton-cancelar {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: var(--radio);
            padding: 5px 12px;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .boton-cancelar:hover { background: #fecaca; }
        .vacio { text-align: center; color: #6b7280; padding: 32px 0; }
    </style>

    <div class="tabla-pedidos">
        <h1>Mis solicitudes de impresión</h1>
        <p class="sub">Sigue el estado de tus solicitudes. Puedes cancelar las que estén en «Pendiente».</p>

        @if (session('success'))
            <p class="aviso-ok" role="status">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
            <div class="errores" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($pedidos->isEmpty())
            <p class="vacio">Aún no has realizado solicitudes de impresión.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Recurso</th>
                        <th>Fecha</th>
                        <th>Gramos</th>
                        <th>Costo (Bs)</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->detalles->first()?->recurso?->titulo ?? '—' }}</td>
                            <td>{{ $pedido->fecha_solicitud->format('d/m/Y') }}</td>
                            <td>{{ number_format($pedido->total_gramos_pla, 2) }}</td>
                            <td>{{ number_format($pedido->costo_total, 2) }}</td>
                            <td>
                                @if ($pedido->trashed())
                                    <span class="etiqueta etiqueta-cancelada">Cancelada</span>
                                @else
                                     @php $estado = $pedido->estado; @endphp
                                     <span class="etiqueta etiqueta-{{ $estado === \App\Models\Pedido::ESTADO_EN_IMPRESION ? 'impresion' : ($estado === \App\Models\Pedido::ESTADO_APROBADO ? 'aprobado' : strtolower($estado)) }}">
                                         {{ $estado }}
                                     </span>
                                     @if ($pedido->motivo_rechazo)
                                         <div class="motivo">Motivo: {{ $pedido->motivo_rechazo }}</div>
                                     @endif
                                 @endif
                             </td>
                             <td class="acciones">
                                 @if (! $pedido->trashed() && $pedido->estado === \App\Models\Pedido::ESTADO_PENDIENTE)
                                    <form method="POST" action="{{ route('pedidos.cancelar', $pedido) }}"
                                          onsubmit="return confirm('¿Cancelar esta solicitud?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="boton-cancelar">Cancelar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
