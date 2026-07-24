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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
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
            'rol_usuario',
            'id_usuario_rol_usuario',
            'id_rol_rol_usuario'
        )->withPivot('status', 'id_rol_usuario');
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
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    /**
     * Revisa si el usuario tiene un rol activo por slug.
     */
    public function hasRole(string $slug): bool
    {
        if ($this->status !== 1) {
            return false;
        }

        return $this->roles()
            ->where('rol.status', 1)
            ->where('rol_usuario.status', 1)
            ->where('slug_rol', $slug)
            ->exists();
    }

    /**
     * Revisa si el usuario tiene un permiso activo a través de sus roles activos.
     */
    public function hasPermissionTo(string $permissionSlug): bool
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
            ->where('rol_usuario.status', 1)
            ->whereHas('permisos', function (Builder $q) use ($permissionSlug) {
                $q->where('permiso.status', 1)
                  ->where('permiso_rol.status', 1)
                  ->where('slug_permiso', $permissionSlug);
            })->exists();
    }
}
