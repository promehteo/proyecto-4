<?php

declare(strict_types=1);

namespace App\Http\Requests\Rol;

use Illuminate\Foundation\Http\FormRequest;

class ActivarRolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('roles.activar') ?? false;
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
        return [
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_rol.required' => 'El ID del rol es obligatorio.',
            'id_rol.exists' => 'El rol a activar no existe.',
        ];
    }
}
