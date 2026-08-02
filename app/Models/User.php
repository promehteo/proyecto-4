<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'integer',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'detalle_rol',
            'id_usuario',
            'id_rol'
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

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('nombre', 'like', "%{$term}%")
              ->orWhere('apellido', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    /**
     * Revisa si el usuario tiene un rol activo por clave.
     */
    public function hasRole(string $clave): bool
    {
        if ($this->status !== 1) {
            return false;
        }

        return $this->roles()
            ->where('rol.status', 1)
            ->where('detalle_rol.status', 1)
            ->where('clave_rol', $clave)
            ->exists();
    }

    protected ?array $permissionsCache = null;

    /**
     * Revisa si el usuario tiene un permiso activo usando type-safety y caché local.
     */
    public function hasPermission(\App\Enums\Permission $permission): bool
    {
        if ($this->status !== 1) {
            return false;
        }

        if ($this->hasRole('admin')) {
            return true;
        }

        if ($this->permissionsCache === null) {
            $this->permissionsCache = $this->roles()
                ->where('rol.status', 1)
                ->where('detalle_rol.status', 1)
                ->with(['permisos' => function ($query) {
                    $query->where('permiso.status', 1)
                          ->where('detalle_permiso.status', 1);
                }])
                ->get()
                ->pluck('permisos')
                ->collapse()
                ->pluck('clave_permiso')
                ->toArray();
        }

        return in_array($permission->value, $this->permissionsCache, true);
    }

    /**
     * Revisa si el usuario tiene un permiso activo a través de sus roles activos.
     */
    public function hasPermissionTo(string $permissionClave): bool
    {
        if ($this->status !== 1) {
            return false;
        }

        // Si tiene rol 'admin' activo, posee acceso a todos los permisos
        if ($this->hasRole('admin')) {
            return true;
        }

        return $this->roles()
            ->where('rol.status', 1)
            ->where('detalle_rol.status', 1)
            ->whereHas('permisos', function (Builder $q) use ($permissionClave) {
                $q->where('permiso.status', 1)
                  ->where('detalle_permiso.status', 1)
                  ->where('clave_permiso', $permissionClave);
            })->exists();
    }
}
