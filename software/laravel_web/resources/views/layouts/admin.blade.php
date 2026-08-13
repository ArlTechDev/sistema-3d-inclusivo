{{-- Layout de administración: extiende AdminLTE (densidad de datos y sidebar para el rol Administrador).
     Las vistas hijas definen las secciones title / content_header / content y solo cambian el @extends. --}}
@extends('adminlte::page')

@section('title')
    @yield('title')
@stop

@section('content_header')
    @yield('content_header')
@stop

@section('content')
    @yield('content')
@stop
