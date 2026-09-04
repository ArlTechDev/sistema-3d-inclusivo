<?php

namespace App\Exports;

use App\Models\Recurso;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RecursosExport implements FromCollection, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection()
    {
        return Recurso::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Titulo',
            'Descripcion',
            'Gramos PLA',
            'Tiempo Minutos',
            'Url Imagen',
            'Url Gcode',
            'Estado',
            'Created At',
            'Updated At',
            'Deleted At',
        ];
    }
}
