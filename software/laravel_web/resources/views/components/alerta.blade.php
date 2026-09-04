@props([
    'tipo' => 'success',
    'mensaje' => null,
])

@php
    $clases = [
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ][$tipo] ?? 'alert-info';
@endphp

<div {{ $attributes->merge(['class' => 'alert ' . $clases . ' alert-dismissible fade show']) }} role="alert">
    {{ $mensaje ?? $slot }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar notificación">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
