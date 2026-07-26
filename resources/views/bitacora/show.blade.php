<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Registro de Bitácora #') . $bitacora->id_bitacora }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl space-y-6">
                    <div class="flex justify-between items-center border-b pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $bitacora->accion_bitacora }}</h3>
                        <p class="text-sm text-gray-500">{{ $bitacora->descripcion_bitacora }}</p>
                    </div>
                    <a href="{{ route('bitacora.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold uppercase">Volver al listado</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="bg-gray-50 p-4 rounded-md space-y-2">
                        <p><strong>Usuario Snapshot:</strong> {{ $bitacora->usuario_snapshot_bitacora }}</p>
                        <p><strong>ID Usuario Relacionado:</strong> {{ $bitacora->id_usuario_bitacora ?? 'N/A' }}</p>
                        <p><strong>Entidad Afectada:</strong> {{ $bitacora->auditable_tipo_bitacora }}</p>
                        <p><strong>ID Afectado:</strong> #{{ $bitacora->auditable_id_bitacora }}</p>
                        <p><strong>Fecha / Hora:</strong> {{ $bitacora->fecha_bitacora ? $bitacora->fecha_bitacora->format('d/m/Y H:i:s') : 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md space-y-2">
                        <p><strong>IP Origen:</strong> {{ $bitacora->ip_bitacora }}</p>
                        <p><strong>Método HTTP:</strong> {{ $bitacora->metodo_bitacora }}</p>
                        <p><strong>URL Solicitada:</strong> <span class="text-xs break-all font-mono">{{ $bitacora->url_bitacora }}</span></p>
                        <p><strong>User Agent:</strong> <span class="text-xs break-all text-gray-600">{{ $bitacora->user_agent_bitacora }}</span></p>
                    </div>
                </div>

                <!-- Comparación de JSON Formateado -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Valores Anteriores (JSON)</h4>
                        <pre class="bg-gray-900 text-green-400 p-4 rounded-md text-xs overflow-x-auto font-mono min-h-[150px]">{{ json_encode($bitacora->valores_anteriores_bitacora, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'null' }}</pre>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Valores Nuevos (JSON)</h4>
                        <pre class="bg-gray-900 text-blue-400 p-4 rounded-md text-xs overflow-x-auto font-mono min-h-[150px]">{{ json_encode($bitacora->valores_nuevos_bitacora, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'null' }}</pre>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
