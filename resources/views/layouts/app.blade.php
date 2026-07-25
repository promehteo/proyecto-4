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
                    <div class="px-4 sm:px-6 lg:px-8 py-4">
                        {{ $header }}
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
