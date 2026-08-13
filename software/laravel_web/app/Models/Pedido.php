<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'institucion_id',
        'estado',
        'fecha_solicitud',
        'total_gramos_pla',
        'costo_total',
        'gcode_path',
        'motivo_rechazo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function institucion()
    {
        return $this->belongsTo(Institucion::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    protected function casts(): array
    {
        return [
            'fecha_solicitud' => 'datetime',
        ];
    }
}
