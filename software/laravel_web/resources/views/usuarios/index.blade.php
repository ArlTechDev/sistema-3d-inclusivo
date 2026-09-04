@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-users text-primary mr-2"></i>Gestión de Usuarios</h1>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-user-plus mr-1"></i> Nuevo Usuario
    </a>
</div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Usuarios</h3>

        @if(auth()->user()->rol === 'Administrador')
            <div class="card-tools">
                <a href="{{ route('usuarios.papelera') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-trash"></i> Papelera
                </a>
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Usuario
                </a>
            </div>
        @endif
    </div>

    <div class="card-body">
        <table aria-label="Lista de usuarios del sistema" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Imagen</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <span class="badge bg-{{ $usuario->rol === 'Administrador' ? 'danger' : 'info' }}">
                                {{ $usuario->rol }}
                            </span>
                        </td>
                        <td>
                            @if($usuario->foto_perfil)
                                <img src="{{ asset('storage/' . $usuario->foto_perfil) }}" alt="Foto de {{ $usuario->name }}" width="50px" height="50px" class="img-thumbnail" style="object-fit: cover;">
                            @else
                                <span class="text-muted">Sin foto</span>
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->rol === 'Administrador')
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Enviar usuario a papelera?');">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            <a href="{{ route('usuarios.pdf') }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('usuarios.excel') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center">
    {{ $usuarios->links() }}
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('.alert-success').delay(3000).fadeOut('slow');
    });
</script>
@stop
