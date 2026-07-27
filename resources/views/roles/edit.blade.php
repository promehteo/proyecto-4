<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Rol: ') . $rol->nombre_rol }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('roles.update', $rol) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="nombre_rol" :value="__('Nombre del Rol')" />
                        <x-text-input id="nombre_rol" name="nombre_rol" type="text" class="mt-1 block w-full" :value="old('nombre_rol', $rol->nombre_rol)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nombre_rol')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="slug_rol" :value="__('Slug del Rol')" />
                        <x-text-input id="slug_rol" name="slug_rol" type="text" class="mt-1 block w-full font-mono text-sm" :value="old('slug_rol', $rol->slug_rol)" required placeholder="ej: auditor_inventario" />
                        <x-input-error class="mt-2" :messages="$errors->get('slug_rol')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="descripcion_rol" :value="__('Descripción')" />
                        <textarea id="descripcion_rol" name="descripcion_rol" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descripcion_rol', $rol->descripcion_rol) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('descripcion_rol')" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="status" :value="__('Estado')" />
                        <select id="status" name="status" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="1" {{ old('status', $rol->status) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="2" {{ old('status', $rol->status) == 2 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Cancelar</a>
                        <x-primary-button class="ms-3">Actualizar Rol</x-primary-button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
