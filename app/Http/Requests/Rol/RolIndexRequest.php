<?php

declare(strict_types=1);

namespace App\Http\Requests\Rol;

use App\Models\Rol;
use Illuminate\Foundation\Http\FormRequest;

class RolIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($this->isMethod('PATCH') && $this->routeIs('roles.inactivar')) {
            return $user->hasPermissionTo('roles.inactivar');
        }

        if ($this->isMethod('PATCH') && $this->routeIs('roles.activar')) {
            return $user->hasPermissionTo('roles.activar');
        }

        return $user->hasPermissionTo('roles.ver');
    }

    protected function prepareForValidation(): void
    {
        /** @var mixed $rol */
        $rol = $this->route('rol');
        $idRol = is_object($rol) ? $rol->id_rol : $rol;
        if ($idRol) {
            $this->merge([
                'id_rol' => (int) $idRol,
            ]);
        }
    }

    public function rules(): array
    {
        if ($this->isMethod('PATCH') && $this->routeIs('roles.inactivar')) {
            return [
                'id_rol' => [
                    'required',
                    'integer',
                    'exists:rol,id_rol',
                    function ($attribute, $value, $fail) {
                        $rol = Rol::find($value);
                        if ($rol) {
                            if ($rol->usuarios()->where('user.status', 1)->where('detalle_rol.status', 1)->exists()) {
                                $fail('No se puede inactivar el rol porque tiene usuarios activos asignados.');
                                return;
                            }

                            if ($rol->clave_rol === 'admin') {
                                $otrosAdminActivos = Rol::where('clave_rol', 'admin')
                                    ->where('status', 1)
                                    ->where('id_rol', '!=', $rol->id_rol)
                                    ->exists();

                                if (!$otrosAdminActivos) {
                                    $fail('No se puede inactivar el rol porque es el último rol activo con permisos de administración.');
                                }
                            }
                        }
                    },
                ],
            ];
        }

        if ($this->isMethod('PATCH') && $this->routeIs('roles.activar')) {
            return [
                'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
            ];
        }

        return [
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_rol.required' => 'El ID del rol es obligatorio.',
            'id_rol.exists' => 'El rol seleccionado no existe.',
        ];
    }
}
