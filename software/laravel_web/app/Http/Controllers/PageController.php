<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    /**
     * Muestra la página Acerca del Proyecto (Impacto, Equipo, etc.)
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Muestra la Landing Page principal del proyecto
     */
    public function welcome()
    {
        return view('welcome');
    }

    /**
     * Muestra la página de Ayuda y Contacto (FAQ, Flujo)
     */
    public function help()
    {
        return view('pages.help');
    }
}
