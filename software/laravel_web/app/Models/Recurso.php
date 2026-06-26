<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recurso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'recursos';
    protected $fillable = [
        'titulo',
        'descripcion',
        'gramos_pla',
        'tiempo_minutos',
        'url_imagen',
        'url_gcode',
        'estado',
        'categoria_id',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
