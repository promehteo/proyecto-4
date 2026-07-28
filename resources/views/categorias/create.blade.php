<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Categoría') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="w-full">
                    <form method="POST" action="{{ route('categorias.store') }}" novalidate>
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="nombre_categoria" :value="__('Nombre de la Categoría')" />
                                <x-text-input id="nombre_categoria" name="nombre_categoria" type="text"
                                    class="mt-1 block w-full" :value="old('nombre_categoria')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('nombre_categoria')" />
                            </div>

                            <div>
                                <x-input-label for="id_categoria_padre_categoria" :value="__('Categoría Padre (Opcional)')" />
                                <x-select id="id_categoria_padre_categoria" name="id_categoria_padre_categoria" class="mt-1 block w-full">
                                    <option value="">— Ninguna (Categoría Principal) —</option>
                                    @foreach($categoriasPadre as $padre)
                                        <option value="{{ $padre->id_categoria }}" {{ old('id_categoria_padre_categoria') == $padre->id_categoria ? 'selected' : '' }}>
                                            {{ $padre->nombre_categoria }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-input-error class="mt-2" :messages="$errors->get('id_categoria_padre_categoria')" />
                            </div>

                            <div>
                                <x-input-label for="descripcion_categoria" :value="__('Descripción')" />
                                <x-textarea id="descripcion_categoria" name="descripcion_categoria" rows="3"
                                    class="mt-1 block w-full">{{ old('descripcion_categoria') }}</x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('descripcion_categoria')" />
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <a href="{{ route('categorias.index') }}"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </a>
                            <x-primary-button class="w-full sm:w-auto justify-center">Registrar</x-primary-button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>