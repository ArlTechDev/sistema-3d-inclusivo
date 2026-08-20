FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    curl \
    wget \
    # OpenSCAD + dependencias headless
    openscad \
    xvfb \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PrusaSlicer AppImage (sin FUSE, sin privileged)
ARG PRUSA_VERSION=2.7.4
RUN wget -q "https://github.com/prusa3d/PrusaSlicer/releases/download/version_${PRUSA_VERSION}/PrusaSlicer-${PRUSA_VERSION}+linux-x64-GTK3-202404050928.AppImage" \
      -O /usr/local/bin/prusa-slicer.AppImage \
    && chmod +x /usr/local/bin/prusa-slicer.AppImage \
    && printf '#!/bin/bash\n/usr/local/bin/prusa-slicer.AppImage --appimage-extract-and-run "$@"\n' \
         > /usr/local/bin/prusa-slicer \
    && chmod +x /usr/local/bin/prusa-slicer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
