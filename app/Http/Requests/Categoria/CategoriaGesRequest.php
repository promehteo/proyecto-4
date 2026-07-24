<?php

declare(strict_types=1);

namespace App\Http\Requests\Categoria;

use App\Models\Categoria;
use Illuminate\Foundation\Http\FormRequest;

class CategoriaGesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return $user->hasPermissionTo('categorias.editar');
        }

        return $user->hasPermissionTo('categorias.crear');
    }

    public function rules(): array
    {
        /** @var Categoria|null $categoria */
        $categoria = $this->route('categoria');
        $categoriaId = $categoria ? $categoria->id_categoria : null;

        return [
            'nombre_categoria' => ['required', 'string', 'max:120'],
            'descripcion_categoria' => ['nullable', 'string', 'max:255'],
            'id_categoria_padre_categoria' => [
                'nullable',
                'integer',
                'exists:categoria,id_categoria',
                function ($attribute, $value, $fail) use ($categoria, $categoriaId) {
                    if ($value) {
                        if ($categoriaId && (int) $value === (int) $categoriaId) {
                            $fail('Una categoría no puede ser su propio padre.');
                            return;
                        }

                        $padre = Categoria::find($value);
                        if (!$padre || (int) $padre->status !== 1) {
                            $fail('La categoría padre seleccionada debe estar activa.');
                            return;
                        }

                        if ($categoria && $categoria->generaJerarquiaCircular((int) $value)) {
                            $fail('La asignación de categoría padre genera una jerarquía circular.');
                        }
                    }
                },
            ],
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
        ];
    }
}
