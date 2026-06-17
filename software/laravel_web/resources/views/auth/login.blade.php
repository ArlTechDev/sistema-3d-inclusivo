@extends('adminlte::auth.login')

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
    </div>
@stop

@section('auth_body')
    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="input-group mb-3">
            <input
                type="email"
                id="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Correo electrónico"
                value="{{ old('email') }}"
                required
                autofocus
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

        <div class="input-group mb-3">
            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Contraseña"
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

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
            </div>
        </div>
    </form>
@stop
