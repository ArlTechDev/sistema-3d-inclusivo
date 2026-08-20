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

    public const ESTADO_PENDIENTE = 'Pendiente';

    public const ESTADO_APROBADO = 'Aprobado';

    public const ESTADO_EN_IMPRESION = 'En impresión';

    public const ESTADO_COMPLETADO = 'Completado';

    public const ESTADO_RECHAZADO = 'Rechazado';

    /**
     * Transiciones de estado permitidas (UC-08).
     *
     * @var array<string, array<int, string>>
     */
    public const TRANSICIONES = [
        self::ESTADO_PENDIENTE => [self::ESTADO_APROBADO, self::ESTADO_RECHAZADO],
        self::ESTADO_APROBADO => [self::ESTADO_EN_IMPRESION, self::ESTADO_RECHAZADO],
        self::ESTADO_EN_IMPRESION => [self::ESTADO_COMPLETADO, self::ESTADO_RECHAZADO],
    ];

    public function puedeTransicionarA(string $nuevoEstado): bool
    {
        return in_array($nuevoEstado, self::TRANSICIONES[$this->estado] ?? [], true);
    }

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
