@props(['estado'])

@php
    $mapa = [
        \App\Models\Pedido::ESTADO_PENDIENTE => ['bg' => 'badge-warning', 'texto' => 'Pendiente'],
        \App\Models\Pedido::ESTADO_APROBADO => ['bg' => 'badge-info', 'texto' => 'Aprobado'],
        \App\Models\Pedido::ESTADO_EN_IMPRESION => ['bg' => 'badge-primary', 'texto' => 'En Impresión'],
        \App\Models\Pedido::ESTADO_COMPLETADO => ['bg' => 'badge-success', 'texto' => 'Completado'],
        \App\Models\Pedido::ESTADO_RECHAZADO => ['bg' => 'badge-danger', 'texto' => 'Rechazado'],
    ];
    $badge = $mapa[$estado] ?? ['bg' => 'badge-secondary', 'texto' => $estado];
@endphp

<span class="badge {{ $badge['bg'] }}" aria-label="Estado de solicitud: {{ $badge['texto'] }}">
    {{ $badge['texto'] }}
</span>
