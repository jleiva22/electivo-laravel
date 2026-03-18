<header class="bg-[#AD1133] text-white shadow-md">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
        <a href="/" class="flex items-center gap-2 font-semibold text-lg italic">
            Sistema de electivos
        </a>

        <div class="flex items-center gap-6">
            <nav id="navMenu" class="hidden lg:flex lg:items-center lg:gap-6">
                <a href="/" class="text-sm font-medium hover:text-white/80">Inicio</a>
                <a href="/alumno" class="text-sm font-medium hover:text-white/80">Alumno</a>
                <a href="/admin" class="text-sm font-medium hover:text-white/80">Administración</a>
                <a href="/login" class="bg-white text-[#AD1133] px-4 py-2  text-sm font-bold border-none hover:bg-gray-100 transition-colors">
                    INGRESAR
                </a>
            </nav>

            <button id="navToggle" class="lg:hidden inline-flex items-center justify-center rounded-md p-2 hover:bg-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <div id="navMobile" class="hidden border-t border-white/10 bg-black/10 lg:hidden">
        <div class="flex flex-col px-6 py-4 space-y-3">
            <a href="/" class="text-sm font-medium">Inicio</a>
            <a href="/alumno" class="text-sm font-medium">Alumno</a>
            <a href="/admin" class="text-sm font-medium">Administración</a>
            <a href="/login" class="inline-block bg-white text-[#AD1133] px-4 py-2 rounded-[8px] text-center font-bold">INGRESAR</a>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navToggle = document.getElementById('navToggle');
        const navMobile = document.getElementById('navMobile');
        if (navToggle && navMobile) {
            navToggle.addEventListener('click', () => {
                navMobile.classList.toggle('hidden');
            });
        }
    });
</script>