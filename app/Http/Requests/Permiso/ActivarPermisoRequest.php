<?php

declare(strict_types=1);

namespace App\Http\Requests\Permiso;

use Illuminate\Foundation\Http\FormRequest;

class ActivarPermisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('permisos.activar') ?? false;
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
        return [
            'id_permiso' => ['required', 'integer', 'exists:permiso,id_permiso'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_permiso.required' => 'El ID del permiso es obligatorio.',
            'id_permiso.exists' => 'El permiso a activar no existe.',
        ];
    }
}
