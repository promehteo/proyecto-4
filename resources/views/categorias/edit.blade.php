<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categoría: ') . $categoria->nombre_categoria }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('categorias.update', $categoria) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nombre de la Categoría *</label>
                        <input type="text" name="nombre_categoria" value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nombre_categoria') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Categoría Padre (Opcional)</label>
                        <select name="id_categoria_padre_categoria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">— Ninguna (Categoría Principal) —</option>
                            @foreach($categoriasPadre as $padre)
                                <option value="{{ $padre->id_categoria }}" {{ old('id_categoria_padre_categoria', $categoria->id_categoria_padre_categoria) == $padre->id_categoria ? 'selected' : '' }}>
                                    {{ $padre->nombre_categoria }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_categoria_padre_categoria') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Descripción</label>
                        <textarea name="descripcion_categoria" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('descripcion_categoria', $categoria->descripcion_categoria) }}</textarea>
                        @error('descripcion_categoria') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Estado *</label>
                        <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="1" {{ old('status', $categoria->status) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="2" {{ old('status', $categoria->status) == 2 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('categorias.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-xs font-semibold uppercase">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase hover:bg-indigo-700">Actualizar</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
