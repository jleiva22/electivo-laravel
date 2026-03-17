<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', config('app.name', 'Electivo'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@4.0.7/dist/tailwind.min.css" />
    @endif
</head>
<body class="min-h-screen bg-slate-50 dark:bg-[#0a0a0a] text-slate-900 dark:text-slate-100 antialiased font-sans">
    <header class="bg-[#AD1133] text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="/" class="flex items-center gap-2 font-semibold text-lg">
                <span class="inline-block h-8 w-8 rounded-full bg-white/20"></span>
                <span>Electivos</span>
            </a>

            <button id="navToggle" class="lg:hidden inline-flex items-center justify-center rounded-md p-2 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <nav id="navMenu" class="hidden lg:flex lg:items-center lg:gap-6">
                <a href="/" class="text-sm font-medium hover:text-white/90">Inicio</a>
                <a href="/alumno" class="text-sm font-medium hover:text-white/90">Alumno</a>
                <a href="/admin" class="text-sm font-medium hover:text-white/90">Administración</a>
            </nav>
        </div>
        <div id="navMobile" class="hidden border-t border-white/10 bg-white/5 lg:hidden">
            <div class="flex flex-col px-6 py-4 space-y-2">
                <a href="/" class="text-sm font-medium hover:text-white/90">Inicio</a>
                <a href="/alumno" class="text-sm font-medium hover:text-white/90">Alumno</a>
                <a href="/admin" class="text-sm font-medium hover:text-white/90">Administración</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-10">
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        const navToggle = document.getElementById('navToggle');
        const navMobile = document.getElementById('navMobile');

        if (navToggle && navMobile) {
            navToggle.addEventListener('click', () => {
                navMobile.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
