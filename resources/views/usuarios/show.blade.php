<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle de Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <!-- Cabecera de la tarjeta -->
                <div class="border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold">Información General</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Detalles del usuario registrado en el sistema.</p>
                </div>

                <!-- Contenido de detalles -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Nombre -->
                    <div>
                        <x-input-label :value="__('Nombre')" />
                        <p class="text-slate-800 dark:text-slate-200 font-semibold text-lg mt-1">{{ $usuario->nombre }}</p>
                    </div>

                    <!-- Apellido -->
                    <div>
                        <x-input-label :value="__('Apellido')" />
                        <p class="text-slate-800 dark:text-slate-200 font-semibold text-lg mt-1">{{ $usuario->apellido }}</p>
                    </div>

                    <!-- Cédula -->
                    <div>
                        <x-input-label :value="__('Cédula')" />
                        <p class="text-slate-800 dark:text-slate-200 font-semibold text-lg mt-1">{{ $usuario->cedula ?? 'N/A' }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label :value="__('Correo Electrónico')" />
                        <p class="text-indigo-600 dark:text-indigo-400 font-semibold mt-1">
                            {{ $usuario->email }}
                        </p>
                    </div>

                    <!-- Estado -->
                    <div>
                        <x-input-label :value="__('Estado')" />
                        <div class="mt-1">
                            @if($usuario->status === 1)
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-md bg-emerald-600 text-white">Activo</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-md bg-rose-600 text-white">Inactivo</span>
                            @endif
                        </div>
                    </div>

                    <!-- Roles Asignados -->
                    <div class="md:col-span-3 mt-4">
                        <x-input-label :value="__('Roles Asignados')" />
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse($usuario->roles as $rol)
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 font-semibold">
                                    {{ $rol->nombre_rol }}
                                </span>
                            @empty
                                <p class="text-sm text-slate-500 italic">No tiene roles asignados.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción inferior -->
                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('usuarios.index') }}"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
