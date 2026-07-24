<?php

declare(strict_types=1);

namespace App\Http\Requests\Permiso;

use App\Models\Permiso;
use Illuminate\Foundation\Http\FormRequest;

class PermisoIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($this->isMethod('PATCH') && $this->routeIs('permisos.inactivar')) {
            return $user->hasPermissionTo('permisos.inactivar');
        }

        if ($this->isMethod('PATCH') && $this->routeIs('permisos.activar')) {
            return $user->hasPermissionTo('permisos.activar');
        }

        return $user->hasPermissionTo('permisos.ver');
    }

    protected function prepareForValidation(): void
    {
        /** @var mixed $permiso */
        $permiso = $this->route('permiso');
        $idPermiso = is_object($permiso) ? $permiso->id_permiso : $permiso;
        if ($idPermiso) {
            $this->merge([
                'id_permiso' => (int) $idPermiso,
            ]);
        }
    }

    public function rules(): array
    {
        if ($this->isMethod('PATCH') && $this->routeIs('permisos.inactivar')) {
            return [
                'id_permiso' => [
                    'required',
                    'integer',
                    'exists:permiso,id_permiso',
                    function ($attribute, $value, $fail) {
                        $permiso = Permiso::find($value);
                        if ($permiso) {
                            if ($permiso->roles()->where('rol.status', 1)->where('permiso_rol.status', 1)->exists()) {
                                $fail('No se puede inactivar el permiso porque tiene asignaciones activas a roles.');
                            }
                        }
                    },
                ],
            ];
        }

        if ($this->isMethod('PATCH') && $this->routeIs('permisos.activar')) {
            return [
                'id_permiso' => ['required', 'integer', 'exists:permiso,id_permiso'],
            ];
        }

        return [
            'search' => ['nullable', 'string', 'max:120'],
            'modulo' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_permiso.required' => 'El ID del permiso es obligatorio.',
            'id_permiso.exists' => 'El permiso seleccionado no existe.',
        ];
    }
}
