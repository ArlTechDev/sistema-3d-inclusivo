<?php

namespace App\Http\Requests;

use App\Support\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;

class RechazarPedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motivo_rechazo' => 'required|string|min:5|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'motivo_rechazo.required' => 'El motivo del rechazo es obligatorio.',
            'motivo_rechazo.min' => 'El motivo debe tener al menos 5 caracteres.',
            'motivo_rechazo.max' => 'El motivo no puede superar 255 caracteres.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(Sanitizer::cleanArray($this->all(), ['motivo_rechazo']));
    }
}
