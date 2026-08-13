<?php

namespace App\Exports;

use App\Models\Pedido;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PedidosExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Pedido::with(['user', 'institucion'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Solicitante',
            'Institución',
            'Fecha solicitud',
            'Estado',
            'Gramos PLA totales',
            'Costo total (Bs)',
            'Motivo rechazo',
            'Archivo G-Code',
        ];
    }

    public function map($pedido): array
    {
        return [
            $pedido->id,
            $pedido->user->name ?? '-',
            $pedido->institucion->nombre ?? '-',
            $pedido->fecha_solicitud?->format('d/m/Y') ?? '-',
            $pedido->estado,
            $pedido->total_gramos_pla,
            number_format($pedido->costo_total, 2),
            $pedido->motivo_rechazo ?? '-',
            $pedido->gcode_path ? basename($pedido->gcode_path) : '-',
        ];
    }
}
