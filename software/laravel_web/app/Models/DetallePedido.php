<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePedido extends Model
{
    protected $fillable = [
        'pedido_id',
        'recurso_id',
        'cantidad',
        'gramos_pla',
        'costo_unitario',
    ];

    /** @return BelongsTo<Pedido, $this> */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    /** @return BelongsTo<Recurso, $this> */
    public function recurso(): BelongsTo
    {
        return $this->belongsTo(Recurso::class);
    }
}
