<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bitácora de Auditoría del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Filtros Avanzados -->
                <form method="GET" action="{{ route('bitacora.index') }}" class="mb-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Búsqueda General</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por usuario, acción o descripción..." class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Usuario</label>
                            <select name="usuario_id" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Todos los usuarios</option>
                                @foreach($usuarios as $user)
                                    <option value="{{ $user->id }}" {{ (string)$usuarioId === (string)$user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Acción</label>
                            <select name="accion" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Todas las acciones</option>
                                @foreach($acciones as $acc)
                                    <option value="{{ $acc }}" {{ $accion === $acc ? 'selected' : '' }}>
                                        {{ $acc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">IP</label>
                            <input type="text" name="ip" value="{{ $ip }}" placeholder="Ej: 127.0.0.1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Tipo Entidad</label>
                            <input type="text" name="auditable_tipo" value="{{ $auditableTipo }}" placeholder="Ej: Categoria, Rol, User" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">ID Entidad</label>
                            <input type="number" name="auditable_id" value="{{ $auditableId }}" placeholder="Ej: 1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Fecha Desde</label>
                            <input type="date" name="fecha_desde" value="{{ $fechaDesde }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Fecha Hasta</label>
                            <input type="date" name="fecha_hasta" value="{{ $fechaHasta }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('bitacora.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold uppercase">Limpiar</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase hover:bg-indigo-700">Filtrar Auditoría</button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha / Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario (Snapshot)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entidad Afectada</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP / Método</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Detalle</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bitacoras as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        {{ $log->fecha_bitacora ? $log->fecha_bitacora->format('d/m/Y H:i:s') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->usuario_snapshot_bitacora }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-700">
                                        {{ $log->accion_bitacora }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        <span class="font-mono">{{ class_basename($log->auditable_tipo_bitacora) }}</span>
                                        <span class="text-gray-400">#{{ $log->auditable_id_bitacora }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        {{ $log->ip_bitacora }} <span class="text-gray-400">({{ $log->metodo_bitacora }})</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('bitacora.show', $log) }}" class="text-indigo-600 hover:text-indigo-900">Ver JSON / Detalle</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No se encontraron registros de auditoría.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $bitacoras->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
