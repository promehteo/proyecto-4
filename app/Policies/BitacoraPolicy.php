<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Bitacora;
use App\Models\User;

class BitacoraPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('bitacora.ver');
    }

    public function view(User $user, Bitacora $bitacora): bool
    {
        return $user->hasPermissionTo('bitacora.ver');
    }
}
