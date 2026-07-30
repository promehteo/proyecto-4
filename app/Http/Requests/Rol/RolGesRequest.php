<?php

declare(strict_types=1);

namespace App\Http\Requests\Rol;

use App\Models\Rol;
use Illuminate\Foundation\Http\FormRequest;

class RolGesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return $user->hasPermissionTo('roles.editar');
        }

        return $user->hasPermissionTo('roles.crear');
    }

    public function rules(): array
    {
        /** @var Rol|null $rol */
        $rol = $this->route('rol');
        $rolId = $rol ? $rol->id_rol : null;

        if (!$rolId && ($this->input('rol_id') || $this->input('_model_id'))) {
            $rolId = (int) ($this->input('rol_id') ?: $this->input('_model_id'));
        }

        return [
            'nombre_rol' => ['required', 'string', 'max:100'],
            'clave_rol' => ['required', 'string', 'max:120', "unique:rol,clave_rol,{$rolId},id_rol", 'regex:/^[a-z0-9._-]+$/'],
            'status' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_rol.required' => 'El nombre del rol es obligatorio.',
            'nombre_rol.max' => 'El nombre del rol no debe exceder 100 caracteres.',
            'clave_rol.required' => 'La clave del rol es obligatoria.',
            'clave_rol.unique' => 'La clave ya se encuentra registrada.',
            'clave_rol.regex' => 'La clave solo puede contener letras minúsculas, números, puntos, guiones y guiones bajos.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser activo (1) o inactivo (2).',
        ];
    }
}
