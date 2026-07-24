<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Bitacora;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class BitacoraService
{
    /**
     * Campos sensibles que se excluirán de los valores en bitácora.
     */
    protected static array $sensitiveFields = [
        'password',
        'password_confirmation',
        'remember_token',
        'token',
        'secret',
        'api_token',
    ];

    /**
     * Registra una acción en la bitácora.
     */
    public static function registrar(
        Model|string $auditable,
        string $accion,
        ?array $valoresAnteriores = null,
        ?array $valoresNuevos = null,
        ?string $descripcion = null
    ): Bitacora {
        $user = Auth::user();

        $auditableTipo = is_object($auditable) ? get_class($auditable) : (string) $auditable;
        $auditableId = is_object($auditable) ? ($auditable->getKey() ?? 0) : 0;

        $userSnapshot = $user ? "{$user->name} ({$user->email})" : 'Sistema / Anónimo';

        $cleanAnteriores = $valoresAnteriores ? static::cleanSensitiveData($valoresAnteriores) : null;
        $cleanNuevos = $valoresNuevos ? static::cleanSensitiveData($valoresNuevos) : null;

        return Bitacora::create([
            'id_usuario_bitacora' => $user?->id,
            'usuario_snapshot_bitacora' => $userSnapshot,
            'accion_bitacora' => $accion,
            'auditable_tipo_bitacora' => $auditableTipo,
            'auditable_id_bitacora' => $auditableId,
            'valores_anteriores_bitacora' => $cleanAnteriores,
            'valores_nuevos_bitacora' => $cleanNuevos,
            'ip_bitacora' => Request::ip(),
            'user_agent_bitacora' => Request::userAgent(),
            'url_bitacora' => Request::fullUrl(),
            'metodo_bitacora' => Request::method(),
            'descripcion_bitacora' => $descripcion,
            'fecha_bitacora' => now(),
            'status' => 1,
        ]);
    }

    /**
     * Remueve recursivamente campos sensibles de los arrays de datos.
     */
    protected static function cleanSensitiveData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array(strtolower((string) $key), static::$sensitiveFields, true)) {
                unset($data[$key]);
                continue;
            }

            if (is_array($value)) {
                $data[$key] = static::cleanSensitiveData($value);
            }
        }

        return $data;
    }
}
