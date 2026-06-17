<?php

namespace App\Exports;

use App\Models\Institucion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InstitucionesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Institucion::select(
            'id',
            'nombre',
            'direccion',
            'telefono',
            'director',
            'logo',
            'documento_pdf',
            'created_at',
            'updated_at',
            'deleted_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Direccion',
            'Telefono',
            'Director',
            'Logo',
            'Documento PDF',
            'Created At',
            'Updated At',
            'Deleted At',
        ];
    }
}
