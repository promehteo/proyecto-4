<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            {{ __('Bitácora de Auditoría del Sistema') }}
        </h2>
    </x-slot>

    <div class="pt-6 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <!-- Filtros Avanzados -->
                <form method="GET" action="{{ route('bitacora.index') }}" x-data x-ref="filterForm" class="mb-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label :value="__('Búsqueda General')" />
                            <x-text-input 
                                type="text" 
                                name="search" 
                                value="{{ $search }}" 
                                placeholder="Buscar por usuario, acción..." 
                                @input.debounce.500ms="$refs.filterForm.submit()"
                                class="mt-1 w-full text-sm py-1.5" 
                            />
                        </div>
                        <div>
                            <x-input-label :value="__('Usuario')" />
                            <x-select 
                                name="usuario_id" 
                                @change="$refs.filterForm.submit()"
                                class="mt-1 w-full text-sm py-1.5"
                            >
                                <option value="">Todos los usuarios</option>
                                @foreach($usuarios as $user)
                                    <option value="{{ $user->id_user }}" {{ (string)$usuarioId === (string)$user->id_user ? 'selected' : '' }}>
                                        {{ $user->nombre }} {{ $user->apellido }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                        <div>
                            <x-input-label :value="__('Acción')" />
                            <x-select 
                                name="accion" 
                                @change="$refs.filterForm.submit()"
                                class="mt-1 w-full text-sm py-1.5"
                            >
                                <option value="">Todas las acciones</option>
                                @foreach($acciones as $acc)
                                    <option value="{{ $acc }}" {{ $accion === $acc ? 'selected' : '' }}>
                                        {{ $acc }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                        <div>
                            <x-input-label :value="__('IP')" />
                            <x-text-input 
                                type="text" 
                                name="ip" 
                                value="{{ $ip }}" 
                                placeholder="Ej: 127.0.0.1" 
                                @input.debounce.500ms="$refs.filterForm.submit()"
                                class="mt-1 w-full text-sm py-1.5" 
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label :value="__('Módulo')" />
                            <x-text-input 
                                type="text" 
                                name="auditable_tipo" 
                                value="{{ $auditableTipo }}" 
                                placeholder="Ej: Rol, User" 
                                @input.debounce.500ms="$refs.filterForm.submit()"
                                class="mt-1 w-full text-sm py-1.5" 
                            />
                        </div>
                        <div>
                            <x-input-label :value="__('ID Registro')" />
                            <x-text-input 
                                type="number" 
                                name="auditable_id" 
                                value="{{ $auditableId }}" 
                                placeholder="Ej: 1" 
                                @input.debounce.500ms="$refs.filterForm.submit()"
                                class="mt-1 w-full text-sm py-1.5" 
                            />
                        </div>
                        <div>
                            <x-input-label :value="__('Fecha Desde')" />
                            <x-date-input 
                                name="fecha_desde" 
                                value="{{ $fechaDesde }}" 
                                @change="$refs.filterForm.submit()"
                                class="mt-1 w-full text-sm py-1.5" 
                            />
                        </div>
                        <div>
                            <x-input-label :value="__('Fecha Hasta')" />
                            <x-date-input 
                                name="fecha_hasta" 
                                value="{{ $fechaHasta }}" 
                                @change="$refs.filterForm.submit()"
                                class="mt-1 w-full text-sm py-1.5" 
                            />
                        </div>
                    </div>

                    @if($search || $usuarioId || $accion || $ip || $auditableTipo || $auditableId || $fechaDesde || $fechaHasta)
                        <div class="flex justify-end pt-2">
                            <a href="{{ route('bitacora.index') }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 text-slate-700 dark:text-gray-300 rounded-md text-xs font-semibold uppercase hover:bg-gray-50 dark:hover:bg-gray-700">Limpiar Filtros</a>
                        </div>
                    @endif
                </form>

                <x-data-table :paginator="$bitacoras" :headers="['FECHA / HORA', 'USUARIO', 'ACCIÓN', 'ENTIDAD AFECTADA', 'IP / MÉTODO', 'ACCIONES']">
                    @forelse($bitacoras as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-slate-900 dark:text-slate-100">
                                {{ $log->fecha_bitacora ? $log->fecha_bitacora->format('d/m/Y H:i:s') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $log->usuario ? ($log->usuario->nombre . ' ' . $log->usuario->apellido) : 'Sistema / Anónimo' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $log->accion_bitacora }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-900 dark:text-slate-100">
                                <span class="font-mono bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded">{{ class_basename($log->modulo_bitacora) }}</span>
                                <span class="text-slate-900 dark:text-slate-100">#{{ $log->registro_id_bitacora }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-slate-900 dark:text-slate-100">
                                {{ $log->ip_bitacora }} <span class="text-slate-900 dark:text-slate-100">({{ $log->metodo_bitacora }})</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('bitacora.show', $log) }}" class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-150 gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-slate-500">No se encontraron registros de auditoría.</td>
                        </tr>
                    @endforelse
                </x-data-table>
            </x-card>
        </div>
    </div>
</x-app-layout>
