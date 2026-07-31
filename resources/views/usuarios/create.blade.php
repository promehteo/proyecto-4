<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            {{ __('Registrar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="userForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-card>
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 font-semibold">Información General</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Registrar un nuevo usuario para acceder al sistema.</p>
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
                    <form method="POST" action="{{ route('usuarios.store') }}" novalidate @submit.prevent="submitForm($event)">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Nombre --}}
                            <div>
                                <x-input-label for="nombre" :value="__('Nombre')" />
                                <x-text-input
                                    id="nombre"
                                    name="nombre"
                                    type="text"
                                    x-model="form.nombre"
                                    @input="markDirty('nombre')"
                                    @blur="markDirty('nombre')"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus />
                                <template x-if="errors['nombre']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['nombre'][0]"></p>
                                </template>
                                @if($errors->has('nombre'))
                                    <template x-if="!dirtyFields.has('nombre')">
                                        <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
                                    </template>
                                @endif
                            </div>

                            {{-- Apellido --}}
                            <div>
                                <x-input-label for="apellido" :value="__('Apellido')" />
                                <x-text-input
                                    id="apellido"
                                    name="apellido"
                                    type="text"
                                    x-model="form.apellido"
                                    @input="markDirty('apellido')"
                                    @blur="markDirty('apellido')"
                                    class="mt-1 block w-full"
                                    required />
                                <template x-if="errors['apellido']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['apellido'][0]"></p>
                                </template>
                                @if($errors->has('apellido'))
                                    <template x-if="!dirtyFields.has('apellido')">
                                        <x-input-error class="mt-2" :messages="$errors->get('apellido')" />
                                    </template>
                                @endif
                            </div>

                            {{-- Cédula --}}
                            <div>
                                <x-input-label for="cedula" :value="__('Cédula')" />
                                <x-text-input
                                    id="cedula"
                                    name="cedula"
                                    type="number"
                                    x-model="form.cedula"
                                    @input="markDirty('cedula')"
                                    @blur="markDirty('cedula')"
                                    class="mt-1 block w-full" />
                                <template x-if="errors['cedula']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['cedula'][0]"></p>
                                </template>
                                @if($errors->has('cedula'))
                                    <template x-if="!dirtyFields.has('cedula')">
                                        <x-input-error class="mt-2" :messages="$errors->get('cedula')" />
                                    </template>
                                @endif
                            </div>

                            {{-- Correo Electrónico --}}
                            <div>
                                <x-input-label for="email" :value="__('Correo Electrónico')" />
                                <x-text-input
                                    id="email"
                                    name="email"
                                    type="email"
                                    x-model="form.email"
                                    @input="markDirty('email')"
                                    @blur="markDirty('email')"
                                    class="mt-1 block w-full"
                                    required />
                                <template x-if="errors['email']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['email'][0]"></p>
                                </template>
                                @if($errors->has('email'))
                                    <template x-if="!dirtyFields.has('email')">
                                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                    </template>
                                @endif
                            </div>

                            {{-- Contraseña --}}
                            <div>
                                <x-input-label for="password" :value="__('Contraseña')" />
                                <x-text-input
                                    id="password"
                                    name="password"
                                    type="password"
                                    x-model="form.password"
                                    @input="markDirty('password'); if(form.password_confirmation) markDirty('password_confirmation')"
                                    @blur="markDirty('password')"
                                    class="mt-1 block w-full"
                                    required />
                                <template x-if="errors['password']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['password'][0]"></p>
                                </template>
                                @if($errors->has('password'))
                                    <template x-if="!dirtyFields.has('password')">
                                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                    </template>
                                @endif
                            </div>

                            {{-- Confirmar Contraseña --}}
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                                <x-text-input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    x-model="form.password_confirmation"
                                    @input="markDirty('password_confirmation'); markDirty('password')"
                                    @blur="markDirty('password_confirmation')"
                                    class="mt-1 block w-full"
                                    required />
                                <template x-if="errors['password_confirmation']">
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2" x-text="errors['password_confirmation'][0]"></p>
                                </template>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <a href="{{ route('usuarios.index') }}"
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
        function userForm(userId = null) {
            return {
                form: {
                    nombre: @js(old('nombre', '')),
                    apellido: @js(old('apellido', '')),
                    cedula: @js(old('cedula', '')),
                    email: @js(old('email', '')),
                    password: '',
                    password_confirmation: ''
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
                        const response = await fetch("{{ route('api.system.validate-partial', ['formRequest' => 'User/UserGesRequest']) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                ...this.form,
                                _model_id: userId,
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
                    this.dirtyFields.add('nombre');
                    this.dirtyFields.add('apellido');
                    this.dirtyFields.add('cedula');
                    this.dirtyFields.add('email');
                    this.dirtyFields.add('password');
                    this.dirtyFields.add('password_confirmation');
                    
                    await this.validateForm();
                    
                    if (Object.keys(this.errors).length === 0) {
                        e.target.submit();
                    }
                }
            };
        }
    </script>
</x-app-layout>