<?php

namespace App\Http\Requests;

use App\Services\BrailleTranslator;
use App\Support\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;

class StorePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recurso_id' => 'required|integer|exists:recursos,id',
            'institucion_id' => 'required|integer|exists:instituciones,id',
            'cantidad' => 'required|integer|min:1|max:100',
            'texto_personalizado' => 'nullable|string|min:1|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'recurso_id.required' => 'Debe seleccionar un recurso del catálogo.',
            'recurso_id.exists' => 'El recurso seleccionado no existe.',
            'institucion_id.required' => 'Debe seleccionar la institución de origen.',
            'institucion_id.exists' => 'La institución seleccionada no existe.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad mínima es 1.',
            'cantidad.max' => 'La cantidad máxima es 100.',
            'texto_personalizado.max' => 'El texto personalizado no puede superar 200 caracteres.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $textoPersonalizado = trim((string) $this->input('texto_personalizado'));
            if ($textoPersonalizado !== '') {
                $invalidos = app(BrailleTranslator::class)->validarCaracteres($textoPersonalizado);
                if ($invalidos !== []) {
                    $validator->errors()->add(
                        'texto_personalizado',
                        'El texto contiene caracteres no soportados: '.implode(', ', $invalidos).'.'
                    );
                }
            }
        });
    }

    protected function prepareForValidation(): void
    {
        // texto_personalizado NO se sanitiza con entidades HTML a propósito: el traductor
        // soporta comillas (') y ("), y el valor solo viaja dentro del archivo .gcode
        // descargado (nunca se renderiza como HTML; Storage::download lo sirve como adjunto).
        $this->merge(Sanitizer::cleanArray($this->all(), []));
    }
}
