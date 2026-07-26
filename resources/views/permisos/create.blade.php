<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Permiso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl space-y-6">
                    <form method="POST" action="{{ route('permisos.store') }}">
                    @csrf

                    <div>
                        <x-input-label for="nombre_permiso" :value="__('Nombre del Permiso')" />
                        <x-text-input id="nombre_permiso" name="nombre_permiso" type="text" class="mt-1 block w-full" :value="old('nombre_permiso')" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nombre_permiso')" />
                    </div>

                    <div>
                        <x-input-label for="slug_permiso" :value="__('Slug del Permiso')" />
                        <x-text-input id="slug_permiso" name="slug_permiso" type="text" class="mt-1 block w-full font-mono text-sm" :value="old('slug_permiso')" required placeholder="ej: productos.crear" />
                        <x-input-error class="mt-2" :messages="$errors->get('slug_permiso')" />
                    </div>

                    <div>
                        <x-input-label for="modulo_permiso" :value="__('Módulo del Permiso')" />
                        <x-text-input id="modulo_permiso" name="modulo_permiso" type="text" class="mt-1 block w-full" :value="old('modulo_permiso')" required list="list-modulos" placeholder="ej: inventario" />
                        <datalist id="list-modulos">
                            @foreach($modulosExistentes as $mod)
                                <option value="{{ $mod }}">
                            @endforeach
                        </datalist>
                        <x-input-error class="mt-2" :messages="$errors->get('modulo_permiso')" />
                    </div>

                    <div>
                        <x-input-label for="descripcion_permiso" :value="__('Descripción')" />
                        <textarea id="descripcion_permiso" name="descripcion_permiso" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descripcion_permiso') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('descripcion_permiso')" />
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Estado')" />
                        <select id="status" name="status" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('permisos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{ __('Cancelar') }}</a>
                        <x-primary-button>{{ __('Guardar Permiso') }}</x-primary-button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
