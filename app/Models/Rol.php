<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre_rol',
        'clave_rol',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }

    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(
            Permiso::class,
            'detalle_permiso',
            'id_rol',
            'id_permiso'
        )->withPivot('status', 'id_detalle_permiso');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'detalle_rol',
            'id_rol',
            'id_usuario'
        )->withPivot('status', 'id_detalle_rol');
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

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('nombre_rol', 'like', "%{$term}%")
              ->orWhere('clave_rol', 'like', "%{$term}%");
        });
    }
}
