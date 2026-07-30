@props([
    'name',
    'value' => null,
    'options' => [], // Array de arrays asociativos: [['value' => 1, 'label' => 'Opción 1'], ...]
    'placeholder' => 'Seleccione una opción...',
    'id' => null,
])

@php
    $id = $id ?? $name;
@endphp

<div x-data="{
    open: false,
    search: '',
    value: @js($value),
    options: @js($options),
    get filteredOptions() {
        if (!this.search) return this.options;
        return this.options.filter(option => 
            option.label.toLowerCase().includes(this.search.toLowerCase())
        );
    },
    get selectedLabel() {
        const option = this.options.find(opt => opt.value == this.value);
        return option ? option.label : '';
    },
    selectOption(val, label) {
        this.value = val;
        this.search = '';
        this.open = false;
        $nextTick(() => {
            $refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }
}" 
class="relative w-full"
@click.outside="open = false; search = ''">
    <!-- Input oculto para enviar el valor real del formulario -->
    <input type="hidden" name="{{ $name }}" x-model="value" x-ref="hiddenInput" id="{{ $id }}">

    <!-- Disparador del Selector / Entrada de texto simulada -->
    <div class="relative cursor-pointer" @click="open = !open">
        <input 
            type="text" 
            readonly
            :value="selectedLabel"
            placeholder="{{ $placeholder }}"
            class="w-full rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 text-sm py-2 pl-3 pr-10 shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer"
        />
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    <!-- Menú desplegable -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-1 w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-md shadow-lg max-h-60 overflow-y-auto"
        style="display: none;"
    >
        <!-- Buscador interno -->
        <div class="sticky top-0 p-2 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 z-10">
            <input 
                type="text" 
                x-model="search"
                x-ref="searchField"
                @keyup.escape="open = false; search = ''"
                placeholder="Escribe para buscar..."
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-xs py-1.5 px-2.5 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                x-init="$watch('open', value => value && $nextTick(() => $refs.searchField.focus()))"
            />
        </div>

        <ul class="py-1">
            <template x-for="option in filteredOptions" :key="option.value">
                <li 
                    @click="selectOption(option.value, option.label)"
                    class="cursor-pointer select-none relative py-2.5 pl-3 pr-9 text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-850 text-sm"
                    :class="value == option.value ? 'bg-indigo-50 dark:bg-indigo-950/40 font-semibold' : ''"
                >
                    <span x-text="option.label" class="block truncate"></span>
                </li>
            </template>

            <!-- Mensaje cuando no hay resultados -->
            <div x-show="filteredOptions.length === 0" class="py-3 px-3 text-sm text-slate-400 dark:text-slate-500 text-center">
                No se encontraron resultados
            </div>
        </ul>
    </div>
</div>
