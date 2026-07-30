<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            {{ __('Registrar Permiso') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="permisoForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold">Información General</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Registrar un nuevo permiso de acceso al sistema.</p>
                    </div>
                    <!-- Indicador de validación reactiva -->
                    <div class="flex items-center gap-2 text-sm text-indigo-500 font-medium" x-show="isValidating" style="display: none;">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Validando...
                    </div>
                </div>

                <div class="w-full">
                    <form method="POST" action="{{ route('permisos.store') }}" novalidate @submit.prevent="submitForm($event)">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                            {{-- Nombre del permiso --}}
                            <div>
                                <x-input-label for="nombre_permiso" :value="__('Nombre del Permiso')" />
                                <x-text-input
                                    id="nombre_permiso"
                                    name="nombre_permiso"
                                    type="text"
                                    x-model="form.nombre_permiso"
                                    @input="markDirty('nombre_permiso'); if(!slugTouched) { form.clave_permiso = slugify(form.nombre_permiso); markDirty('clave_permiso'); }"
                                    @blur="markDirty('nombre_permiso')"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus />
                                <template x-if="errors['nombre_permiso']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['nombre_permiso'][0]"></p>
                                </template>
                                @if($errors->has('nombre_permiso'))
                                    <template x-if="!dirtyFields.has('nombre_permiso')">
                                        <x-input-error class="mt-2" :messages="$errors->get('nombre_permiso')" />
                                    </template>
                                @endif
                            </div>

                            {{-- Clave --}}
                            <div>
                                <x-input-label for="clave_permiso" :value="__('Clave del Permiso')" />
                                <x-text-input
                                    id="clave_permiso"
                                    name="clave_permiso"
                                    type="text"
                                    x-model="form.clave_permiso"
                                    @input="slugTouched = true; markDirty('clave_permiso')"
                                    @blur="markDirty('clave_permiso')"
                                    class="mt-1 block w-full font-mono text-sm"
                                    required
                                    placeholder="ej: categorias.crear" />
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    Se genera desde el nombre.
                                    <button type="button" @click="regen()"
                                        class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline focus:outline-none">
                                        Regenerar
                                    </button>
                                </p>
                                <template x-if="errors['clave_permiso']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['clave_permiso'][0]"></p>
                                </template>
                                @if($errors->has('clave_permiso'))
                                    <template x-if="!dirtyFields.has('clave_permiso')">
                                        <x-input-error class="mt-2" :messages="$errors->get('clave_permiso')" />
                                    </template>
                                @endif
                            </div>

                            {{-- Módulo --}}
                            <div>
                                <x-input-label for="modulo_permiso" :value="__('Módulo')" />
                                <x-text-input
                                    id="modulo_permiso"
                                    name="modulo_permiso"
                                    type="text"
                                    x-model="form.modulo_permiso"
                                    @input="markDirty('modulo_permiso')"
                                    @blur="markDirty('modulo_permiso')"
                                    class="mt-1 block w-full"
                                    required
                                    placeholder="ej: Categorías" />
                                <template x-if="errors['modulo_permiso']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['modulo_permiso'][0]"></p>
                                </template>
                                @if($errors->has('modulo_permiso'))
                                    <template x-if="!dirtyFields.has('modulo_permiso')">
                                        <x-input-error class="mt-2" :messages="$errors->get('modulo_permiso')" />
                                    </template>
                                @endif
                            </div>

                            {{-- Estado --}}
                            <div>
                                <x-input-label for="status" :value="__('Estado')" />
                                <x-select
                                    id="status"
                                    name="status"
                                    x-model="form.status"
                                    @change="markDirty('status')"
                                    class="mt-1 block w-full">
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </x-select>
                                <template x-if="errors['status']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['status'][0]"></p>
                                </template>
                                @if($errors->has('status'))
                                    <template x-if="!dirtyFields.has('status')">
                                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                    </template>
                                @endif
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <a href="{{ route('permisos.index') }}"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </a>
                            <x-primary-button class="w-full sm:w-auto justify-center" ::disabled="isValidating">Registrar</x-primary-button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>

    <script>
        function permisoForm(permisoId = null) {
            return {
                form: {
                    nombre_permiso: @js(old('nombre_permiso', '')),
                    clave_permiso: @js(old('clave_permiso', '')),
                    modulo_permiso: @js(old('modulo_permiso', '')),
                    status: @js(old('status', '1'))
                },
                slugTouched: @js(old('clave_permiso') !== null && old('clave_permiso') !== ''),
                errors: {},
                dirtyFields: new Set(),
                isValidating: false,
                debounceTimeout: null,

                slugify(v) {
                    return (v || '').toString().toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9.]+/g, '.')
                        .replace(/^\.+|\.+$/g, '')
                        .slice(0, 100);
                },

                regen() {
                    this.form.clave_permiso = this.slugify(this.form.nombre_permiso);
                    this.slugTouched = false;
                    this.markDirty('clave_permiso');
                },

                markDirty(field) {
                    this.dirtyFields.add(field);
                    this.debounceValidate();
                },

                debounceValidate() {
                    clearTimeout(this.debounceTimeout);
                    this.debounceTimeout = setTimeout(() => {
                        this.validateForm();
                    }, 500);
                },

                async validateForm() {
                    if (this.dirtyFields.size === 0) return;

                    this.isValidating = true;

                    try {
                        const response = await fetch("{{ route('api.system.validate-partial', ['formRequest' => 'Permiso/PermisoGesRequest']) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                ...this.form,
                                _model_id: permisoId,
                                _dirty: Array.from(this.dirtyFields)
                            })
                        });

                        const result = await response.json();
                        
                        this.dirtyFields.forEach(field => {
                            delete this.errors[field];
                        });

                        if (result.errors) {
                            Object.assign(this.errors, result.errors);
                        }
                    } catch (error) {
                        console.error("Error en validación reactiva:", error);
                    } finally {
                        this.isValidating = false;
                    }
                },

                async submitForm(e) {
                    this.dirtyFields.add('nombre_permiso');
                    this.dirtyFields.add('clave_permiso');
                    this.dirtyFields.add('modulo_permiso');
                    this.dirtyFields.add('status');
                    
                    await this.validateForm();
                    
                    if (Object.keys(this.errors).length === 0) {
                        e.target.submit();
                    }
                }
            };
        }
    </script>
</x-app-layout>