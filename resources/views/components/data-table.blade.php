@props([
    'paginator' => null, // Instancia del paginador
    'headers' => [],     // Array de cabeceras ['NOMBRE', 'DESCRIPCIÓN', ...] o un Blade Slot
])

<div class="bg-slate-50 dark:bg-[#0f172a] rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden font-sans shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-700">
                    @if($headers instanceof \Illuminate\Support\HtmlString || $headers instanceof \Illuminate\View\ComponentSlot)
                        {{ $headers }}
                    @else
                        @foreach($headers as $header)
                            <th class="px-6 py-4 text-xs font-semibold text-slate-900 dark:text-slate-100 tracking-wider uppercase {{ $loop->last ? 'text-right' : '' }}">
                                {{ $header }}
                            </th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if($paginator && $paginator->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50 dark:bg-[#0f172a]">
            <!-- Select de registros por página -->
            <div class="flex items-center text-sm text-slate-900 dark:text-slate-100 font-medium">
                <select 
                    class="bg-slate-50 dark:bg-[#0f172a] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-sm rounded focus:ring-indigo-500 focus:border-indigo-500 py-1 pl-2 pr-6"
                    onchange="window.location.href=this.value">
                    @foreach([5, 10, 15, 20, 25] as $size)
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => $size]) }}" {{ request('per_page', $paginator->perPage()) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
                <span class="ml-2">registros por página</span>
            </div>
            
            <!-- Paginación de Laravel -->
            <div class="w-full sm:w-auto">
                {{ $paginator->appends(request()->query())->links() }}
            </div>
        </div>
    @elseif($paginator && $paginator->total() > 0)
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50 dark:bg-[#0f172a]">
            <div class="flex items-center text-sm text-slate-900 dark:text-slate-100 font-medium">
                <select 
                    class="bg-slate-50 dark:bg-[#0f172a] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-sm rounded focus:ring-indigo-500 focus:border-indigo-500 py-1 pl-2 pr-6"
                    onchange="window.location.href=this.value">
                    @foreach([5, 10, 15, 20, 25] as $size)
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => $size]) }}" {{ request('per_page', $paginator->perPage()) == $size ? 'selected' : '' }}>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
                <span class="ml-2">registros por página</span>
            </div>
            <div class="text-sm text-slate-900 dark:text-slate-100 font-medium">
                Mostrando todos los {{ $paginator->total() }} registros.
            </div>
        </div>
    @endif
</div>
