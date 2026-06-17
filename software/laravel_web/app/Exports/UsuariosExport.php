<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsuariosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::select(
            'id',
            'name',
            'email',
            'rol',
            'foto_perfil',
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
            'Email',
            'Rol',
            'Foto Perfil',
            'Created At',
            'Updated At',
            'Deleted At',
        ];
    }
}
