<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle de Bitácora') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold">{{ $bitacora->accion_bitacora }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $bitacora->descripcion_bitacora }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm mb-6">
                    <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-md border border-slate-200 dark:border-slate-800 space-y-2">
                        <p class="text-slate-700 dark:text-slate-300"><strong>Usuario Snapshot:</strong> {{ $bitacora->usuario_snapshot_bitacora }}</p>
                        <p class="text-slate-700 dark:text-slate-300"><strong>ID Usuario Relacionado:</strong> #{{ $bitacora->id_usuario_bitacora ?? 'N/A' }}</p>
                        <p class="text-slate-700 dark:text-slate-300"><strong>Entidad Afectada:</strong> {{ class_basename($bitacora->auditable_tipo_bitacora) }}</p>
                        <p class="text-slate-700 dark:text-slate-300"><strong>ID Afectado:</strong> #{{ $bitacora->auditable_id_bitacora }}</p>
                        <p class="text-slate-700 dark:text-slate-300"><strong>Fecha / Hora:</strong> {{ $bitacora->fecha_bitacora ? $bitacora->fecha_bitacora->format('d/m/Y H:i:s') : 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-md border border-slate-200 dark:border-slate-800 space-y-2">
                        <p class="text-slate-700 dark:text-slate-300"><strong>IP Origen:</strong> {{ $bitacora->ip_bitacora }}</p>
                        <p class="text-slate-700 dark:text-slate-300"><strong>Método HTTP:</strong> <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $bitacora->metodo_bitacora }}</span></p>
                        <p class="text-slate-700 dark:text-slate-300"><strong>URL Solicitada:</strong> <span class="text-xs break-all font-mono">{{ $bitacora->url_bitacora }}</span></p>
                        <p class="text-slate-700 dark:text-slate-300"><strong>User Agent:</strong> <span class="text-xs break-all text-slate-500">{{ $bitacora->user_agent_bitacora }}</span></p>
                    </div>
                </div>

                <!-- Comparación de JSON Formateado -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label :value="__('Valores Anteriores (JSON)')" class="mb-2" />
                        <pre class="bg-slate-900 text-emerald-400 p-4 rounded-md text-xs overflow-x-auto font-mono min-h-[150px] border border-slate-800">{{ json_encode($bitacora->valores_anteriores_bitacora, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'null' }}</pre>
                    </div>
                    <div>
                        <x-input-label :value="__('Valores Nuevos (JSON)')" class="mb-2" />
                        <pre class="bg-slate-900 text-cyan-400 p-4 rounded-md text-xs overflow-x-auto font-mono min-h-[150px] border border-slate-800">{{ json_encode($bitacora->valores_nuevos_bitacora, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'null' }}</pre>
                    </div>
                </div>

                <!-- Botón Volver al final -->
                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('bitacora.index') }}"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>