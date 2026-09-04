{{-- Layout de administración: extiende AdminLTE (densidad de datos y sidebar para el rol Administrador).
     Las vistas hijas definen las secciones title / content_header / content y solo cambian el @extends. --}}
@extends('adminlte::page')

@section('title')
    @yield('title')
@stop

@section('content_header')
    <div role="region" aria-label="Encabezado de página de administración">
        @yield('content_header')
    </div>
@stop

@section('content')
    <main id="main-content" role="main" aria-label="Contenido principal del panel">
        @yield('content')
    </main>
@stop
