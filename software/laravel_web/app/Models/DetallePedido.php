<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $fillable = [
        'pedido_id',
        'recurso_id',
        'cantidad',
        'gramos_pla',
        'costo_unitario',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function recurso()
    {
        return $this->belongsTo(Recurso::class);
    }
}
