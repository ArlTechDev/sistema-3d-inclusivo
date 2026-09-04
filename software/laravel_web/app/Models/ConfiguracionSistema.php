<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionSistema extends Model
{
    protected $table = 'configuracion_sistemas';

    protected $fillable = ['clave', 'valor', 'descripcion'];
}
