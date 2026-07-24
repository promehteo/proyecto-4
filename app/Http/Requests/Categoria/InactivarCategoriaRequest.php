<?php

declare(strict_types=1);

namespace App\Http\Requests\Categoria;

use App\Models\Categoria;
use Illuminate\Foundation\Http\FormRequest;

class InactivarCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('categorias.inactivar') ?? false;
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
                    if ($categoria) {
                        if ($categoria->productos()->where('status', 1)->exists()) {
                            $fail('No se puede inactivar la categoría porque tiene productos activos asociados.');
                        }

                        if ($categoria->hijas()->where('status', 1)->exists()) {
                            $fail('No se puede inactivar la categoría porque tiene categorías hijas activas.');
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
            'id_categoria.exists' => 'La categoría a inactivar no existe.',
        ];
    }
}
