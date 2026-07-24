<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class InactivarUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('usuarios.inactivar') ?? false;
    }

    protected function prepareForValidation(): void
    {
        /** @var User|null $usuario */
        $usuario = $this->route('usuario');
        if ($usuario) {
            $this->merge([
                'id_usuario' => $usuario->id,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'id_usuario' => [
                'required',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ((int) $this->user()->id === (int) $value) {
                        $fail('No puede inactivar su propio usuario.');
                        return;
                    }

                    $targetUser = User::find($value);
                    if ($targetUser && $targetUser->hasRole('admin')) {
                        // Verificar si dejaría al sistema sin admins activos
                        $adminsActivosCount = User::where('status', 1)
                            ->whereHas('roles', function ($q) {
                                $q->where('rol.status', 1)
                                  ->where('rol_usuario.status', 1)
                                  ->where('slug_rol', 'admin');
                            })
                            ->where('id', '!=', $value)
                            ->count();

                        if ($adminsActivosCount === 0) {
                            $fail('No se puede inactivar al usuario porque es el único administrador activo del sistema.');
                        }
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required' => 'El ID del usuario es obligatorio.',
            'id_usuario.exists' => 'El usuario a inactivar no existe.',
        ];
    }
}
