<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Model
{
    protected $table = 'bitacora';
    protected $primaryKey = 'id_bitacora';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario_bitacora',
        'accion_bitacora',
        'modulo_bitacora',
        'registro_id_bitacora',
        'valores_anteriores_bitacora',
        'valores_nuevos_bitacora',
        'ip_bitacora',
        'navegador_bitacora',
        'url_bitacora',
        'metodo_bitacora',
        'fecha_bitacora',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'valores_anteriores_bitacora' => 'array',
            'valores_nuevos_bitacora' => 'array',
            'fecha_bitacora' => 'datetime',
            'status' => 'integer',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_bitacora', 'id_user');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    public function scopeByUsuario(Builder $query, ?int $usuarioId): Builder
    {
        return $usuarioId ? $query->where('id_usuario_bitacora', $usuarioId) : $query;
    }

    public function scopeByAccion(Builder $query, ?string $accion): Builder
    {
        return $accion ? $query->where('accion_bitacora', $accion) : $query;
    }

    public function scopeByModulo(Builder $query, ?string $modulo): Builder
    {
        return $modulo ? $query->where('modulo_bitacora', 'like', "%{$modulo}%") : $query;
    }

    public function scopeByRegistroId(Builder $query, ?int $id): Builder
    {
        return $id ? $query->where('registro_id_bitacora', $id) : $query;
    }

    public function scopeByFechaDesde(Builder $query, ?string $fecha): Builder
    {
        return $fecha ? $query->whereDate('fecha_bitacora', '>=', $fecha) : $query;
    }

    public function scopeByFechaHasta(Builder $query, ?string $fecha): Builder
    {
        return $fecha ? $query->whereDate('fecha_bitacora', '<=', $fecha) : $query;
    }

    public function scopeByIp(Builder $query, ?string $ip): Builder
    {
        return $ip ? $query->where('ip_bitacora', $ip) : $query;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('accion_bitacora', 'like', "%{$term}%")
              ->orWhere('modulo_bitacora', 'like', "%{$term}%");
        });
    }
}
