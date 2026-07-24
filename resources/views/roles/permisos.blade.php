<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Permisos al Rol: ') . $rol->nombre_rol }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        permisosSeleccionados: @js($permisosAsignadosIds),
        marcarTodos(moduloId) {
            let inputs = document.querySelectorAll('.modulo-' + moduloId);
            inputs.forEach(el => {
                let id = parseInt(el.value);
                if (!this.permisosSeleccionados.includes(id)) {
                    this.permisosSeleccionados.push(id);
                }
            });
        },
        desmarcarTodos(moduloId) {
            let inputs = document.querySelectorAll('.modulo-' + moduloId);
            inputs.forEach(el => {
                let id = parseInt(el.value);
                this.permisosSeleccionados = this.permisosSeleccionados.filter(item => item !== id);
            });
        }
    }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('roles.permisos.update', $rol) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6 flex justify-between items-center border-b pb-4">
                        <p class="text-sm text-gray-600">
                            Marque o desmarque los permisos correspondientes. Al guardar, los permisos desmarcados pasarán a estado inactivo (status = 2) en la tabla pivote sin borrado físico.
                        </p>
                        <div class="flex gap-2">
                            <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold uppercase">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase hover:bg-indigo-700">Guardar Permisos</button>
                        </div>
                    </div>

                    <div class="space-y-8">
                        @foreach($permisosAgrupados as $modulo => $permisosGroup)
                            @php $moduloSlug = Str::slug($modulo); @endphp
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="flex justify-between items-center mb-3 border-b pb-2">
                                    <h3 class="text-base font-bold text-gray-800 uppercase tracking-wider">
                                        Módulo: {{ $modulo }}
                                    </h3>
                                    <div class="flex gap-2 text-xs">
                                        <button type="button" @click="marcarTodos('{{ $moduloSlug }}')" class="text-indigo-600 hover:underline">Marcar Todos</button>
                                        <span class="text-gray-400">|</span>
                                        <button type="button" @click="desmarcarTodos('{{ $moduloSlug }}')" class="text-red-600 hover:underline">Desmarcar Todos</button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($permisosGroup as $p)
                                        <label class="flex items-start gap-2 bg-white p-2 rounded border border-gray-200 cursor-pointer hover:bg-indigo-50">
                                            <input type="checkbox"
                                                   name="permisos[]"
                                                   value="{{ $p->id_permiso }}"
                                                   x-model.number="permisosSeleccionados"
                                                   class="modulo-{{ $moduloSlug }} rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mt-1">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-800">{{ $p->nombre_permiso }}</div>
                                                <div class="text-xs text-indigo-600 font-mono">{{ $p->slug_permiso }}</div>
                                                @if($p->descripcion_permiso)
                                                    <div class="text-xs text-gray-500 mt-1">{{ $p->descripcion_permiso }}</div>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t">
                        <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold uppercase">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase hover:bg-indigo-700">Guardar Permisos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
