<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateConfiguracionRequest;
use App\Models\ConfiguracionSistema;

class ConfiguracionController extends Controller
{
    /**
     * Muestra la vista de configuración general y de costos del sistema.
     */
    public function index()
    {
        $precioGramo = (float) (ConfiguracionSistema::where('clave', 'precio_gramo_pla')->value('valor') ?? 0.15);
        $moneda = (string) (ConfiguracionSistema::where('clave', 'moneda_simbolo')->value('valor') ?? 'Bs');
        $gramosPorCelda = (float) (ConfiguracionSistema::where('clave', 'gramos_por_celda_braille')->value('valor') ?? 0.02);

        return view('configuracion.index', compact('precioGramo', 'moneda', 'gramosPorCelda'));
    }

    /**
     * Actualiza los parámetros de costos y filamento del sistema.
     */
    public function update(UpdateConfiguracionRequest $request)
    {
        $validated = $request->validated();

        ConfiguracionSistema::updateOrCreate(
            ['clave' => 'precio_gramo_pla'],
            [
                'valor' => (string) $validated['precio_gramo_pla'],
                'descripcion' => 'Precio del gramo de filamento PLA reciclado',
            ]
        );

        ConfiguracionSistema::updateOrCreate(
            ['clave' => 'moneda_simbolo'],
            [
                'valor' => $validated['moneda_simbolo'],
                'descripcion' => 'Símbolo de moneda para reportes y pedidos',
            ]
        );

        ConfiguracionSistema::updateOrCreate(
            ['clave' => 'gramos_por_celda_braille'],
            [
                'valor' => (string) $validated['gramos_por_celda_braille'],
                'descripcion' => 'Gramos de filamento PLA adicionales por cada celda Braille en relieve',
            ]
        );

        return redirect()->route('configuracion.index')
            ->with('success', 'Parámetros de costos y filamento actualizados exitosamente.');
    }
}
