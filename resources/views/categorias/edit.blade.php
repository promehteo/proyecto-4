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
                    <form method="POST" action="{{ route('categorias.update', $categoria) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="nombre_categoria" :value="__('Nombre de la Categoría')" />
                        <x-text-input id="nombre_categoria" name="nombre_categoria" type="text" class="mt-1 block w-full" :value="old('nombre_categoria', $categoria->nombre_categoria)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nombre_categoria')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="id_categoria_padre_categoria" :value="__('Categoría Padre (Opcional)')" />
                        <select id="id_categoria_padre_categoria" name="id_categoria_padre_categoria" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">— Ninguna (Categoría Principal) —</option>
                            @foreach($categoriasPadre as $padre)
                                <option value="{{ $padre->id_categoria }}" {{ old('id_categoria_padre_categoria', $categoria->id_categoria_padre_categoria) == $padre->id_categoria ? 'selected' : '' }}>
                                    {{ $padre->nombre_categoria }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('id_categoria_padre_categoria')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="descripcion_categoria" :value="__('Descripción')" />
                        <textarea id="descripcion_categoria" name="descripcion_categoria" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descripcion_categoria', $categoria->descripcion_categoria) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('descripcion_categoria')" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('categorias.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Cancelar</a>
                        <x-primary-button class="ms-3">Actualizar</x-primary-button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
