<?php

declare(strict_types=1);

namespace App\Http\Requests\Bitacora;

use App\Models\Bitacora;
use Illuminate\Foundation\Http\FormRequest;

class ShowBitacoraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('bitacora.ver') ?? false;
    }

    protected function prepareForValidation(): void
    {
        /** @var Bitacora|null $bitacora */
        $bitacora = $this->route('bitacora');
        if ($bitacora) {
            $this->merge([
                'id_bitacora' => $bitacora->id_bitacora,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'id_bitacora' => ['required', 'integer', 'exists:bitacora,id_bitacora'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_bitacora.required' => 'El ID de la bitácora es obligatorio.',
            'id_bitacora.exists' => 'El registro de bitácora no existe.',
        ];
    }
}
