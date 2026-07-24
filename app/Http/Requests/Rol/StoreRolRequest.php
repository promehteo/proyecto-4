<?php

declare(strict_types=1);

namespace App\Http\Requests\Rol;

use Illuminate\Foundation\Http\FormRequest;

class StoreRolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('roles.crear') ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre_rol' => ['required', 'string', 'max:100'],
            'slug_rol' => ['required', 'string', 'max:120', 'unique:rol,slug_rol', 'regex:/^[a-z0-9._-]+$/'],
            'descripcion_rol' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_rol.required' => 'El nombre del rol es obligatorio.',
            'nombre_rol.max' => 'El nombre del rol no debe exceder 100 caracteres.',
            'slug_rol.required' => 'El slug del rol es obligatorio.',
            'slug_rol.unique' => 'El slug ya se encuentra registrado.',
            'slug_rol.regex' => 'El slug solo puede contener letras minúsculas, números, puntos, guiones y guiones bajos.',
            'descripcion_rol.max' => 'La descripción no debe exceder 255 caracteres.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser activo (1) o inactivo (2).',
        ];
    }
}
