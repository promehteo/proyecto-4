<?php

declare(strict_types=1);

namespace App\Http\Requests\Rol;

use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRolPermisosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('roles.asignar_permisos') ?? false;
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
                    if (!$rol || (int) $rol->status !== 1) {
                        $fail('El rol seleccionado debe estar activo para asignarle permisos.');
                    }
                },
            ],
            'permisos' => ['nullable', 'array'],
            'permisos.*' => [
                'integer',
                'exists:permiso,id_permiso',
                function ($attribute, $value, $fail) {
                    $permiso = Permiso::find($value);
                    if (!$permiso || (int) $permiso->status !== 1) {
                        $fail("El permiso con ID {$value} debe estar activo.");
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_rol.required' => 'El ID del rol es obligatorio.',
            'id_rol.exists' => 'El rol seleccionado no existe.',
            'permisos.array' => 'Los permisos deben ser un arreglo.',
            'permisos.*.exists' => 'Uno o más permisos seleccionados no existen.',
        ];
    }
}
