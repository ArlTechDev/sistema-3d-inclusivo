@props(['recurso'])

<article class="tarjeta-recurso" aria-label="Recurso didáctico: {{ $recurso->titulo }}">
    <div class="tarjeta-img-contenedor">
        @if($recurso->url_imagen)
            <img src="{{ asset('storage/' . $recurso->url_imagen) }}" alt="Fotografía táctil de {{ $recurso->titulo }}" loading="lazy">
        @else
            <div class="placeholder-tactil" aria-hidden="true">🧊</div>
        @endif
    </div>
    <div class="tarjeta-cuerpo">
        <span class="categoria-badge">{{ $recurso->categoria->nombre ?? 'General' }}</span>
        <h3>{{ $recurso->titulo }}</h3>
        <p>{{ Str::limit($recurso->descripcion, 90) }}</p>
    </div>
</article>
