<?php

namespace App\Http\Requests;

use App\Models\Pedido;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estado' => 'required|in:'.implode(',', [
                Pedido::ESTADO_PENDIENTE,
                Pedido::ESTADO_APROBADO,
                Pedido::ESTADO_EN_IMPRESION,
                Pedido::ESTADO_COMPLETADO,
            ]),
        ];
    }

    public function messages(): array
    {
        return [
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}
