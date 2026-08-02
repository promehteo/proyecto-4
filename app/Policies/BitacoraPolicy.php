<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Bitacora;
use App\Models\User;

class BitacoraPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(Permission::VER_BITACORA);
    }

    public function view(User $user, Bitacora $bitacora): bool
    {
        return $user->hasPermission(Permission::VER_BITACORA);
    }
}
