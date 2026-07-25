<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catálogo de Categorías') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        confirmModal: false,
        inactivarUrl: '',
        nombreCategoria: '',
        abrirConfirmacion(url, nombre) {
            this.inactivarUrl = url;
            this.nombreCategoria = nombre;
            this.confirmModal = true;
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

            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-slate-200 dark:border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <form method="GET" action="{{ route('categorias.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre..." class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Todos los estados</option>
                            <option value="1" {{ (string)$status === '1' ? 'selected' : '' }}>Activos</option>
                            <option value="2" {{ (string)$status === '2' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Filtrar
                        </button>
                    </form>

                    @can('create', App\Models\Categoria::class)
                        <a href="{{ route('categorias.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            + Nueva Categoría
                        </a>
                    @endcan
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría Padre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($categorias as $cat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $cat->id_categoria }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cat->nombre_categoria }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $cat->padre ? $cat->padre->nombre_categoria : '— Sin padre —' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $cat->descripcion_categoria ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($cat->status === 1)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            @can('update', $cat)
                                                <a href="{{ route('categorias.edit', $cat) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                            @endcan

                                            @if($cat->status === 1)
                                                @can('deactivate', $cat)
                                                    <button type="button" @click="abrirConfirmacion('{{ route('categorias.inactivar', $cat) }}', '{{ $cat->nombre_categoria }}')" class="text-red-600 hover:text-red-900">
                                                        Inactivar
                                                    </button>
                                                @endcan
                                            @else
                                                @can('activate', $cat)
                                                    <form method="POST" action="{{ route('categorias.activar', $cat) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-green-600 hover:text-green-900">Activar</button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No se encontraron categorías.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $categorias->links() }}
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación para Inactivar -->
        <div x-show="confirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full p-6 z-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Inactivación</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        ¿Está seguro que desea inactivar la categoría <strong x-text="nombreCategoria"></strong>? Ningún registro se eliminará físicamente.
                    </p>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="confirmModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 text-xs font-semibold uppercase rounded-md hover:bg-gray-300">
                            Cancelar
                        </button>
                        <form :action="inactivarUrl" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-red-700">
                                Inactivar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
