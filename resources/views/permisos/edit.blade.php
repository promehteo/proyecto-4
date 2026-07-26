<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Permiso: ') . $permiso->nombre_permiso }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('permisos.update', $permiso) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nombre del Permiso *</label>
                        <input type="text" name="nombre_permiso" value="{{ old('nombre_permiso', $permiso->nombre_permiso) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nombre_permiso') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Slug del Permiso *</label>
                        <input type="text" name="slug_permiso" value="{{ old('slug_permiso', $permiso->slug_permiso) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm">
                        @error('slug_permiso') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Módulo del Permiso *</label>
                        <input type="text" name="modulo_permiso" value="{{ old('modulo_permiso', $permiso->modulo_permiso) }}" required list="list-modulos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <datalist id="list-modulos">
                            @foreach($modulosExistentes as $mod)
                                <option value="{{ $mod }}">
                            @endforeach
                        </datalist>
                        @error('modulo_permiso') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Descripción</label>
                        <textarea name="descripcion_permiso" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('descripcion_permiso', $permiso->descripcion_permiso) }}</textarea>
                        @error('descripcion_permiso') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Estado *</label>
                        <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="1" {{ old('status', $permiso->status) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="2" {{ old('status', $permiso->status) == 2 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('permisos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold uppercase">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase hover:bg-indigo-700">Actualizar Permiso</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
