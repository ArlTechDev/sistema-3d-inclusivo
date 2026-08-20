<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionSistema;
use Illuminate\Http\Request;

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
    public function update(Request $request)
    {
        $validated = $request->validate([
            'precio_gramo_pla' => 'required|numeric|min:0.001|max:100',
            'moneda_simbolo' => 'required|string|max:10',
            'gramos_por_celda_braille' => 'required|numeric|min:0|max:10',
        ], [
            'precio_gramo_pla.required' => 'El precio por gramo de filamento es obligatorio.',
            'precio_gramo_pla.numeric' => 'El precio por gramo debe ser un valor numérico.',
            'precio_gramo_pla.min' => 'El precio por gramo debe ser mayor a 0.',
            'moneda_simbolo.required' => 'El símbolo de moneda es obligatorio.',
            'gramos_por_celda_braille.required' => 'Los gramos por celda Braille son obligatorios.',
            'gramos_por_celda_braille.numeric' => 'Los gramos por celda deben ser un valor numérico.',
        ]);

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
