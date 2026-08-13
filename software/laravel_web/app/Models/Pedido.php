<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Institucion, $this> */
    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    /** @return HasMany<DetallePedido, $this> */
    public function detalles(): HasMany
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
