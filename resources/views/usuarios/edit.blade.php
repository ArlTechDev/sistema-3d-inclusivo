@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar Usuario</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $usuario->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $usuario->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    <small class="text-muted">Dejar en blanco para conservar la contraseña actual.</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select id="rol" name="rol" class="form-select @error('rol') is-invalid @enderror">
                        <option value="Administrador" {{ old('rol', $usuario->rol) === 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Docente" {{ old('rol', $usuario->rol) === 'Docente' ? 'selected' : '' }}>Docente</option>
                    </select>
                    @error('rol')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Actualizar usuario</button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection
