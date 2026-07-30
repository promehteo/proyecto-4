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

        $modulo = is_object($auditable) ? get_class($auditable) : (string) $auditable;
        $registroId = is_object($auditable) ? ($auditable->getKey() ?? 0) : 0;

        $cleanAnteriores = $valoresAnteriores ? static::cleanSensitiveData($valoresAnteriores) : null;
        $cleanNuevos = $valoresNuevos ? static::cleanSensitiveData($valoresNuevos) : null;

        return Bitacora::create([
            'id_usuario_bitacora' => $user?->id_user,
            'accion_bitacora' => $accion,
            'modulo_bitacora' => $modulo,
            'registro_id_bitacora' => $registroId,
            'valores_anteriores_bitacora' => $cleanAnteriores,
            'valores_nuevos_bitacora' => $cleanNuevos,
            'ip_bitacora' => Request::ip(),
            'navegador_bitacora' => Request::userAgent(),
            'url_bitacora' => Request::fullUrl(),
            'metodo_bitacora' => Request::method(),
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
