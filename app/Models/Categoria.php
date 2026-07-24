<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false;

    protected $fillable = [
        'id_categoria_padre_categoria',
        'nombre_categoria',
        'descripcion_categoria',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria_padre_categoria', 'id_categoria');
    }

    public function hijas(): HasMany
    {
        return $this->hasMany(Categoria::class, 'id_categoria_padre_categoria', 'id_categoria');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'id_categoria_producto', 'id_categoria');
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
            $q->where('nombre_categoria', 'like', "%{$term}%")
              ->orWhere('descripcion_categoria', 'like', "%{$term}%");
        });
    }

    /**
     * Verifica si asignar $nuevoPadreId genera una jerarquía circular.
     */
    public function generaJerarquiaCircular(?int $nuevoPadreId): bool
    {
        if (!$nuevoPadreId) {
            return false;
        }

        if ($this->id_categoria && (int) $this->id_categoria === (int) $nuevoPadreId) {
            return true;
        }

        $padreActual = Categoria::find($nuevoPadreId);
        while ($padreActual && $padreActual->id_categoria_padre_categoria) {
            if ($this->id_categoria && (int) $padreActual->id_categoria_padre_categoria === (int) $this->id_categoria) {
                return true;
            }
            $padreActual = Categoria::find($padreActual->id_categoria_padre_categoria);
        }

        return false;
    }
}
