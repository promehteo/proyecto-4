<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolUsuario extends Model
{
    protected $table = 'rol_usuario';
    protected $primaryKey = 'id_rol_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_rol_rol_usuario',
        'id_usuario_rol_usuario',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }
}
