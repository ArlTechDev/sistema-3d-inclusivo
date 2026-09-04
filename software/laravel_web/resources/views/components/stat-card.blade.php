@props([
    'icono' => '📊',
    'valor' => '0',
    'etiqueta' => '',
])

<div class="stat-card" role="region" aria-label="Indicador: {{ $etiqueta }}">
    <div class="stat-icon" aria-hidden="true">{{ $icono }}</div>
    <div class="stat-value">{{ $valor }}</div>
    <div class="stat-label">{{ $etiqueta }}</div>
</div>
