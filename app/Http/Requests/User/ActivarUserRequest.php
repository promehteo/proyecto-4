<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ActivarUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('usuarios.activar') ?? false;
    }

    protected function prepareForValidation(): void
    {
        /** @var mixed $usuario */
        $usuario = $this->route('usuario');
        $idUsuario = is_object($usuario) ? $usuario->id : $usuario;
        if ($idUsuario) {
            $this->merge([
                'id_usuario' => (int) $idUsuario,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'id_usuario' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required' => 'El ID del usuario es obligatorio.',
            'id_usuario.exists' => 'El usuario a activar no existe.',
        ];
    }
}
