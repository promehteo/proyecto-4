<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            // Inicializar tema desde localStorage o preferencia del sistema
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Inicializar estado de sidebar desde localStorage
            window.sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        </script>
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100">
        <!-- Sidebar -->
        @include('layouts.navigation')
        
        <!-- Contenedor de contenido -->
        <div class="min-h-screen transition-all duration-200 lg:ml-64"
             x-data="{ collapsed: window.sidebarCollapsed }"
             x-init="$watch('collapsed', value => localStorage.setItem('sidebar-collapsed', value))"
             :class="collapsed ? 'lg:ml-20' : 'lg:ml-64'">
            
            <!-- Header dentro del contenedor -->
            @isset($header)
                <header class="sticky top-0 z-20 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                        <!-- Botón hamburguesa móvil -->
                        <button @click="$dispatch('sidebar-toggle')" 
                                class="lg:hidden p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                aria-label="Abrir menú"
                                aria-expanded="false"
                                aria-controls="sidebar-nav">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="flex-1">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
