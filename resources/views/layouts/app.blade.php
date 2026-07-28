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
            // Theme persistence
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100">
        <!-- Sidebar (fixed) -->
        @include('layouts.navigation')
        
        <!-- Main content container -->
        <div class="min-h-screen flex flex-col lg:ml-64">
            
            <!-- Header (sticky dentro del contenedor, NO fixed sobre viewport) -->
            @isset($header)
                <header class="sticky top-0 z-20 h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm flex items-center">
                    <div class="w-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Mobile hamburger -->
                            <button x-data @click="$dispatch('sidebar-toggle')" 
                                    class="lg:hidden p-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    aria-label="Abrir menú"
                                    aria-expanded="false"
                                    aria-controls="sidebar">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            {{ $header }}
                        </div>
                        
                        <!-- User menu / theme toggle could go here -->
                        <div class="flex items-center gap-2" x-data="{ isDark: document.documentElement.classList.contains('dark') }">
                            <!-- Theme toggle -->
                            <button @click="
                                isDark = !isDark;
                                if (isDark) {
                                    document.documentElement.classList.add('dark');
                                    localStorage.setItem('theme', 'dark');
                                } else {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.setItem('theme', 'light');
                                }
                            " 
                                    class="p-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    aria-label="Cambiar tema">
                                <svg x-show="!isDark" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                <svg x-show="isDark" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm border-t border-slate-200 dark:border-slate-800 py-4 mt-8">
                <div class="px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-700 dark:text-slate-300 font-medium">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Todos los derechos reservados.
                </div>
            </footer>
        </div>
    </body>
</html>
