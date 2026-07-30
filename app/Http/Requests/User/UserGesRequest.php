<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserGesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return $user->hasPermissionTo('usuarios.editar');
        }

        return $user->hasPermissionTo('usuarios.crear');
    }

    public function rules(): array
    {
        /** @var User|null $usuario */
        $usuario = $this->route('usuario');
        $userId = $usuario ? $usuario->id_user : null;

        if (!$userId && ($this->input('usuario_id') || $this->input('_model_id'))) {
            $userId = (int) ($this->input('usuario_id') ?: $this->input('_model_id'));
        }

        $passwordRule = $userId ? ['nullable', 'string', 'min:8', 'confirmed'] : ['required', 'string', 'min:8', 'confirmed'];

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'cedula' => ['nullable', 'integer', 'digits_between:1,8', "unique:user,cedula,{$userId},id_user"],
            'email' => ['required', 'email', 'max:255', "unique:user,email,{$userId},id_user"],
            'password' => $passwordRule,
            'status' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del usuario es obligatorio.',
            'apellido.required' => 'El apellido del usuario es obligatorio.',
            'cedula.integer' => 'La cédula debe ser un número entero.',
            'cedula.digits_between' => 'La cédula debe tener un máximo de 8 dígitos.',
            'cedula.unique' => 'Esta cédula ya se encuentra registrada.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese una dirección de correo válida.',
            'email.unique' => 'El correo electrónico ya se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser activo (1) o inactivo (2).',
        ];
    }
}
