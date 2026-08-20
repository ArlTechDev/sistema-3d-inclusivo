<?php

namespace App\Http\Requests;

use App\Support\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;

class StoreRecursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|min:5|max:150',
            'descripcion' => 'required|string|min:10',
            'gramos_pla' => 'required|numeric|min:0.1',
            'tiempo_minutos' => 'required|integer|min:1',
            'fecha_creacion' => 'nullable|date',
            'estado' => 'required|in:Activo,Inactivo',
            'categoria_id' => 'nullable|integer|exists:categorias,id',
            'url_imagen' => 'nullable|image|max:2048',
            'url_gcode' => 'nullable|file|mimes:gcode,txt',
            'archivo_stl' => 'nullable|file|max:20480',
            'archivo_glb' => 'nullable|file|max:20480',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.min' => 'El título debe tener al menos 5 caracteres.',
            'titulo.max' => 'El título no puede superar 150 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            'gramos_pla.required' => 'Los gramos de PLA son obligatorios.',
            'gramos_pla.numeric' => 'Los gramos de PLA deben ser un valor numérico.',
            'gramos_pla.min' => 'Los gramos de PLA deben ser mayor a 0.',
            'tiempo_minutos.required' => 'El tiempo en minutos es obligatorio.',
            'tiempo_minutos.integer' => 'El tiempo debe ser un número entero.',
            'tiempo_minutos.min' => 'El tiempo debe ser mayor a 0.',
            'fecha_creacion.date' => 'Debe ingresar una fecha válida.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = Sanitizer::cleanArray($this->all(), ['titulo', 'descripcion']);

        if (empty($data['fecha_creacion'])) {
            $data['fecha_creacion'] = now()->toDateString();
        }

        $this->merge($data);
    }
}
