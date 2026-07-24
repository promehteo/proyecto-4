<?php

declare(strict_types=1);

namespace App\Http\Requests\Categoria;

use App\Models\Categoria;
use Illuminate\Foundation\Http\FormRequest;

class CategoriaIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($this->isMethod('PATCH') && $this->routeIs('categorias.inactivar')) {
            return $user->hasPermissionTo('categorias.inactivar');
        }

        if ($this->isMethod('PATCH') && $this->routeIs('categorias.activar')) {
            return $user->hasPermissionTo('categorias.activar');
        }

        return $user->hasPermissionTo('categorias.ver');
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
        if ($this->isMethod('PATCH') && $this->routeIs('categorias.inactivar')) {
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

        if ($this->isMethod('PATCH') && $this->routeIs('categorias.activar')) {
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

        return [
            'search' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_categoria.required' => 'El ID de la categoría es obligatorio.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
        ];
    }
}
