<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuario: ') . $usuario->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl space-y-6">
                    <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $usuario->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $usuario->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Nueva Contraseña (Opcional)')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Dejar en blanco para mantener la actual" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirmar Nueva Contraseña')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" placeholder="Confirmar contraseña" autocomplete="new-password" />
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Estado')" />
                        <select id="status" name="status" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="1" {{ old('status', $usuario->status) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="2" {{ old('status', $usuario->status) == 2 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('usuarios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{ __('Cancelar') }}</a>
                        <x-primary-button>{{ __('Actualizar Usuario') }}</x-primary-button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
