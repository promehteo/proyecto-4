<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Categoría') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="categoriaForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold">Información General</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Registre una nueva categoría para el catálogo.</p>
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
                    <form method="POST" action="{{ route('categorias.store') }}" novalidate @submit.prevent="submitForm($event)">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="nombre_categoria" :value="__('Nombre de la Categoría')" />
                                <x-text-input 
                                    id="nombre_categoria" 
                                    name="nombre_categoria" 
                                    type="text"
                                    class="mt-1 block w-full" 
                                    x-model="form.nombre_categoria" 
                                    @input="markDirty('nombre_categoria')"
                                    @blur="markDirty('nombre_categoria')"
                                    required 
                                    autofocus 
                                />
                                <template x-if="errors['nombre_categoria']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['nombre_categoria'][0]"></p>
                                </template>
                                @if($errors->has('nombre_categoria'))
                                    <template x-if="!dirtyFields.has('nombre_categoria')">
                                        <x-input-error class="mt-2" :messages="$errors->get('nombre_categoria')" />
                                    </template>
                                @endif
                            </div>

                            <div>
                                <x-input-label for="id_categoria_padre_categoria" :value="__('Categoría Padre (Opcional)')" />
                                <x-select 
                                    id="id_categoria_padre_categoria" 
                                    name="id_categoria_padre_categoria" 
                                    class="mt-1 block w-full"
                                    x-model="form.id_categoria_padre_categoria"
                                    @change="markDirty('id_categoria_padre_categoria')"
                                >
                                    <option value="">— Ninguna (Categoría Principal) —</option>
                                    @foreach($categoriasPadre as $padre)
                                        <option value="{{ $padre->id_categoria }}" {{ old('id_categoria_padre_categoria') == $padre->id_categoria ? 'selected' : '' }}>
                                            {{ $padre->nombre_categoria }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <template x-if="errors['id_categoria_padre_categoria']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['id_categoria_padre_categoria'][0]"></p>
                                </template>
                                @if($errors->has('id_categoria_padre_categoria'))
                                    <template x-if="!dirtyFields.has('id_categoria_padre_categoria')">
                                        <x-input-error class="mt-2" :messages="$errors->get('id_categoria_padre_categoria')" />
                                    </template>
                                @endif
                            </div>

                            <div>
                                <x-input-label for="descripcion_categoria" :value="__('Descripción')" />
                                <x-textarea 
                                    id="descripcion_categoria" 
                                    name="descripcion_categoria" 
                                    rows="3"
                                    class="mt-1 block w-full" 
                                    maxlength="255"
                                    x-model="form.descripcion_categoria"
                                    @input="markDirty('descripcion_categoria')"
                                    @blur="markDirty('descripcion_categoria')"
                                >{{ old('descripcion_categoria') }}</x-textarea>
                                <template x-if="errors['descripcion_categoria']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['descripcion_categoria'][0]"></p>
                                </template>
                                @if($errors->has('descripcion_categoria'))
                                    <template x-if="!dirtyFields.has('descripcion_categoria')">
                                        <x-input-error class="mt-2" :messages="$errors->get('descripcion_categoria')" />
                                    </template>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <a href="{{ route('categorias.index') }}"
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
        function categoriaForm(categoriaId = null) {
            return {
                form: {
                    nombre_categoria: @js(old('nombre_categoria', '')),
                    id_categoria_padre_categoria: @js(old('id_categoria_padre_categoria', '')),
                    descripcion_categoria: @js(old('descripcion_categoria', ''))
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
                        const response = await fetch("{{ route('api.system.validate-partial', ['formRequest' => 'Categoria/CategoriaGesRequest']) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                ...this.form,
                                _model_id: categoriaId,
                                _dirty: Array.from(this.dirtyFields)
                            })
                        });

                        const result = await response.json();
                        
                        // Limpiar errores viejos de los campos que ya validamos
                        this.dirtyFields.forEach(field => {
                            delete this.errors[field];
                        });

                        // Mezclar los nuevos errores retornados
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
                    // Forzar validación final de todos los campos marcándolos como dirty
                    this.dirtyFields.add('nombre_categoria');
                    this.dirtyFields.add('id_categoria_padre_categoria');
                    this.dirtyFields.add('descripcion_categoria');
                    
                    await this.validateForm();
                    
                    if (Object.keys(this.errors).length === 0) {
                        e.target.submit();
                    }
                }
            };
        }
    </script>
</x-app-layout>