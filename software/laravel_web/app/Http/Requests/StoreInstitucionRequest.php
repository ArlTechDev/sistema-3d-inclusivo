<?php

namespace App\Http\Requests;

use App\Support\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;

class StoreInstitucionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|min:3|max:100',
            'direccion' => 'required|string|min:5|max:200',
            'telefono' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
            'director' => 'nullable|string|max:100',
            'logo' => 'nullable|image|max:2048',
            'documento_pdf' => 'nullable|file|mimes:pdf|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la institución es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.min' => 'La dirección debe tener al menos 5 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios, +, guiones o paréntesis.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.max' => 'El logo no puede superar 2 MB.',
            'documento_pdf.mimes' => 'El documento debe ser un archivo PDF.',
            'documento_pdf.max' => 'El documento PDF no puede superar 4 MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(Sanitizer::cleanArray($this->all(), ['nombre', 'direccion', 'director']));
    }
}
