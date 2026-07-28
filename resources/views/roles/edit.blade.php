<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Editar Rol: ') . $rol->nombre_rol }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 sm:rounded-lg">
                <div class="w-full">
                    <form
                        method="POST"
                        action="{{ route('roles.update', $rol) }}"
                        novalidate
                        x-data="{
                            nombre: @js(old('nombre_rol', $rol->nombre_rol)),
                            slug: @js(old('slug_rol', $rol->slug_rol)),
                            slugTouched: true,
                            desc: @js(old('descripcion_rol', $rol->descripcion_rol ?? '')),
                            status: @js((string) old('status', $rol->status)),
                            maxDesc: 255,
                            slugify(v) {
                                return (v || '').toString().toLowerCase()
                                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                                    .replace(/[^a-z0-9]+/g, '_')
                                    .replace(/^_+|_+$/g, '')
                                    .slice(0, 80);
                            },
                            regen() { this.slug = this.slugify(this.nombre); this.slugTouched = false; }
                        }"
                        x-effect="if (!slugTouched) { slug = slugify(nombre) }"
                    >
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Nombre del rol --}}
                            <div>
                                <x-input-label for="nombre_rol" :value="__('Nombre del Rol')" />
                                <x-text-input
                                    id="nombre_rol"
                                    name="nombre_rol"
                                    type="text"
                                    x-model="nombre"
                                    class="mt-1 block w-full transition-colors duration-150 motion-reduce:transition-none"
                                    required
                                    autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('nombre_rol')" />
                            </div>

                            {{-- Slug: en edición NO se autogenera; solo con “Regenerar” --}}
                            <div>
                                <x-input-label for="slug_rol" :value="__('Slug del Rol')" />
                                <x-text-input
                                    id="slug_rol"
                                    name="slug_rol"
                                    type="text"
                                    x-model="slug"
                                    @input="slugTouched = true"
                                    class="mt-1 block w-full font-mono text-sm transition-colors duration-150 motion-reduce:transition-none"
                                    required
                                    placeholder="ej: auditor_inventario" />
                                <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">
                                    No cambia solo al editar.
                                    <button type="button" @click="regen()"
                                        class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded">
                                        Regenerar desde el nombre
                                    </button>
                                </p>
                                <x-input-error class="mt-1" :messages="$errors->get('slug_rol')" />
                            </div>

                            {{-- Estado con indicador de color reactivo --}}
                            <div>
                                <x-input-label for="status" :value="__('Estado')" />
                                <div class="relative mt-1">
                                    <span aria-hidden="true"
                                        :class="status == '1' ? 'bg-emerald-500' : 'bg-gray-400 dark:bg-gray-500'"
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-2 w-2 rounded-full transition-colors duration-150 motion-reduce:transition-none"></span>
                                    <select
                                        id="status"
                                        name="status"
                                        x-model="status"
                                        required
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 pl-8 pr-9 py-2 text-sm shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 transition-colors duration-150 motion-reduce:transition-none">
                                        <option value="1">Activo</option>
                                        <option value="2">Inactivo</option>
                                    </select>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            {{-- Descripción a ancho completo con contador --}}
                            <div class="md:col-span-3">
                                <div class="flex items-baseline justify-between gap-3">
                                    <x-input-label for="descripcion_rol" :value="__('Descripción')" />
                                    <span
                                        x-text="desc.length + ' / ' + maxDesc"
                                        :class="desc.length >= maxDesc ? 'text-rose-500' : (desc.length >= maxDesc - 50 ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500')"
                                        aria-live="polite"
                                        class="text-xs tabular-nums transition-colors duration-150 motion-reduce:transition-none"></span>
                                </div>
                                <textarea
                                    id="descripcion_rol"
                                    name="descripcion_rol"
                                    rows="3"
                                    maxlength="255"
                                    x-model="desc"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm transition-colors duration-150 motion-reduce:transition-none">{{ '' }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('descripcion_rol')" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('roles.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <x-primary-button class="ms-3">Actualizar Rol</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>