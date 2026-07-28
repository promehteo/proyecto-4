<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Asignar Permisos al Rol: ') . $rol->nombre_rol }}
        </h2>
    </x-slot>

    @php
        $todosLosIds = $permisosAgrupados->flatten()->pluck('id_permiso')->values();
    @endphp

    <div class="py-12" x-data="{
        permisosSeleccionados: @js($permisosAsignadosIds),
        todosLosIds: @js($todosLosIds),
        
        get todosMarcados() {
            return this.todosLosIds.length > 0 && this.todosLosIds.every(id => this.permisosSeleccionados.includes(id));
        },

        toggleTodos(checked) {
            if (checked) {
                this.permisosSeleccionados = [...new Set([...this.permisosSeleccionados, ...this.todosLosIds])];
            } else {
                this.permisosSeleccionados = [];
            }
        },

        moduloMarcado(ids) {
            return ids.length > 0 && ids.every(id => this.permisosSeleccionados.includes(id));
        },

        toggleModulo(ids, checked) {
            if (checked) {
                this.permisosSeleccionados = [...new Set([...this.permisosSeleccionados, ...ids])];
            } else {
                this.permisosSeleccionados = this.permisosSeleccionados.filter(id => !ids.includes(id));
            }
        }
    }">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold">Gestión de Permisos Asignados</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Marque o desmarque los permisos correspondientes organizados por módulo.
                    </p>
                </div>

                <form method="POST" action="{{ route('roles.permisos.update', $rol) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6 mb-8">
                        @foreach($permisosAgrupados as $modulo => $permisosGroup)
                            @php 
                                $moduloSlug = Str::slug($modulo); 
                                $moduloIds = $permisosGroup->pluck('id_permiso')->values();
                            @endphp
                            <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-5 bg-slate-50 dark:bg-slate-950">
                                <div class="flex justify-between items-center mb-4 border-b border-slate-200 dark:border-slate-800 pb-3">
                                    <label class="flex items-center gap-3 cursor-pointer select-none">
                                        <x-checkbox :checked="moduloMarcado(@js($moduloIds))"
                                                    @change="toggleModulo(@js($moduloIds), $event.target.checked)" />
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">
                                            Módulo: {{ $modulo }}
                                        </h4>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($permisosGroup as $p)
                                        <label class="flex items-start gap-2.5 bg-white dark:bg-slate-900 p-3 rounded-lg border border-slate-200 dark:border-slate-800 cursor-pointer hover:bg-indigo-50/50 dark:hover:bg-slate-800/60 transition-colors">
                                            <x-checkbox name="permisos[]"
                                                        value="{{ $p->id_permiso }}"
                                                        x-model.number="permisosSeleccionados"
                                                        class="modulo-{{ $moduloSlug }}" />
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $p->nombre_permiso }}</div>
                                                @if($p->descripcion_permiso)
                                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $p->descripcion_permiso }}</div>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Botones de Acción -->
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('roles.index') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver
                        </a>
                        <x-primary-button class="w-full sm:w-auto justify-center">Guardar Permisos</x-primary-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
