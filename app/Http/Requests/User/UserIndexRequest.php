<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($this->isMethod('PATCH') && $this->routeIs('usuarios.inactivar')) {
            return $user->hasPermissionTo('usuarios.inactivar');
        }

        if ($this->isMethod('PATCH') && $this->routeIs('usuarios.activar')) {
            return $user->hasPermissionTo('usuarios.activar');
        }

        return $user->hasPermissionTo('usuarios.ver');
    }

    protected function prepareForValidation(): void
    {
        /** @var mixed $usuario */
        $usuario = $this->route('usuario');
        $idUsuario = is_object($usuario) ? $usuario->id_user : $usuario;
        if ($idUsuario) {
            $this->merge([
                'id_usuario' => (int) $idUsuario,
            ]);
        }
    }

    public function rules(): array
    {
        if ($this->isMethod('PATCH') && $this->routeIs('usuarios.inactivar')) {
            return [
                'id_usuario' => [
                    'required',
                    'integer',
                    'exists:user,id_user',
                    function ($attribute, $value, $fail) {
                        if ((int) $this->user()->id_user === (int) $value) {
                            $fail('No puede inactivar su propio usuario.');
                            return;
                        }

                        $targetUser = User::find($value);
                        if ($targetUser && $targetUser->hasRole('admin')) {
                            $adminsActivosCount = User::where('status', 1)
                                ->whereHas('roles', function ($q) {
                                    $q->where('rol.status', 1)
                                      ->where('detalle_rol.status', 1)
                                      ->where('clave_rol', 'admin');
                                })
                                ->where('id_user', '!=', $value)
                                ->count();

                            if ($adminsActivosCount === 0) {
                                  $fail('No se puede inactivar al usuario porque es el único administrador activo del sistema.');
                            }
                        }
                    },
                ],
            ];
        }

        if ($this->isMethod('PATCH') && $this->routeIs('usuarios.activar')) {
            return [
                'id_usuario' => ['required', 'integer', 'exists:user,id_user'],
            ];
        }

        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required' => 'El ID del usuario es obligatorio.',
            'id_usuario.exists' => 'El usuario seleccionado no existe.',
        ];
    }
}
