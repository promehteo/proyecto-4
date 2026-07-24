@props(['active' => null])

@php
    $sidebarItems = [
        ['route' => 'dashboard', 'label' => 'Panel', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['route' => 'categorias.index', 'label' => 'Categorías', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'can' => App\Models\Categoria::class],
        ['route' => 'bitacora.index', 'label' => 'Bitácora', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'can' => App\Models\Bitacora::class],
        ['route' => 'roles.index', 'label' => 'Roles', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'can' => App\Models\Rol::class],
        ['route' => 'permisos.index', 'label' => 'Permisos', 'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z', 'can' => App\Models\Permiso::class],
        ['route' => 'usuarios.index', 'label' => 'Usuarios', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'can' => App\Models\User::class],
    ];
@endphp

<aside 
    x-data="{ 
        collapsed: localStorage.getItem('sidebar-collapsed') === 'true',
        mobileOpen: false
    }"
    x-init="
        $watch('collapsed', value => {
            localStorage.setItem('sidebar-collapsed', value);
            window.dispatchEvent(new CustomEvent('sidebar-collapse', { detail: value }));
        });
        $watch('mobileOpen', value => {
            localStorage.setItem('sidebar-mobile', value);
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        // Restaurar estado móvil si existe
        const savedMobile = localStorage.getItem('sidebar-mobile');
        if (savedMobile === 'true') {
            mobileOpen = true;
        }
    "
    @keydown.escape.window="mobileOpen = false"
    class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 ease-in-out
           lg:z-40"
    :class="collapsed ? 'w-20' : 'w-64'"
    aria-label="Navegación principal"
>
    <!-- Overlay para móvil -->
    <div 
        x-show="mobileOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen = false"
        class="fixed inset-0 bg-slate-900/50 lg:hidden"
        role="dialog"
        aria-modal="true"
        x-cloak
    ></div>

    <!-- Sidebar container -->
    <div 
        class="relative flex flex-col h-full bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800
               transition-all duration-300 ease-in-out"
        :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        <!-- Cabecera con logo y toggle -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-slate-200 dark:border-slate-800">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <!-- Isotipo / Logo pequeño -->
                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <span class="text-white font-bold text-sm">R</span>
                </div>
                <!-- Texto del logo (visible solo expandido) -->
                <span 
                    class="font-bold text-lg text-indigo-600 dark:text-indigo-400 whitespace-nowrap transition-opacity duration-200"
                    :class="collapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'"
                >
                    La Casa El Rapidito
                </span>
            </a>
            
            <!-- Botón toggle (visible solo en escritorio) -->
            <button
                @click="collapsed = !collapsed"
                class="hidden lg:flex items-center justify-center w-8 h-8 rounded-md text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0 transition-colors"
                :aria-expanded="!collapsed"
                aria-controls="sidebar-nav"
                :title="collapsed ? 'Expandir menú' : 'Colapsar menú'"
            >
                <!-- Icono hamburguesa (cuando está colapsado) -->
                <svg x-show="collapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <!-- Icono flecha (cuando está expandido) -->
                <svg x-show="!collapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
            
            <!-- Botón cerrar móvil -->
            <button
                @click="mobileOpen = false"
                class="lg:hidden inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0"
                aria-label="Cerrar menú"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navegación -->
        <nav id="sidebar-nav" class="flex-1 overflow-y-auto py-4 px-3" aria-label="Principal">
            <ul class="space-y-1">
                @foreach($sidebarItems as $item)
                    @if(!isset($item['can']) || @can('viewAny', $item['can']))
                        @php
                            $isActive = request()->routeIs($item['route'] . '*') || (isset($active) && $active === $item['route']);
                        @endphp
                        <li>
                            <a
                                href="{{ route($item['route']) }}"
                                class="group relative flex items-center gap-3 px-3 py-2.5 rounded-md transition-all duration-200
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0
                                       {{ $isActive 
                                           ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border-l-4 border-indigo-600 dark:border-indigo-500 -ml-4 pl-3 pr-2' 
                                           : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100'
                                       }}"
                                aria-current="{{ $isActive ? 'page' : null }}"
                                :title="collapsed ? '{{ $item['label'] }}' : null"
                            >
                                <!-- Icono -->
                                <svg class="w-5 h-5 flex-shrink-0 {{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300' }}" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                                </svg>
                                
                                <!-- Texto (oculto cuando colapsado) -->
                                <span 
                                    class="whitespace-nowrap transition-opacity duration-200 text-sm font-medium"
                                    :class="collapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'"
                                >
                                    {{ __($item['label']) }}
                                </span>
                                
                                <!-- Tooltip para móvil/colapsado (solo visible en hover cuando colapsado) -->
                                <span 
                                    x-show="collapsed"
                                    class="absolute left-full ml-2 px-2 py-1 text-xs font-medium text-white bg-slate-900 dark:bg-slate-700 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
                                    x-cloak
                                >
                                    {{ __($item['label']) }}
                                </span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>

        <!-- Pie: Usuario autenticado -->
        <div class="border-t border-slate-200 dark:border-slate-800 p-4">
            <div class="relative" x-data="{ userMenuOpen: false }">
                <button
                    @click="userMenuOpen = !userMenuOpen"
                    @click.outside="userMenuOpen = false"
                    class="w-full flex items-center gap-3 px-2 py-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0 transition-colors"
                    :title="collapsed ? Auth::user()->name : null"
                >
                    <!-- Avatar / Inicial -->
                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>
                    
                    <!-- Info usuario (visible solo expandido) -->
                    <div 
                        class="flex-1 text-left min-w-0 transition-opacity duration-200"
                        :class="collapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'"
                    >
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                    
                    <!-- Flecha dropdown (visible solo expandido) -->
                    <svg 
                        class="w-4 h-4 text-slate-400 transition-opacity duration-200"
                        :class="collapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- Dropdown menú usuario -->
                <div
                    x-show="userMenuOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute bottom-full left-0 mb-2 w-56 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden z-50"
                    x-cloak
                >
                    <div class="py-1">
                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-inset"
                        >
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Profile') }}
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-inset"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Estilos para x-cloak -->
<style>
[x-cloak] { display: none !important; }
</style>
