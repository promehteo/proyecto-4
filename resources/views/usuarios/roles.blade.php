<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Asignar Roles al Usuario: ') . $usuario->name }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        rolesSeleccionados: @js($rolesAsignadosIds)
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold">Gestión de Roles Asignados</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Seleccione los roles que tendrá asignados este usuario. Los roles desmarcados cambiarán a estado inactivo en la relación del usuario.
                    </p>
                </div>

                <form method="POST" action="{{ route('usuarios.roles.update', $usuario) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4 mb-8">
                        @foreach($rolesActivos as $r)
                            <label class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 cursor-pointer hover:bg-indigo-50/50 dark:hover:bg-slate-800/60 transition-colors">
                                <x-checkbox name="roles[]"
                                            value="{{ $r->id_rol }}"
                                            x-model.number="rolesSeleccionados" />
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $r->nombre_rol }}</span>
                                        <span class="text-xs font-mono text-indigo-600 dark:text-indigo-400 font-medium px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/50 rounded-md border border-indigo-200 dark:border-indigo-800">{{ $r->slug_rol }}</span>
                                    </div>
                                    @if($r->descripcion_rol)
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $r->descripcion_rol }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Botones de Acción -->
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('usuarios.index') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver
                        </a>
                        <x-primary-button class="w-full sm:w-auto justify-center">Guardar Roles</x-primary-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
