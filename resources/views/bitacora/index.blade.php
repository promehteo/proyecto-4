<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bitácora de Auditoría del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
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
                                    <option value="{{ $user->id }}" {{ (string)$usuarioId === (string)$user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
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
                            <x-input-label :value="__('Tipo Entidad')" />
                            <x-text-input 
                                type="text" 
                                name="auditable_tipo" 
                                value="{{ $auditableTipo }}" 
                                placeholder="Ej: Categoria, Rol, User" 
                                class="mt-1 w-full text-sm py-1.5" 
                            />
                        </div>
                        <div>
                            <x-input-label :value="__('ID Entidad')" />
                            <x-text-input 
                                type="number" 
                                name="auditable_id" 
                                value="{{ $auditableId }}" 
                                placeholder="Ej: 1" 
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

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('bitacora.index') }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 text-slate-700 dark:text-gray-300 rounded-md text-xs font-semibold uppercase hover:bg-gray-50 dark:hover:bg-gray-700">Limpiar</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase hover:bg-indigo-500">Filtrar Auditoría</button>
                    </div>
                </form>

                <x-data-table :paginator="$bitacoras" :headers="['FECHA / HORA', 'USUARIO (SNAPSHOT)', 'ACCIÓN', 'ENTIDAD AFECTADA', 'IP / MÉTODO', 'ACCIONES']">
                    @forelse($bitacoras as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">
                                {{ $log->fecha_bitacora ? $log->fecha_bitacora->format('d/m/Y H:i:s') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-slate-200">
                                {{ $log->usuario_snapshot_bitacora }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $log->accion_bitacora }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                <span class="font-mono bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded">{{ class_basename($log->auditable_tipo_bitacora) }}</span>
                                <span class="text-slate-400">#{{ $log->auditable_id_bitacora }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">
                                {{ $log->ip_bitacora }} <span class="text-slate-400">({{ $log->metodo_bitacora }})</span>
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
