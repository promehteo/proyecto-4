<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Permisos') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        confirmModal: false,
        inactivarUrl: '',
        nombrePermiso: '',
        abrirConfirmacion(url, nombre) {
            this.inactivarUrl = url;
            this.nombrePermiso = nombre;
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <form method="GET" action="{{ route('permisos.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, slug o módulo..." class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <select name="modulo" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Todos los módulos</option>
                            @foreach($modulos as $mod)
                                <option value="{{ $mod }}" {{ $modulo === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Todos los estados</option>
                            <option value="1" {{ (string)$status === '1' ? 'selected' : '' }}>Activos</option>
                            <option value="2" {{ (string)$status === '2' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Filtrar
                        </button>
                    </form>

                    @can('create', App\Models\Permiso::class)
                        <a href="{{ route('permisos.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            + Nuevo Permiso
                        </a>
                    @endcan
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Módulo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($permisos as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $p->id_permiso }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $p->nombre_permiso }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-indigo-600">{{ $p->slug_permiso }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold uppercase">{{ $p->modulo_permiso }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($p->status === 1)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-3">
                                            @can('update', $p)
                                                <a href="{{ route('permisos.edit', $p) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                            @endcan

                                            @if($p->status === 1)
                                                @can('deactivate', $p)
                                                    <button type="button" @click="abrirConfirmacion('{{ route('permisos.inactivar', $p) }}', '{{ $p->nombre_permiso }}')" class="text-red-600 hover:text-red-900">
                                                        Inactivar
                                                    </button>
                                                @endcan
                                            @else
                                                @can('activate', $p)
                                                    <form method="POST" action="{{ route('permisos.activar', $p) }}" class="inline">
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
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No se encontraron permisos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $permisos->links() }}
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación para Inactivar Permiso -->
        <div x-show="confirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full p-6 z-10">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Inactivación de Permiso</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        ¿Está seguro que desea inactivar el permiso <strong x-text="nombrePermiso"></strong>? El permiso no se borrará físicamente.
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
