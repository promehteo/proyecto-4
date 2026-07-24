<?php

declare(strict_types=1);

namespace App\Http\Requests\Categoria;

use App\Models\Categoria;
use Illuminate\Foundation\Http\FormRequest;

class ActivarCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('categorias.activar') ?? false;
    }

    protected function prepareForValidation(): void
    {
        /** @var Categoria|null $categoria */
        $categoria = $this->route('categoria');
        if ($categoria) {
            $this->merge([
                'id_categoria' => $categoria->id_categoria,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'id_categoria' => [
                'required',
                'integer',
                'exists:categoria,id_categoria',
                function ($attribute, $value, $fail) {
                    $categoria = Categoria::find($value);
                    if ($categoria && $categoria->id_categoria_padre_categoria) {
                        $padre = Categoria::find($categoria->id_categoria_padre_categoria);
                        if (!$padre || (int) $padre->status !== 1) {
                            $fail('No se puede activar la categoría porque su categoría padre está inactiva.');
                        }
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_categoria.required' => 'El ID de la categoría es obligatorio.',
            'id_categoria.exists' => 'La categoría a activar no existe.',
        ];
    }
}
