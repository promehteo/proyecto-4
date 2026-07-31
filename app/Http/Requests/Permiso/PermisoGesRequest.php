<?php

declare(strict_types=1);

namespace App\Http\Requests\Permiso;

use App\Models\Permiso;
use Illuminate\Foundation\Http\FormRequest;

class PermisoGesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return $user->hasPermissionTo('permisos.editar');
        }

        return $user->hasPermissionTo('permisos.crear');
    }

    public function rules(): array
    {
        /** @var Permiso|null $permiso */
        $permiso = $this->route('permiso');
        $permisoId = $permiso ? $permiso->id_permiso : null;

        if (!$permisoId && ($this->input('permiso_id') || $this->input('_model_id'))) {
            $permisoId = (int) ($this->input('permiso_id') ?: $this->input('_model_id'));
        }

        return [
            'nombre_permiso' => ['required', 'string', 'max:120'],
            'clave_permiso' => ['required', 'string', 'max:150', "unique:permiso,clave_permiso,{$permisoId},id_permiso", 'regex:/^[a-z0-9._-]+$/'],
            'modulo_permiso' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_permiso.required' => 'El nombre del permiso es obligatorio.',
            'nombre_permiso.max' => 'El nombre del permiso no debe exceder 120 caracteres.',
            'clave_permiso.required' => 'La clave del permiso es obligatoria.',
            'clave_permiso.unique' => 'La clave ya se encuentra registrada.',
            'clave_permiso.regex' => 'La clave solo puede contener letras minúsculas, números, puntos, guiones y guiones bajos.',
            'modulo_permiso.required' => 'El módulo del permiso es obligatorio.',
            'modulo_permiso.max' => 'El módulo no debe exceder 100 caracteres.',
        ];
    }
}
