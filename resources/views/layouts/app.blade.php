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
    <body class="font-sans antialiased bg-slate-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100">
        <!-- Sidebar (fixed) -->
        @include('layouts.navigation')
        
        <!-- Main content container -->
        <div class="min-h-screen flex flex-col lg:ml-64">
            
            <!-- Header (sticky dentro del contenedor, NO fixed sobre viewport) -->
            @isset($header)
                <header class="sticky top-0 z-20 h-16 bg-blue-600 border-b border-blue-700 shadow-md flex items-center">
                    <div class="w-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Mobile hamburger -->
                            <button x-data @click="$dispatch('sidebar-toggle')" 
                                    class="lg:hidden p-2 rounded-lg text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-white"
                                    aria-label="Abrir menú"
                                    aria-expanded="false"
                                    aria-controls="sidebar">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div class="text-white [&_h2]:!text-white [&_h2]:!font-bold">
                                {{ $header }}
                            </div>
                        </div>
                        
                        <!-- User menu / theme toggle centralized in dropdown -->
                        <div class="flex items-center gap-2" x-data="{ isDark: document.documentElement.classList.contains('dark') }">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center gap-2.5 px-3 py-1.5 border border-blue-500 rounded-full text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                        <!-- Círculo tipo avatar -->
                                        <div class="flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white shadow-inner">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <span>{{ Auth::user()->name }}</span>
                                        <svg class="fill-current h-4 w-4 text-blue-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <!-- Enlace a Perfil -->
                                    <x-dropdown-link :href="route('profile.edit')">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>{{ __('Perfil') }}</span>
                                        </div>
                                    </x-dropdown-link>

                                    <!-- Cambiar tema -->
                                    <button @click="
                                        isDark = !isDark;
                                        if (isDark) {
                                            document.documentElement.classList.add('dark');
                                            localStorage.setItem('theme', 'dark');
                                        } else {
                                            document.documentElement.classList.remove('dark');
                                            localStorage.setItem('theme', 'light');
                                        }
                                    " class="block w-full px-4 py-2 text-start text-sm leading-5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:bg-slate-100 dark:focus:bg-slate-800 transition duration-150 ease-in-out">
                                        <div class="flex items-center gap-2">
                                            <!-- Icono luna -->
                                            <svg x-show="!isDark" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                            </svg>
                                            <!-- Icono sol -->
                                            <svg x-show="isDark" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            <span x-show="!isDark">{{ __('Modo Oscuro') }}</span>
                                            <span x-show="isDark">{{ __('Modo Claro') }}</span>
                                        </div>
                                    </button>
                                </x-slot>
                            </x-dropdown>
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
