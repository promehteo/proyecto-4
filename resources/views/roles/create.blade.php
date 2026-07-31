<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            {{ __('Registrar Rol') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="rolForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold">Información General</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Registre un nuevo rol en el sistema.</p>
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
                    <form method="POST" action="{{ route('roles.store') }}" novalidate @submit.prevent="submitForm($event)">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Nombre --}}
                            <div>
                                <x-input-label for="nombre_rol" :value="__('Nombre del Rol')" />
                                <x-text-input
                                    id="nombre_rol"
                                    name="nombre_rol"
                                    type="text"
                                    x-model="form.nombre_rol"
                                    @input="markDirty('nombre_rol'); if(!slugTouched) { form.clave_rol = slugify(form.nombre_rol); markDirty('clave_rol'); }"
                                    @blur="markDirty('nombre_rol')"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus />
                                <template x-if="errors['nombre_rol']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['nombre_rol'][0]"></p>
                                </template>
                                @if($errors->has('nombre_rol'))
                                    <template x-if="!dirtyFields.has('nombre_rol')">
                                        <x-input-error class="mt-2" :messages="$errors->get('nombre_rol')" />
                                    </template>
                                @endif
                            </div>

                            {{-- Clave --}}
                            <div>
                                <x-input-label for="clave_rol" :value="__('Clave del Rol')" />
                                <x-text-input
                                    id="clave_rol"
                                    name="clave_rol"
                                    type="text"
                                    x-model="form.clave_rol"
                                    @input="slugTouched = true; markDirty('clave_rol')"
                                    @blur="markDirty('clave_rol')"
                                    class="mt-1 block w-full font-mono text-sm"
                                    required
                                    placeholder="ej: auditor_inventario" />
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    Se genera desde el nombre.
                                    <button type="button" @click="regen()"
                                        class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline focus:outline-none">
                                        Regenerar
                                    </button>
                                </p>
                                <template x-if="errors['clave_rol']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['clave_rol'][0]"></p>
                                </template>
                                @if($errors->has('clave_rol'))
                                    <template x-if="!dirtyFields.has('clave_rol')">
                                        <x-input-error class="mt-2" :messages="$errors->get('clave_rol')" />
                                    </template>
                                @endif
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <a href="{{ route('roles.index') }}"
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
        function rolForm(rolId = null) {
            return {
                form: {
                    nombre_rol: @js(old('nombre_rol', '')),
                    clave_rol: @js(old('clave_rol', ''))
                },
                slugTouched: @js(old('clave_rol') !== null && old('clave_rol') !== ''),
                errors: {},
                dirtyFields: new Set(),
                isValidating: false,
                debounceTimeout: null,

                slugify(v) {
                    return (v || '').toString().toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9]+/g, '_')
                        .replace(/^_+|_+$/g, '')
                        .slice(0, 80);
                },

                regen() {
                    this.form.clave_rol = this.slugify(this.form.nombre_rol);
                    this.slugTouched = false;
                    this.markDirty('clave_rol');
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
                        const response = await fetch("{{ route('api.system.validate-partial', ['formRequest' => 'Rol/RolFormRequest']) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                ...this.form,
                                _model_id: rolId,
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
                    this.dirtyFields.add('nombre_rol');
                    this.dirtyFields.add('clave_rol');
                    
                    await this.validateForm();
                    
                    if (Object.keys(this.errors).length === 0) {
                        e.target.submit();
                    }
                }
            };
        }
    </script>
</x-app-layout>