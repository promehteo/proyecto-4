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
                    <form method="POST" action="{{ route('permisos.update', $permiso) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="nombre_permiso" :value="__('Nombre del Permiso')" />
                        <x-text-input id="nombre_permiso" name="nombre_permiso" type="text" class="mt-1 block w-full" :value="old('nombre_permiso', $permiso->nombre_permiso)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nombre_permiso')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="slug_permiso" :value="__('Slug del Permiso')" />
                        <x-text-input id="slug_permiso" name="slug_permiso" type="text" class="mt-1 block w-full font-mono text-sm" :value="old('slug_permiso', $permiso->slug_permiso)" required placeholder="ej: productos.crear" />
                        <x-input-error class="mt-2" :messages="$errors->get('slug_permiso')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="modulo_permiso" :value="__('Módulo del Permiso')" />
                        <x-text-input id="modulo_permiso" name="modulo_permiso" type="text" class="mt-1 block w-full" :value="old('modulo_permiso', $permiso->modulo_permiso)" required list="list-modulos" />
                        <datalist id="list-modulos">
                            @foreach($modulosExistentes as $mod)
                                <option value="{{ $mod }}">
                            @endforeach
                        </datalist>
                        <x-input-error class="mt-2" :messages="$errors->get('modulo_permiso')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="descripcion_permiso" :value="__('Descripción')" />
                        <textarea id="descripcion_permiso" name="descripcion_permiso" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descripcion_permiso', $permiso->descripcion_permiso) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('descripcion_permiso')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="status" :value="__('Estado')" />
                        <select id="status" name="status" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="1" {{ old('status', $permiso->status) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="2" {{ old('status', $permiso->status) == 2 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('permisos.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Cancelar</a>
                        <x-primary-button class="ms-3">Actualizar Permiso</x-primary-button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
