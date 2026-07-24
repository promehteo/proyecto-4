<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Roles al Usuario: ') . $usuario->name }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        rolesSeleccionados: @js($rolesAsignadosIds)
    }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('usuarios.roles.update', $usuario) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6 border-b pb-4">
                        <p class="text-sm text-gray-600">
                            Seleccione los roles que tendrá asignados este usuario. Los roles desmarcados cambiarán a estado inactivo (`status = 2`) en la tabla pivote `rol_usuario` sin borrado físico.
                        </p>
                    </div>

                    <div class="space-y-4 mb-6">
                        @foreach($rolesActivos as $r)
                            <label class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-indigo-50">
                                <input type="checkbox"
                                       name="roles[]"
                                       value="{{ $r->id_rol }}"
                                       x-model.number="rolesSeleccionados"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mt-1">
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $r->nombre_rol }}</div>
                                    <div class="text-xs text-indigo-600 font-mono">{{ $r->slug_rol }}</div>
                                    @if($r->descripcion_rol)
                                        <div class="text-xs text-gray-500 mt-1">{{ $r->descripcion_rol }}</div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold uppercase">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase hover:bg-indigo-700">Guardar Roles</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
