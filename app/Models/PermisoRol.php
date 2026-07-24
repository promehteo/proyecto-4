<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermisoRol extends Model
{
    protected $table = 'permiso_rol';
    protected $primaryKey = 'id_permiso_rol';
    public $timestamps = false;

    protected $fillable = [
        'id_permiso_permiso_rol',
        'id_rol_permiso_rol',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }
}
