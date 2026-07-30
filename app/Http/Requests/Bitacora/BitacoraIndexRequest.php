<?php

declare(strict_types=1);

namespace App\Http\Requests\Bitacora;

use Illuminate\Foundation\Http\FormRequest;

class BitacoraIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('bitacora.ver') ?? false;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:190'],
            'usuario_id' => ['nullable', 'integer', 'exists:user,id_user'],
            'accion' => ['nullable', 'string', 'max:80'],
            'auditable_tipo' => ['nullable', 'string', 'max:190'],
            'auditable_id' => ['nullable', 'integer'],
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date', 'after_or_equal:fecha_desde'],
            'ip' => ['nullable', 'string', 'max:45'],
        ];
    }

    public function messages(): array
    {
        return [
            'search.max' => 'El término de búsqueda no debe exceder 190 caracteres.',
            'usuario_id.exists' => 'El usuario seleccionado no existe.',
            'accion.max' => 'La acción no debe exceder 80 caracteres.',
            'fecha_hasta.after_or_equal' => 'La fecha hasta debe ser igual o posterior a la fecha desde.',
            'ip.max' => 'La IP no debe exceder 45 caracteres.',
        ];
    }
}
