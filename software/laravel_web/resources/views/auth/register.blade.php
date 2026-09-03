@extends('layouts.app')
@section('titulo', 'Registrarse')

@section('contenido')
<style>
    .auth-wrapper {
        max-width: 960px;
        margin: 24px auto 48px;
        background: var(--blanco);
        border: 1px solid var(--linea);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--sombra);
        display: grid;
        grid-template-columns: 1.1fr 1fr;
    }

    /* Panel Izquierdo: Branding & Misión */
    .auth-branding {
        background: linear-gradient(145deg, rgba(20, 108, 90, 0.08) 0%, rgba(180, 83, 9, 0.06) 100%);
        border-right: 1px solid var(--linea);
        padding: 48px 36px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    [data-theme="dark"] .auth-branding {
        background: linear-gradient(145deg, rgba(16, 185, 129, 0.1) 0%, rgba(245, 158, 11, 0.08) 100%);
    }
    .auth-brand-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        background: var(--blanco);
        border: 1px solid var(--linea);
        color: var(--verde);
        font-family: var(--font-mono);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 24px;
        width: fit-content;
    }
    .auth-branding h2 {
        font-family: var(--font-display);
        font-size: 2rem;
        line-height: 1.25;
        color: var(--tinta);
        margin: 0 0 16px 0;
    }
    .auth-branding p {
        color: var(--tinta-suave);
        font-size: 0.98rem;
        line-height: 1.6;
        margin: 0 0 32px 0;
    }
    .auth-feature-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .auth-feature-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 0.9rem;
        color: var(--tinta);
    }
    .auth-feature-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--blanco);
        border: 1px solid var(--linea);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .auth-brand-footer {
        margin-top: 36px;
        padding-top: 20px;
        border-top: 1px dashed var(--linea);
        font-size: 0.82rem;
        color: var(--tinta-suave);
    }

    /* Panel Derecho: Formulario */
    .auth-form-container {
        padding: 48px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .auth-header-text h1 {
        font-family: var(--font-display);
        font-size: 1.85rem;
        color: var(--tinta);
        margin: 0 0 6px 0;
    }
    .auth-header-text p {
        color: var(--tinta-suave);
        font-size: 0.92rem;
        margin: 0 0 24px 0;
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-label {
        display: block;
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--tinta);
        margin-bottom: 6px;
    }
    .form-control-custom {
        width: 100%;
        padding: 11px 14px;
        border-radius: var(--radio);
        border: 1px solid var(--linea);
        background: var(--papel);
        color: var(--tinta);
        font-family: var(--font-body);
        font-size: 0.92rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-control-custom:focus {
        border-color: var(--verde);
        outline: none;
        box-shadow: 0 0 0 3px rgba(20, 108, 90, 0.15);
    }
    .form-error {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 5px;
        display: block;
    }
    .btn-auth-submit {
        width: 100%;
        background: var(--verde);
        color: white;
        border: none;
        border-radius: var(--radio);
        padding: 13px 20px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.15s ease, transform 0.15s ease;
        margin-top: 12px;
    }
    .btn-auth-submit:hover {
        background: var(--verde-oscuro);
        transform: translateY(-1px);
    }
    .auth-links {
        margin-top: 20px;
        text-align: center;
        font-size: 0.88rem;
        color: var(--tinta-suave);
    }
    .auth-links a {
        color: var(--verde);
        font-weight: 600;
    }
    .auth-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        color: var(--tinta-suave);
        margin-top: 16px;
        text-decoration: none;
    }
    .auth-back-link:hover {
        color: var(--tinta);
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .auth-wrapper {
            grid-template-columns: 1fr;
            margin: 12px auto 32px;
        }
        .auth-branding {
            padding: 32px 24px;
            border-right: none;
            border-bottom: 1px solid var(--linea);
        }
        .auth-form-container {
            padding: 32px 24px;
        }
    }
</style>

<div class="auth-wrapper">
    <!-- Lado Izquierdo: Branding -->
    <div class="auth-branding">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <div style="width: 44px; height: 44px;">
                    <x-logo-svg size="44" />
                </div>
                <div>
                    <div style="font-size: 1.3rem; font-weight: 800; color: var(--tinta); line-height: 1;">
                        TÁCTIL<span style="color: var(--verde);">3D</span>
                    </div>
                    <div style="font-size: 0.68rem; font-weight: 700; color: var(--tinta-suave); letter-spacing: 0.08em; text-transform: uppercase;">
                        Sistema Braille Inclusivo
                    </div>
                </div>
            </div>

            <div class="auth-brand-badge">
                <span>🟢</span> CUENTA DE SOLICITANTE
            </div>
            <h2>Únete a la Red de Aprendizaje Inclusivo</h2>
            <p>Regístrate como docente o institución para solicitar recursos táctiles tridimensionales y material en Braille Grado 1 sin costo.</p>

            <div class="auth-feature-list">
                <div class="auth-feature-item">
                    <div class="auth-feature-icon">✨</div>
                    <div>
                        <strong>Registro Inmediato</strong><br>
                        <span style="color: var(--tinta-suave); font-size: 0.82rem;">Acceso instantáneo a todo el catálogo.</span>
                    </div>
                </div>
                <div class="auth-feature-item">
                    <div class="auth-feature-icon">📦</div>
                    <div>
                        <strong>Gestión de Pedidos</strong><br>
                        <span style="color: var(--tinta-suave); font-size: 0.82rem;">Solicita impresiones 3D personalizadas.</span>
                    </div>
                </div>
                <div class="auth-feature-item">
                    <div class="auth-feature-icon">🎓</div>
                    <div>
                        <strong>Impacto Educativo</strong><br>
                        <span style="color: var(--tinta-suave); font-size: 0.82rem;">Material adaptado para tus estudiantes.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-brand-footer">
            Inst. Técnico "Federico Álvarez Plata" Nocturno · Cochabamba
        </div>
    </div>

    <!-- Lado Derecho: Formulario de Registro -->
    <div class="auth-form-container">
        <div class="auth-header-text">
            <h1>Registro de Nuevo Solicitante</h1>
            <p>Completa tus datos para solicitar material didáctico táctil.</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nombre Completo</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control-custom"
                    placeholder="Ej. Prof. María Flores"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control-custom"
                    placeholder="docente@escuela.edu.bo"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control-custom"
                    placeholder="Mínimo 8 caracteres"
                    required
                >
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control-custom"
                    placeholder="Repite la contraseña"
                    required
                >
            </div>

            <button type="submit" class="btn-auth-submit">
                Crear mi Cuenta Gratuita →
            </button>

            <div class="auth-links">
                ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="auth-back-link">
                    ← Volver a la página principal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
