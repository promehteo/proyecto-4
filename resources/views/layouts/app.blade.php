<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
        
        <!-- Alpine Sidebar Store -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('sidebar', {
                    open: false,
                    collapsed: localStorage.getItem('sidebar-collapsed') === '1'
                });
            });
        </script>
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 min-h-screen">
        <!-- Sidebar Component -->
        <x-sidebar />
        
        <!-- Content Container with dynamic left margin based on sidebar state -->
        <div 
            x-data
            x-bind:class="$store.sidebar.collapsed ? 'lg:ml-20' : 'lg:ml-64'"
            class="min-h-screen transition-all duration-200 ease-in-out"
        >
            <!-- Header / Top Bar (inside content area, NOT fixed over viewport) -->
            <header class="sticky top-0 z-20 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Mobile hamburger button -->
                    <button 
                        @click="$store.sidebar.open = true"
                        class="lg:hidden p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        aria-label="Abrir menú"
                        aria-expanded="false"
                        aria-controls="sidebar"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <!-- Page title / actions area -->
                    <div class="flex-1 flex items-center justify-between">
                        @isset($header)
                            <div class="flex-1">
                                {{ $header }}
                            </div>
                        @endisset
                        
                        <!-- Theme toggle and user actions can go here -->
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
        
        <!-- Dark mode script -->
        <script>
            (function() {
                const theme = localStorage.getItem('theme');
                if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
    </body>
</html>
