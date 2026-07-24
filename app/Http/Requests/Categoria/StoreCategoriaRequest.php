<?php

declare(strict_types=1);

namespace App\Http\Requests\Categoria;

use App\Models\Categoria;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('categorias.crear') ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre_categoria' => ['required', 'string', 'max:120'],
            'descripcion_categoria' => ['nullable', 'string', 'max:255'],
            'id_categoria_padre_categoria' => [
                'nullable',
                'integer',
                'exists:categoria,id_categoria',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $padre = Categoria::find($value);
                        if (!$padre || (int) $padre->status !== 1) {
                            $fail('La categoría padre seleccionada debe estar activa.');
                        }
                    }
                },
            ],
            'status' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_categoria.required' => 'El nombre de la categoría es obligatorio.',
            'nombre_categoria.string' => 'El nombre de la categoría debe ser una cadena de texto.',
            'nombre_categoria.max' => 'El nombre de la categoría no debe exceder 120 caracteres.',
            'descripcion_categoria.max' => 'La descripción no debe exceder 255 caracteres.',
            'id_categoria_padre_categoria.exists' => 'La categoría padre seleccionada no existe.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser activo (1) o inactivo (2).',
        ];
    }
}
