<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle de Categoría') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <!-- Cabecera de la tarjeta -->
                <div class="border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">Información General</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Detalles de registro de la categoría seleccionada.</p>
                </div>

                <!-- Contenido de detalles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div>
                        <x-input-label :value="__('Nombre de la Categoría')" />
                        <p class="text-slate-800 dark:text-slate-200 font-semibold text-lg mt-1">{{ $categoria->nombre_categoria }}</p>
                    </div>

                    <!-- Categoría Padre -->
                    <div>
                        <x-input-label :value="__('Categoría Padre')" />
                        <p class="text-slate-855 dark:text-slate-345 mt-1">
                            {{ $categoria->padre ? $categoria->padre->nombre_categoria : '— Ninguna (Categoría Principal) —' }}
                        </p>
                    </div>

                    <!-- Estado -->
                    <div>
                        <x-input-label :value="__('Estado')" />
                        <div class="mt-1">
                            @if($categoria->status === 1)
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-md bg-emerald-600 text-white">Activo</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-md bg-rose-600 text-white">Inactivo</span>
                            @endif
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="md:col-span-2">
                        <x-input-label :value="__('Descripción')" />
                        <p class="text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-950 p-4 rounded-md border border-slate-250 dark:border-slate-800 mt-1">
                            {{ $categoria->descripcion_categoria ?? 'Sin descripción disponible.' }}
                        </p>
                    </div>

                </div>

                <!-- Botones de Acción inferior -->
                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('categorias.index') }}"
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
