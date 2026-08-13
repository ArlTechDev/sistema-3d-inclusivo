<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'fecha_creacion',
        'url_imagen',
        'url_gcode',
        'estado',
        'categoria_id',
    ];

    /** @return BelongsTo<Categoria, $this> */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /** @return HasMany<DetallePedido, $this> */
    public function detallesPedido(): HasMany
    {
        return $this->hasMany(DetallePedido::class);
    }
}
