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
        'usuario_snapshot_bitacora',
        'accion_bitacora',
        'auditable_tipo_bitacora',
        'auditable_id_bitacora',
        'valores_anteriores_bitacora',
        'valores_nuevos_bitacora',
        'ip_bitacora',
        'user_agent_bitacora',
        'url_bitacora',
        'metodo_bitacora',
        'descripcion_bitacora',
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
        return $this->belongsTo(User::class, 'id_usuario_bitacora', 'id');
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

    public function scopeByAuditableTipo(Builder $query, ?string $tipo): Builder
    {
        return $tipo ? $query->where('auditable_tipo_bitacora', 'like', "%{$tipo}%") : $query;
    }

    public function scopeByAuditableId(Builder $query, ?int $id): Builder
    {
        return $id ? $query->where('auditable_id_bitacora', $id) : $query;
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
            $q->where('usuario_snapshot_bitacora', 'like', "%{$term}%")
              ->orWhere('accion_bitacora', 'like', "%{$term}%")
              ->orWhere('auditable_tipo_bitacora', 'like', "%{$term}%")
              ->orWhere('descripcion_bitacora', 'like', "%{$term}%");
        });
    }
}
