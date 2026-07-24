<?php

declare(strict_types=1);

namespace App\Http\Requests\Permiso;

use App\Models\Permiso;
use Illuminate\Foundation\Http\FormRequest;

class InactivarPermisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('permisos.inactivar') ?? false;
    }

    protected function prepareForValidation(): void
    {
        /** @var Permiso|null $permiso */
        $permiso = $this->route('permiso');
        if ($permiso) {
            $this->merge([
                'id_permiso' => $permiso->id_permiso,
            ]);
        }
    }

    public function rules(): array
    {
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

    public function messages(): array
    {
        return [
            'id_permiso.required' => 'El ID del permiso es obligatorio.',
            'id_permiso.exists' => 'El permiso a inactivar no existe.',
        ];
    }
}
