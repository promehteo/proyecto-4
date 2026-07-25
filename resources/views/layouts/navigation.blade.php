<!-- Sidebar component - Fixed position, off-canvas en móvil -->
<aside id="sidebar"
       x-data="{ 
           open: false,
           collapsed: localStorage.getItem('sidebar-collapsed') === 'true',
           toggle() {
               this.collapsed = !this.collapsed;
               localStorage.setItem('sidebar-collapsed', this.collapsed);
               $dispatch('sidebar-toggle', { collapsed: this.collapsed });
           }
       }"
       @sidebar-toggle.window="open = true"
       @keydown.escape.window="open = false"
       :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 lg:w-64 transition-all duration-200"
       :class="collapsed ? 'lg:w-20' : 'lg:w-64'"
       aria-label="Navegación principal">
    
    <!-- Logo section -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <a href="{{ route('dashboard') }}" 
           class="font-bold text-lg text-indigo-600 dark:text-indigo-400 truncate"
           :class="collapsed ? 'lg:hidden' : ''">
            La Casa El Rapidito
        </a>
        <a href="{{ route('dashboard') }}" 
           class="font-bold text-xl text-indigo-600 dark:text-indigo-400 hidden lg:block"
           :class="collapsed ? '' : 'hidden'">
            LR
        </a>
        <!-- Close button for mobile -->
        <button @click="open = false" 
                class="lg:hidden p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                aria-label="Cerrar menú">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto py-4 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800">
        <div class="px-3 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group"
               :class="request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-l-2 border-indigo-600' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
               aria-current="{{ request()->routeIs('dashboard') ? 'page' : null }}">
                <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span :class="collapsed ? 'lg:hidden' : ''">{{ __('Dashboard') }}</span>
            </a>

            @can('viewAny', App\Models\Categoria::class)
                <a href="{{ route('categorias.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group"
                   :class="request()->routeIs('categorias.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-l-2 border-indigo-600' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                   aria-current="{{ request()->routeIs('categorias.*') ? 'page' : null }}">
                    <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span :class="collapsed ? 'lg:hidden' : ''">{{ __('Categorías') }}</span>
                </a>
            @endcan

            @can('viewAny', App\Models\Bitacora::class)
                <a href="{{ route('bitacora.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group"
                   :class="request()->routeIs('bitacora.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-l-2 border-indigo-600' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                   aria-current="{{ request()->routeIs('bitacora.*') ? 'page' : null }}">
                    <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span :class="collapsed ? 'lg:hidden' : ''">{{ __('Bitácora') }}</span>
                </a>
            @endcan

            @can('viewAny', App\Models\Rol::class)
                <a href="{{ route('roles.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group"
                   :class="request()->routeIs('roles.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-l-2 border-indigo-600' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                   aria-current="{{ request()->routeIs('roles.*') ? 'page' : null }}">
                    <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span :class="collapsed ? 'lg:hidden' : ''">{{ __('Roles') }}</span>
                </a>
            @endcan

            @can('viewAny', App\Models\Permiso::class)
                <a href="{{ route('permisos.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group"
                   :class="request()->routeIs('permisos.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-l-2 border-indigo-600' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                   aria-current="{{ request()->routeIs('permisos.*') ? 'page' : null }}">
                    <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <span :class="collapsed ? 'lg:hidden' : ''">{{ __('Permisos') }}</span>
                </a>
            @endcan

            @can('viewAny', App\Models\User::class)
                <a href="{{ route('usuarios.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group"
                   :class="request()->routeIs('usuarios.*') ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-l-2 border-indigo-600' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'"
                   aria-current="{{ request()->routeIs('usuarios.*') ? 'page' : null }}">
                    <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span :class="collapsed ? 'lg:hidden' : ''">{{ __('Usuarios') }}</span>
                </a>
            @endcan
        </div>
    </nav>

    <!-- Collapse toggle button (desktop only) -->
    <div class="hidden lg:flex items-center justify-between px-3 py-3 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <button @click="toggle()" 
                class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                :aria-expanded="!collapsed">
            <svg class="h-4 w-4" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
            <span x-text="collapsed ? 'Expandir' : 'Colapsar'" :class="collapsed ? 'lg:hidden' : ''"></span>
        </button>
    </div>

    <!-- User Dropdown -->
    <div class="border-t border-slate-200 dark:border-slate-800 p-4 bg-white dark:bg-slate-900">
        <x-dropdown align="left" width="48">
            <x-slot name="trigger">
                <button class="flex items-center w-full px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="flex-1 text-left truncate" :class="collapsed ? 'lg:hidden' : ''">{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4 ml-2 flex-shrink-0" :class="collapsed ? 'lg:hidden' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</aside>

<!-- Overlay for mobile (solo visible cuando sidebar está abierto en móvil) -->
<div x-show="open" 
     @click="open = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900/60 z-20 lg:hidden"
     style="display: none;"
     aria-hidden="true"></div>
