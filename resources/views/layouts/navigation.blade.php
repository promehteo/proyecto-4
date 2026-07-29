<div x-data="{ open: false }" @sidebar-toggle.window="open = !open" @keydown.escape.window="open = false">
    <!-- Sidebar component -->
    <aside id="sidebar" 
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0 bg-blue-600 shadow-2xl lg:shadow-none"
        aria-label="Navegación principal">

        <!-- Logo section -->
        <div class="flex items-center justify-between h-16 px-6 bg-blue-700/80 shadow-sm relative z-10">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <!-- Ícono / Logo Mark -->
                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-md transition-transform duration-300 group-hover:scale-105">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="font-bold text-lg text-white tracking-wide truncate">
                    El Rapidito
                </span>
            </a>
            <!-- Close button for mobile -->
            <button @click="open = false" class="lg:hidden p-2 rounded-md text-white hover:text-white hover:bg-blue-800 transition-colors focus:outline-none focus:ring-2 focus:ring-white" aria-label="Cerrar menú">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-6 custom-scrollbar bg-blue-600">
            
            <!-- Sección Principal -->
            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded" class="flex items-center justify-between w-full mb-3 px-2 text-[0.70rem] font-bold text-white uppercase tracking-widest hover:text-white transition-colors focus:outline-none">
                    <span>Principal</span>
                    <svg class="h-3 w-3 transform transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="expanded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-1" style="display: none;">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-blue-800 text-white shadow-inner' : 'text-white hover:bg-blue-700' }}">
                        <svg class="h-5 w-5 mr-3 flex-shrink-0 text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>
            </div>

            <!-- Sección Inventario -->
            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded" class="flex items-center justify-between w-full mb-3 px-2 text-[0.70rem] font-bold text-white uppercase tracking-widest hover:text-white transition-colors focus:outline-none">
                    <span>Inventario</span>
                    <svg class="h-3 w-3 transform transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="expanded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-1" style="display: none;">
                    @can('viewAny', App\Models\Categoria::class)
                        <a href="{{ route('categorias.index') }}"
                            class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('categorias.*') ? 'bg-blue-800 text-white shadow-inner' : 'text-white hover:bg-blue-700' }}">
                            <svg class="h-5 w-5 mr-3 flex-shrink-0 text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span>{{ __('Categorías') }}</span>
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Sección Administración -->
            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded" class="flex items-center justify-between w-full mb-3 px-2 text-[0.70rem] font-bold text-white uppercase tracking-widest hover:text-white transition-colors focus:outline-none">
                    <span>Administración</span>
                    <svg class="h-3 w-3 transform transition-transform duration-300" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="expanded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-1" style="display: none;">
                    @can('viewAny', App\Models\Bitacora::class)
                        <a href="{{ route('bitacora.index') }}"
                            class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('bitacora.*') ? 'bg-blue-800 text-white shadow-inner' : 'text-white hover:bg-blue-700' }}">
                            <svg class="h-5 w-5 mr-3 flex-shrink-0 text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>{{ __('Bitácora') }}</span>
                        </a>
                    @endcan

                    @can('viewAny', App\Models\Rol::class)
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('roles.*') ? 'bg-blue-800 text-white shadow-inner' : 'text-white hover:bg-blue-700' }}">
                            <svg class="h-5 w-5 mr-3 flex-shrink-0 text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>{{ __('Roles') }}</span>
                        </a>
                    @endcan

                    @can('viewAny', App\Models\Permiso::class)
                        <a href="{{ route('permisos.index') }}"
                            class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('permisos.*') ? 'bg-blue-800 text-white shadow-inner' : 'text-white hover:bg-blue-700' }}">
                            <svg class="h-5 w-5 mr-3 flex-shrink-0 text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m-2-2a2 2 0 00-2 2m2-2V5a2 2 0 10-4 0v2m4 0h3a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3m3 4h3m-6 4h6" />
                            </svg>
                            <span>{{ __('Permisos') }}</span>
                        </a>
                    @endcan

                    @can('viewAny', App\Models\User::class)
                        <a href="{{ route('usuarios.index') }}"
                            class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group {{ request()->routeIs('usuarios.*') ? 'bg-blue-800 text-white shadow-inner' : 'text-white hover:bg-blue-700' }}">
                            <svg class="h-5 w-5 mr-3 flex-shrink-0 text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>{{ __('Usuarios') }}</span>
                        </a>
                    @endcan
                </div>
            </div>
        </nav>

        <!-- User / Footer section -->
        <div class="p-4 bg-blue-700 relative z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center w-full px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-blue-800/50 hover:bg-red-500 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-400 group">
                    <svg class="h-5 w-5 mr-2 flex-shrink-0 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>{{ __('Cerrar Sesión') }}</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div x-show="open" @click="open = false" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-20 lg:hidden" style="display: none;"
        aria-hidden="true"></div>
    
    <!-- Custom Scrollbar Style for the Sidebar -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.4);
        }
    </style>
</div>