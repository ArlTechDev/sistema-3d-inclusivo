<?php

namespace App\Http\Requests;

use App\Support\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConfiguracionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->rol === 'Administrador';
    }

    public function rules(): array
    {
        return [
            'precio_gramo_pla' => 'required|numeric|min:0.001|max:100',
            'moneda_simbolo' => 'required|string|max:10',
            'gramos_por_celda_braille' => 'required|numeric|min:0|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'precio_gramo_pla.required' => 'El precio por gramo de filamento es obligatorio.',
            'precio_gramo_pla.numeric' => 'El precio por gramo debe ser un valor numérico.',
            'precio_gramo_pla.min' => 'El precio por gramo debe ser mayor a 0.',
            'moneda_simbolo.required' => 'El símbolo de moneda es obligatorio.',
            'gramos_por_celda_braille.required' => 'Los gramos por celda Braille son obligatorios.',
            'gramos_por_celda_braille.numeric' => 'Los gramos por celda deben ser un valor numérico.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(Sanitizer::cleanArray($this->all(), []));
    }
}
