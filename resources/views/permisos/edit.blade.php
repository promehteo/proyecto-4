<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            {{ __('Editar Permiso') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="permisoForm({{ $permiso->id_permiso }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold">Editar Información</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Modifique los datos del permiso seleccionado.</p>
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
                    <form method="POST" action="{{ route('permisos.update', $permiso) }}" novalidate @submit.prevent="submitForm($event)">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                            {{-- Nombre --}}
                            <div>
                                <x-input-label for="nombre_permiso" :value="__('Nombre del Permiso')" />
                                <x-text-input
                                    id="nombre_permiso"
                                    name="nombre_permiso"
                                    type="text"
                                    x-model="form.nombre_permiso"
                                    @input="markDirty('nombre_permiso')"
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

                            {{-- Slug --}}
                            <div>
                                <x-input-label for="slug_permiso" :value="__('Slug del Permiso')" />
                                <x-text-input
                                    id="slug_permiso"
                                    name="slug_permiso"
                                    type="text"
                                    x-model="form.slug_permiso"
                                    @input="markDirty('slug_permiso')"
                                    @blur="markDirty('slug_permiso')"
                                    class="mt-1 block w-full font-mono text-sm"
                                    required />
                                <template x-if="errors['slug_permiso']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['slug_permiso'][0]"></p>
                                </template>
                                @if($errors->has('slug_permiso'))
                                    <template x-if="!dirtyFields.has('slug_permiso')">
                                        <x-input-error class="mt-2" :messages="$errors->get('slug_permiso')" />
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
                                    required />
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

                        {{-- Descripción --}}
                        <div class="mb-6">
                            <div class="flex items-baseline justify-between gap-3">
                                <x-input-label for="descripcion_permiso" :value="__('Descripción')" />
                                <span
                                    x-text="(form.descripcion_permiso || '').length + ' / 255'"
                                    :class="(form.descripcion_permiso || '').length >= 255 ? 'text-rose-500' : 'text-slate-400 dark:text-slate-500'"
                                    class="text-xs tabular-nums"></span>
                            </div>
                            <x-textarea
                                id="descripcion_permiso"
                                name="descripcion_permiso"
                                rows="3"
                                maxlength="255"
                                x-model="form.descripcion_permiso"
                                @input="markDirty('descripcion_permiso')"
                                @blur="markDirty('descripcion_permiso')"
                                class="mt-1 block w-full">{{ old('descripcion_permiso', $permiso->descripcion_permiso) }}</x-textarea>
                            <template x-if="errors['descripcion_permiso']">
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['descripcion_permiso'][0]"></p>
                            </template>
                            @if($errors->has('descripcion_permiso'))
                                <template x-if="!dirtyFields.has('descripcion_permiso')">
                                    <x-input-error class="mt-2" :messages="$errors->get('descripcion_permiso')" />
                                </template>
                            @endif
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
                            <x-primary-button class="w-full sm:w-auto justify-center" ::disabled="isValidating">Editar</x-primary-button>
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
                    nombre_permiso: @js(old('nombre_permiso', $permiso->nombre_permiso)),
                    slug_permiso: @js(old('slug_permiso', $permiso->slug_permiso)),
                    modulo_permiso: @js(old('modulo_permiso', $permiso->modulo_permiso)),
                    descripcion_permiso: @js(old('descripcion_permiso', $permiso->descripcion_permiso ?? '')),
                    status: @js(old('status', (string)$permiso->status))
                },
                errors: {},
                dirtyFields: new Set(),
                isValidating: false,
                debounceTimeout: null,

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
                    this.dirtyFields.add('slug_permiso');
                    this.dirtyFields.add('modulo_permiso');
                    this.dirtyFields.add('status');
                    this.dirtyFields.add('descripcion_permiso');
                    
                    await this.validateForm();
                    
                    if (Object.keys(this.errors).length === 0) {
                        e.target.submit();
                    }
                }
            };
        }
    </script>
</x-app-layout>