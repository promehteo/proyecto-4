<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('usuarios.asignar_roles') ?? false;
    }

    protected function prepareForValidation(): void
    {
        /** @var User|null $usuario */
        $usuario = $this->route('usuario');
        if ($usuario) {
            $this->merge([
                'id_usuario' => $usuario->id,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'id_usuario' => [
                'required',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $targetUser = User::find($value);
                    if (!$targetUser || (int) $targetUser->status !== 1) {
                        $fail('El usuario seleccionado debe estar activo para asignarle roles.');
                        return;
                    }

                    $rolesEnviados = $this->input('roles', []);
                    $adminRol = Rol::where('slug_rol', 'admin')->first();
                    $adminRolId = $adminRol?->id_rol;

                    // Si el usuario era admin activo y se le está retirando el rol admin:
                    if ($targetUser->hasRole('admin') && (!is_array($rolesEnviados) || !in_array($adminRolId, array_map('intval', $rolesEnviados), true))) {
                        $otrosAdminsActivos = User::where('status', 1)
                            ->where('id', '!=', $targetUser->id)
                            ->whereHas('roles', function ($q) {
                                $q->where('rol.status', 1)
                                  ->where('rol_usuario.status', 1)
                                  ->where('slug_rol', 'admin');
                            })
                            ->count();

                        if ($otrosAdminsActivos === 0) {
                            $fail('No se puede quitar el rol Administrador a este usuario porque es el único administrador activo del sistema.');
                        }
                    }
                },
            ],
            'roles' => ['nullable', 'array'],
            'roles.*' => [
                'integer',
                'exists:rol,id_rol',
                function ($attribute, $value, $fail) {
                    $rol = Rol::find($value);
                    if (!$rol || (int) $rol->status !== 1) {
                        $fail("El rol con ID {$value} debe estar activo.");
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required' => 'El ID del usuario es obligatorio.',
            'id_usuario.exists' => 'El usuario seleccionado no existe.',
            'roles.array' => 'Los roles deben ser un arreglo.',
            'roles.*.exists' => 'Uno o más roles seleccionados no existen.',
        ];
    }
}
