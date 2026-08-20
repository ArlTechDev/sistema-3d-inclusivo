@extends('adminlte::auth.register')

@section('adminlte_css')
    @parent
@stop

@section('content_header')
@stop

@section('content')
@stop

@section('auth_header')
    <div class="text-center mb-4">
        <h3 class="text-primary"><b>Sistema Inclusivo</b></h3>
        <p class="text-muted text-sm">Registro de Nuevo Solicitante / Docente</p>
    </div>
@stop

@section('auth_body')
    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        {{-- Nombre --}}
        <div class="input-group mb-3">
            <input
                type="text"
                id="name"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Nombre completo"
                value="{{ old('name') }}"
                required
                autofocus
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Correo Electrónico --}}
        <div class="input-group mb-3">
            <input
                type="email"
                id="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Correo electrónico"
                value="{{ old('email') }}"
                required
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div class="input-group mb-3">
            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Contraseña (mínimo 8 caracteres)"
                required
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Confirmar Contraseña --}}
        <div class="input-group mb-3">
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                placeholder="Confirmar contraseña"
                required
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus mr-1"></i> Registrarse
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <p class="my-0 text-center">
        ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="text-primary font-weight-bold">Inicia sesión</a>
    </p>
@stop
