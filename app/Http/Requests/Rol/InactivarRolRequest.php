<?php

declare(strict_types=1);

namespace App\Http\Requests\Rol;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class InactivarRolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('roles.inactivar') ?? false;
    }

    protected function prepareForValidation(): void
    {
        /** @var Rol|null $rol */
        $rol = $this->route('rol');
        if ($rol) {
            $this->merge([
                'id_rol' => $rol->id_rol,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'id_rol' => [
                'required',
                'integer',
                'exists:rol,id_rol',
                function ($attribute, $value, $fail) {
                    $rol = Rol::find($value);
                    if ($rol) {
                        if ($rol->usuarios()->where('users.status', 1)->where('rol_usuario.status', 1)->exists()) {
                            $fail('No se puede inactivar el rol porque tiene usuarios activos asignados.');
                            return;
                        }

                        // Verificar si es el último rol activo con permisos de administración
                        if ($rol->slug_rol === 'admin') {
                            $otrosAdminActivos = Rol::where('slug_rol', 'admin')
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

    public function messages(): array
    {
        return [
            'id_rol.required' => 'El ID del rol es obligatorio.',
            'id_rol.exists' => 'El rol a inactivar no existe.',
        ];
    }
}
