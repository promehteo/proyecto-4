<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permiso extends Model
{
    protected $table = 'permiso';
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;

    protected $fillable = [
        'nombre_permiso',
        'slug_permiso',
        'modulo_permiso',
        'descripcion_permiso',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'permiso_rol',
            'id_permiso_permiso_rol',
            'id_rol_permiso_rol'
        )->withPivot('status', 'id_permiso_rol');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 2);
    }

    public function scopeByStatus(Builder $query, ?int $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeByModulo(Builder $query, ?string $modulo): Builder
    {
        return $modulo ? $query->where('modulo_permiso', $modulo) : $query;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('nombre_permiso', 'like', "%{$term}%")
              ->orWhere('slug_permiso', 'like', "%{$term}%")
              ->orWhere('modulo_permiso', 'like', "%{$term}%")
              ->orWhere('descripcion_permiso', 'like', "%{$term}%");
        });
    }
}
