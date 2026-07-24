<?php

declare(strict_types=1);

namespace App\Http\Requests\Permiso;

use Illuminate\Foundation\Http\FormRequest;

class StorePermisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('permisos.crear') ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre_permiso' => ['required', 'string', 'max:120'],
            'slug_permiso' => ['required', 'string', 'max:150', 'unique:permiso,slug_permiso', 'regex:/^[a-z0-9._-]+$/'],
            'modulo_permiso' => ['required', 'string', 'max:100'],
            'descripcion_permiso' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_permiso.required' => 'El nombre del permiso es obligatorio.',
            'nombre_permiso.max' => 'El nombre del permiso no debe exceder 120 caracteres.',
            'slug_permiso.required' => 'El slug del permiso es obligatorio.',
            'slug_permiso.unique' => 'El slug ya se encuentra registrado.',
            'slug_permiso.regex' => 'El slug solo puede contener letras minúsculas, números, puntos, guiones y guiones bajos.',
            'modulo_permiso.required' => 'El módulo del permiso es obligatorio.',
            'modulo_permiso.max' => 'El módulo no debe exceder 100 caracteres.',
            'descripcion_permiso.max' => 'La descripción no debe exceder 255 caracteres.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser activo (1) o inactivo (2).',
        ];
    }
}
