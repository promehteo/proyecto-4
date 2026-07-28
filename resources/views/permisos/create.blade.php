<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Permiso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <form method="POST" action="{{ route('permisos.store') }}" novalidate
                        x-data="{
                            nombre: @js(old('nombre_permiso', '')),
                            slug: @js(old('slug_permiso', '')),
                            slugTouched: @js(old('slug_permiso') !== null && old('slug_permiso') !== ''),
                            desc: @js(old('descripcion_permiso', '')),
                            status: @js(old('status', '1')),
                            maxDesc: 255,
                            slugify(v) {
                                return (v || '').toString().toLowerCase()
                                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                                    .replace(/[^a-z0-9]+/g, '.')
                                    .replace(/^\.+|\.+$/g, '')
                                    .slice(0, 80);
                            },
                            regen() { this.slug = this.slugify(this.nombre); this.slugTouched = false; }
                        }"
                        x-effect="if (!slugTouched) { slug = slugify(nombre) }"
                    >
                        @csrf

                        <!-- Fila 1: Grid de 3 columnas para Nombre, Slug y Módulo -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                            
                            {{-- Nombre del Permiso --}}
                            <div>
                                <x-input-label for="nombre_permiso" :value="__('Nombre del Permiso')" />
                                <x-text-input id="nombre_permiso" name="nombre_permiso" type="text"
                                    x-model="nombre"
                                    class="mt-1 block w-full" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('nombre_permiso')" />
                            </div>

                            {{-- Slug del Permiso (Generado en vivo) --}}
                            <div>
                                <x-input-label for="slug_permiso" :value="__('Slug del Permiso')" />
                                <x-text-input id="slug_permiso" name="slug_permiso" type="text"
                                    x-model="slug"
                                    @input="slugTouched = true"
                                    class="mt-1 block w-full font-mono text-sm" required
                                    placeholder="ej: productos.crear" />
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    Se genera desde el nombre.
                                    <button type="button" @click="regen()"
                                        class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded">
                                        Regenerar
                                    </button>
                                </p>
                                <x-input-error class="mt-2" :messages="$errors->get('slug_permiso')" />
                            </div>

                            {{-- Módulo del Permiso --}}
                            <div>
                                <x-input-label for="modulo_permiso" :value="__('Módulo del Permiso')" />
                                <x-text-input id="modulo_permiso" name="modulo_permiso" type="text"
                                    class="mt-1 block w-full" :value="old('modulo_permiso')" required list="list-modulos"
                                    placeholder="ej: inventario" />
                                <datalist id="list-modulos">
                                    @foreach($modulosExistentes as $mod)
                                        <option value="{{ $mod }}">
                                    @endforeach
                                </datalist>
                                <x-input-error class="mt-2" :messages="$errors->get('modulo_permiso')" />
                            </div>
                        </div>

                        <!-- Fila 2: Ancho completo para Descripción -->
                        <div class="mb-4">
                            <div class="flex items-baseline justify-between gap-3">
                                <x-input-label for="descripcion_permiso" :value="__('Descripción')" />
                                <span
                                    x-text="desc.length + ' / ' + maxDesc"
                                    :class="desc.length >= maxDesc ? 'text-rose-500' : (desc.length >= maxDesc - 50 ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500')"
                                    aria-live="polite"
                                    class="text-xs tabular-nums transition-colors duration-150 motion-reduce:transition-none"></span>
                            </div>
                            <textarea id="descripcion_permiso" name="descripcion_permiso" rows="3"
                                maxlength="255" x-model="desc"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descripcion_permiso', '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descripcion_permiso')" />
                        </div>

                        <!-- Fila 3: Estado (Indicador de color reactivo) -->
                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Estado')" />
                            <div class="relative mt-1">
                                <span aria-hidden="true"
                                    :class="status == '1' ? 'bg-emerald-500' : 'bg-gray-400 dark:bg-gray-500'"
                                    class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-2 w-2 rounded-full transition-colors duration-150 motion-reduce:transition-none z-10"></span>
                                <select id="status" name="status" x-model="status" required
                                    class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm pl-8">
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('permisos.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Cancelar</a>
                            <x-primary-button class="ms-3">Guardar Permiso</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>