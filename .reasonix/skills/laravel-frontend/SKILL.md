---
name: laravel-frontend
description: Estándares de frontend para Laravel Blade (arquitectura dual app.blade.php vs admin.blade.php, componentes Blade, accesibilidad WCAG AAA / Braille, diseño responsivo, cero dependencias SPA).
---

# Skill: laravel-frontend — Estándares de Frontend Laravel & Blade

## Propósito
Guiar la creación, diseño y modificación de interfaces de usuario (UI/UX) en `software/laravel_web/`. Garantizar coherencia visual, accesibilidad para personas con discapacidad visual y cumplimiento de la arquitectura dual (Pública/Solicitante vs Administración).

---

## 1. Arquitectura Dual de Layouts

El proyecto cuenta con dos entornos de interfaz claramente delimitados:

### A. Área Pública y del Solicitante (`resources/views/layouts/app.blade.php`)
- **Destinatarios**: Solicitantes, docentes, estudiantes, público general.
- **Vistas**: Catálogo de recursos táctiles (`recursos.index`, `recursos.show`), mis solicitudes (`pedidos.mis`), formulario de pedido.
- **Tecnología**: **CSS nativo puro** con variables y tokens en `:root`, sin dependencias pesadas de frameworks ni CDN. Autocontenido para despliegues offline.
- **Firma Visual del Proyecto**:
  - Marca con isotipo SVG de **celda Braille de 6 puntos**.
  - Paleta: Papel fresco (`--papel: #F5F7F6`), Tinta (`--tinta: #1E2A32`), Verde reciclado (`--verde: #146C5A`) y Ámbar de filamento (`--ambar: #B45309`).
  - Tipografías: Display (`Georgia, serif`), Cuerpo (`system-ui, sans-serif`), Monospace (`ui-monospace, monospace`).

### B. Área de Administración (`resources/views/layouts/admin.blade.php`)
- **Destinatarios**: Administrador del sistema.
- **Vistas**: CRUDs de gestión, usuarios, pedidos globales, configuración del sistema, papelera (`trash/restore`), exportaciones y descarga de archivos `.gcode`.
- **Tecnología**: AdminLTE 3 (`jeroennoten/laravel-adminlte`) + Bootstrap 5.
- **Regla**: Las vistas de administración heredan exclusivamente de `@extends('layouts.admin')` (wrapper estandarizado) y definen `@section('title')`, `@section('content_header')` y `@section('content')`. **Nunca extender directamente de `adminlte::page` en nuevas vistas.**

---

## 2. Pautas de Accesibilidad e Inclusión (WCAG 2.1 AAA)

Dado el objetivo sociocomunitario de inclusión educativa y ceguera/baja visión:

1. **Navegación por Teclado**:
   - Elemento de "Salto al contenido principal" (`.salto:focus-visible`).
   - `:focus-visible` claramente contrastado con contorno de 3px (`outline: 3px solid var(--verde); outline-offset: 2px`).
2. **Respeto a Preferencias del Usuario**:
   - Soporte para `@media (prefers-reduced-motion: reduce)` desactivando animaciones y transiciones no esenciales.
3. **Formularios Accesibles**:
   - Todo `<input>` debe tener su `<label>` asociado con `for` / `id`.
   - Errores de validación vinculados semánticamente mediante `aria-invalid="true"` y `aria-describedby="[input-id]-error"`.
4. **Semántica y Contraste**:
   - Elementos interactivos con contraste mínimo de 4.5:1 (texto normal) y 7:1 (texto destacado).
   - Uso de atributos `aria-label` y `aria-hidden="true"` en elementos puramente decorativos o SVGs.

---

## 3. Componentes Blade y Código Limpio

- **Componentes Blade**: Organizar componentes reutilizables en `resources/views/components/` (ej. alertas, badges de estado, tarjetas de recurso táctil, visores 3D).
- **Control de Estados con Constantes**:
  - En vistas Blade, comparar estados usando las constantes del modelo (ej. `@if($pedido->estado === \App\Models\Pedido::ESTADO_PENDIENTE)`), nunca cadenas mágicas.
- **Formularios Seguros**:
  - Directiva `@csrf` obligatoria en todo formulario POST/PUT/DELETE.
  - `@method('PUT')` / `@method('DELETE')` para verbos RESTful.
  - Sanitización en el backend vía Form Requests + `Sanitizer::cleanArray()`.

---

## 4. Filosofía de JavaScript

- **Renderizado Server-Side (SSR) Puro**: El renderizado principal es 100% Blade.
- **Prohibición de SPAs pesadas**: No incorporar React, Vue ni frameworks SPA complejos que rompan el modelo offline o la simplicidad del mantenimiento.
- **Interactividad Ligera**:
  - JavaScript vanilla modular para utilidades concretas (ej. previsualizador `<model-viewer>` para modelos 3D).
  - Micro-interacciones ligeras.

---

## 5. Idioma y Formato
- Todos los textos de la interfaz, etiquetas, placeholders, botones y notificaciones deben estar en **español neutro**.
- Fechas y números formateados según la convención local en español.

## Fuentes
`AGENTS.md` · `software/laravel_web/resources/views/layouts/app.blade.php` · `software/laravel_web/resources/views/layouts/admin.blade.php`.
