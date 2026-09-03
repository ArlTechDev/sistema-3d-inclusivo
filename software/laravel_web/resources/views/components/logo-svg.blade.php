@props(['size' => '100%'])

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="{{ $size }}" height="{{ $size }}" {{ $attributes }}>
  <defs>
    <!-- Superficie Mate del Cubo -->
    <linearGradient id="surfaceMatte" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#2a384e"/>
      <stop offset="50%" stop-color="#1b2536"/>
      <stop offset="100%" stop-color="#0c121e"/>
    </linearGradient>

    <!-- Bisel de los bordes (Neon Cyan) -->
    <linearGradient id="edgeNeon" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#38bdf8"/>
      <stop offset="100%" stop-color="#0284c7"/>
    </linearGradient>

    <!-- Gradiente Radial Óptico para la Esfera -->
    <radialGradient id="sphere3D" cx="35%" cy="25%" r="65%" fx="20%" fy="15%">
      <stop offset="0%" stop-color="#ffffff"/>       <!-- Brillo central intenso -->
      <stop offset="25%" stop-color="#a7f3d0"/>       <!-- Resplandor verde claro -->
      <stop offset="65%" stop-color="#10b981"/>       <!-- Color base esmeralda -->
      <stop offset="100%" stop-color="#064e3b"/>      <!-- Sombra propia oscura -->
    </radialGradient>

    <!-- Interior y Profundidad del Agujero / Socket Hueco -->
    <radialGradient id="holeInterior" cx="50%" cy="30%" r="60%">
      <stop offset="0%" stop-color="#0ea5e9" stop-opacity="0.8"/>
      <stop offset="50%" stop-color="#0369a1"/>
      <stop offset="100%" stop-color="#020d1a"/>
    </radialGradient>

    <!-- Sombra proyectada por la esfera -->
    <filter id="castShadow" x="-30%" y="-30%" width="160%" height="160%">
      <feGaussianBlur stdDeviation="1" result="blur"/>
      <feOffset dx="1" dy="1.5" result="offsetBlur"/>
      <feFlood flood-color="#000000" flood-opacity="0.75"/>
      <feComposite in2="offsetBlur" operator="in"/>
      <feMerge>
        <feMergeNode/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>

    <!-- Sombra general del cubo en el suelo -->
    <filter id="softFloorShadow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur stdDeviation="3.5" result="blur"/>
      <feComposite in="SourceGraphic" in2="blur" operator="over"/>
    </filter>
  </defs>

  <g transform="translate(0, 4)">
    <!-- SOMBRA DE PISO -->
    <ellipse cx="60" cy="98" rx="42" ry="14" fill="#000000" opacity="0.6" filter="url(#softFloorShadow)"/>

    <!-- CARAS LATERALES Y BASE -->
    <path d="M 60 56 L 16 31 L 16 68 L 60 93 Z" fill="#0b1626" stroke="#10b981" stroke-width="1.2" stroke-linejoin="round"/>
    <path d="M 16 40 L 60 65 M 16 49 L 60 74 M 16 58 L 60 83" stroke="#10b981" stroke-width="0.8" stroke-opacity="0.35"/>

    <path d="M 60 56 L 104 31 L 104 68 L 60 93 Z" fill="#0e1b2e" stroke="#00f2fe" stroke-width="1.2" stroke-linejoin="round"/>
    <path d="M 104 40 L 60 65 M 104 49 L 60 74 M 104 58 L 60 83" stroke="#00f2fe" stroke-width="0.8" stroke-opacity="0.35"/>

    <line x1="60" y1="56" x2="60" y2="93" stroke="#38bdf8" stroke-width="1.5" stroke-opacity="0.85"/>

    <!-- CARA SUPERIOR ISOMÉTRICA -->
    <path d="M 60 6 L 104 31 L 60 56 L 16 31 Z" fill="url(#surfaceMatte)" stroke="url(#edgeNeon)" stroke-width="1.8" stroke-linejoin="round"/>

    <!-- ========================================================================= -->
    <!-- MATRIZ BRAILLE 2x3 PROPORCIÓN PERFECTA                                    -->
    <!-- 4 Esferas Activas Esmeralda + 2 Agujeros Huecos Cian Iluminados           -->
    <!-- ========================================================================= -->

    <!-- === COLUMNA 1 (IZQUIERDA) === -->

    <!-- Punto 1 (Activo - Arriba Izq) -->
    <g transform="translate(66, 20.5)">
      <ellipse cx="0" cy="1.2" rx="4.4" ry="2.4" fill="#000" opacity="0.85"/>
      <ellipse cx="0" cy="-0.5" rx="4.6" ry="2.7" fill="url(#sphere3D)" filter="url(#castShadow)"/>
    </g>

    <!-- Punto 2 (Activo - Medio Izq) -->
    <g transform="translate(54, 27.5)">
      <ellipse cx="0" cy="1.2" rx="4.4" ry="2.4" fill="#000" opacity="0.85"/>
      <ellipse cx="0" cy="-0.5" rx="4.6" ry="2.7" fill="url(#sphere3D)" filter="url(#castShadow)"/>
    </g>

    <!-- Punto 3 (Inactivo / Cavidad Hueca - Abajo Izq) -->
    <g transform="translate(42, 34.5)">
      <!-- Sombra exterior de hendidura -->
      <ellipse cx="0" cy="-0.3" rx="4.4" ry="2.5" fill="#000" opacity="0.5"/>
      <!-- Borde exterior iluminado Cian Neón -->
      <ellipse cx="0" cy="0" rx="4.2" ry="2.4" fill="none" stroke="#38bdf8" stroke-width="1.2"/>
      <!-- Fondo y profundidad del socket -->
      <ellipse cx="0" cy="0.4" rx="3.5" ry="1.9" fill="url(#holeInterior)"/>
      <!-- Centro profundo oscuro -->
      <ellipse cx="0" cy="0.7" rx="2.2" ry="1.2" fill="#020814" opacity="0.95"/>
    </g>

    <!-- === COLUMNA 2 (DERECHA) === -->

    <!-- Punto 4 (Activo - Arriba Der) -->
    <g transform="translate(78, 27.5)">
      <ellipse cx="0" cy="1.2" rx="4.4" ry="2.4" fill="#000" opacity="0.85"/>
      <ellipse cx="0" cy="-0.5" rx="4.6" ry="2.7" fill="url(#sphere3D)" filter="url(#castShadow)"/>
    </g>

    <!-- Punto 5 (Activo - Medio Der) -->
    <g transform="translate(66, 34.5)">
      <ellipse cx="0" cy="1.2" rx="4.4" ry="2.4" fill="#000" opacity="0.85"/>
      <ellipse cx="0" cy="-0.5" rx="4.6" ry="2.7" fill="url(#sphere3D)" filter="url(#castShadow)"/>
    </g>

    <!-- Punto 6 (Inactivo / Cavidad Hueca - Abajo Der) -->
    <g transform="translate(54, 41.5)">
      <!-- Sombra exterior de hendidura -->
      <ellipse cx="0" cy="-0.3" rx="4.4" ry="2.5" fill="#000" opacity="0.5"/>
      <!-- Borde exterior iluminado Cian Neón -->
      <ellipse cx="0" cy="0" rx="4.2" ry="2.4" fill="none" stroke="#38bdf8" stroke-width="1.2"/>
      <!-- Fondo y profundidad del socket -->
      <ellipse cx="0" cy="0.4" rx="3.5" ry="1.9" fill="url(#holeInterior)"/>
      <!-- Centro profundo oscuro -->
      <ellipse cx="0" cy="0.7" rx="2.2" ry="1.2" fill="#020814" opacity="0.95"/>
    </g>

  </g>
</svg>
