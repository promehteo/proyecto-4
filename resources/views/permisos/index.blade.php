<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            {{ __('Gestión de Permisos') }}
        </h2>
    </x-slot>

    <div class="pt-6 pb-12" x-data="{
        confirmModal: false,
        inactivarUrl: '',
        nombrePermiso: '',
        abrirConfirmacion(url, nombre) {
            this.inactivarUrl = url;
            this.nombrePermiso = nombre;
            this.confirmModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Error:</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <x-card>
                <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center mb-6 gap-4 w-full">
                    <form method="GET" action="{{ route('permisos.index') }}" x-data x-ref="filterForm" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                        <x-text-input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Buscar por nombre, clave o módulo..." 
                            @input.debounce.500ms="$refs.filterForm.submit()"
                            class="text-sm py-1.5 w-full"
                        />
                        <x-select 
                            name="modulo" 
                            @change="$refs.filterForm.submit()"
                            class="text-sm py-1.5 w-full"
                        >
                            <option value="">Todos los módulos</option>
                            @foreach($modulos as $mod)
                                <option value="{{ $mod }}" {{ $modulo === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                            @endforeach
                        </x-select>
                        <x-select 
                            name="status" 
                            @change="$refs.filterForm.submit()"
                            class="text-sm py-1.5 w-full"
                        >
                            <option value="">Todos los estados</option>
                            <option value="1" {{ (string)$status === '1' ? 'selected' : '' }}>Activos</option>
                            <option value="2" {{ (string)$status === '2' ? 'selected' : '' }}>Inactivos</option>
                        </x-select>
                    </form>

                    @can('create', App\Models\Permiso::class)
                        <a href="{{ route('permisos.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 w-full md:w-auto transition-colors">
                            + Registrar Permiso
                        </a>
                    @endcan
                </div>

                <x-data-table :paginator="$permisos" :headers="['ID', 'NOMBRE', 'CLAVE', 'MÓDULO', 'ESTADO', 'ACCIONES']">
                    @forelse($permisos as $p)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">#{{ $p->id_permiso }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $p->nombre_permiso }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $p->clave_permiso }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md text-xs">{{ $p->modulo_permiso }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($p->status === 1)
                                    <span class="px-3 py-1 inline-flex text-xs font-bold rounded-md bg-emerald-600 text-white">Activo</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs font-bold rounded-md bg-rose-600 text-white">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('permisos.show', $p) }}" class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-150 gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver
                                    </a>

                                    @can('update', $p)
                                        <a href="{{ route('permisos.edit', $p) }}" class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-md bg-amber-500 text-white hover:bg-amber-600 transition-colors duration-150 gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            Editar
                                        </a>
                                    @endcan

                                    @if($p->status === 1)
                                        @can('deactivate', $p)
                                            <button type="button" @click="abrirConfirmacion('{{ route('permisos.inactivar', $p) }}', '{{ $p->nombre_permiso }}')" class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-md bg-red-600 text-white hover:bg-red-500 transition-colors duration-150 gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Inactivar
                                            </button>
                                        @endcan
                                    @else
                                        @can('activate', $p)
                                            <form method="POST" action="{{ route('permisos.activar', $p) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-md bg-emerald-600 text-white hover:bg-emerald-500 transition-colors duration-150 gap-1">
                                                    Activar
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-slate-500">No se encontraron permisos.</td>
                        </tr>
                    @endforelse
                </x-data-table>
            </x-card>
        </div>

        <!-- Modal de Confirmación para Inactivar Permiso -->
        <div x-show="confirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500/75 dark:bg-slate-900/80 transition-opacity"></div>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full p-6 z-10">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold mb-4">Confirmar Inactivación de Permiso</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
                        ¿Está seguro que desea inactivar el permiso <strong class="text-slate-900 dark:text-slate-100" x-text="nombrePermiso"></strong>? El permiso no se borrará físicamente.
                    </p>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <button type="button" @click="confirmModal = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 text-slate-700 dark:text-slate-300 text-xs font-semibold uppercase rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 w-full sm:w-auto">
                            Cancelar
                        </button>
                        <form :action="inactivarUrl" method="POST" class="w-full sm:w-auto">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-red-500 w-full justify-center">
                                Inactivar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
