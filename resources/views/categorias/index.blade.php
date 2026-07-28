<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            {{ __('Catálogo de Categorías') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        inactivarUrl: '',
        activarUrl: '',
        nombreCategoria: '',
        abrirConfirmacion(url, nombre) {
            this.inactivarUrl = url;
            this.nombreCategoria = nombre;
        },
        abrirConfirmacionActivar(url, nombre) {
            this.activarUrl = url;
            this.nombreCategoria = nombre;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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
                    <form method="GET" action="{{ route('categorias.index') }}" x-data x-ref="filterForm" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                        <x-text-input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Buscar por nombre..." 
                            @input.debounce.500ms="$refs.filterForm.submit()"
                            class="text-sm py-1.5 w-full"
                        />
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

                    @can('create', App\Models\Categoria::class)
                        <a href="{{ route('categorias.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 w-full md:w-auto transition-colors">
                            + Registrar Categoría
                        </a>
                    @endcan
                </div>

                <x-data-table :paginator="$categorias" :headers="['NOMBRE', 'CATEGORÍA PADRE', 'DESCRIPCIÓN', 'ESTADO', 'ACCIONES']">

                    @forelse($categorias as $cat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $cat->nombre_categoria }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-slate-100">
                                {{ $cat->padre ? $cat->padre->nombre_categoria : '— Sin padre —' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $cat->descripcion_categoria ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($cat->status === 1)
                                    <span class="px-3 py-1 inline-flex text-xs font-bold rounded-md bg-emerald-600 text-white">Activo</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs font-bold rounded-md bg-rose-600 text-white">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('categorias.show', $cat) }}" class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-150 gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver
                                    </a>

                                    @can('update', $cat)
                                        <a href="{{ route('categorias.edit', $cat) }}" class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-md bg-amber-500 text-white hover:bg-amber-600 transition-colors duration-150 gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            Editar
                                        </a>
                                    @endcan

                                    @if($cat->status === 1)
                                        @can('deactivate', $cat)
                                            <button type="button" @click="abrirConfirmacion('{{ route('categorias.inactivar', $cat) }}', '{{ $cat->nombre_categoria }}'); $dispatch('open-modal', 'confirmar-inactivacion')" class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-md bg-red-600 text-white hover:bg-red-500 transition-colors duration-150 gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Inactivar
                                            </button>
                                        @endcan
                                    @else
                                        @can('activate', $cat)
                                            <button type="button" @click="abrirConfirmacionActivar('{{ route('categorias.activar', $cat) }}', '{{ $cat->nombre_categoria }}'); $dispatch('open-modal', 'confirmar-activacion')" class="px-3 py-1 inline-flex items-center text-xs font-bold rounded-md bg-emerald-600 text-white hover:bg-emerald-700 transition-colors duration-150 gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Activar
                                            </button>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-400">No se encontraron categorías.</td>
                        </tr>
                    @endforelse
                </x-data-table>
            </x-card>
        </div>

        <x-modal name="confirmar-inactivacion" focusable>
            <div class="p-6">
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">
                    Confirmar Inactivación de Categoría
                </h3>
                
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                    ¿Está seguro que desea inactivar la categoría <strong x-text="nombreCategoria"></strong>? Ningún registro se eliminará físicamente.
                </p>
                
                <div class="flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-semibold uppercase rounded-md hover:bg-slate-300 dark:hover:bg-slate-600">
                        Cancelar
                    </button>
                    
                    <form x-bind:action="inactivarUrl" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="button" onclick="this.closest('form').submit();" class="px-4 py-2 bg-red-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-red-700">
                            Inactivar
                        </button>
                    </form>
                </div>
            </div>
        </x-modal>

        <x-modal name="confirmar-activacion" focusable>
            <div class="p-6">
                <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">
                    Confirmar Activación de Categoría
                </h3>
                
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                    ¿Está seguro que desea activar la categoría <strong x-text="nombreCategoria"></strong>?
                </p>
                
                <div class="flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-semibold uppercase rounded-md hover:bg-slate-300 dark:hover:bg-slate-600">
                        Cancelar
                    </button>
                    
                    <form x-bind:action="activarUrl" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="button" onclick="this.closest('form').submit();" class="px-4 py-2 bg-emerald-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-emerald-700">
                            Activar
                        </button>
                    </form>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>
