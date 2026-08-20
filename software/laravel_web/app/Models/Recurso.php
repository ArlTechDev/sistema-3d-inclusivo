<?php

namespace App\Models;

use App\Services\GcodeGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recurso extends Model
{
    use HasFactory, SoftDeletes;

    public const ESTADO_ACTIVO = 'Activo';

    public const ESTADO_INACTIVO = 'Inactivo';

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
        'archivo_stl',
        'archivo_glb',
        'tipo_placa',
        'placa_ancho',
        'placa_alto',
        'placa_z_altura',
        'max_caracteres',
    ];

    /**
     * Accesor para calcular dinámicamente max_caracteres si es nulo.
     */
    protected function maxCaracteres(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value !== null) {
                    return (int) $value;
                }
                if ($this->placa_ancho !== null) {
                    return (int) floor($this->placa_ancho / GcodeGenerator::AVANCE_CELDA_DEFECTO);
                }

                return null;
            }
        );
    }

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
