<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mockup - Postulación a Electivos</title>

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
<body class="min-h-screen bg-slate-50 dark:bg-[#0a0a0a] text-white dark:text-slate-100 antialiased font-sans">
    <header class="sticky top-0 z-20 border-b border-slate-200/70 bg-[#AD1133] backdrop-blur dark:border-slate-800/60 dark:bg-[#101010]/70">
        <div class="container mx-auto flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 px-6 py-5">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold tracking-tight">Postulación a Electivos</h1>
                <p class="mt-1 text-sm text-black-600 dark:text-slate-300">
                    Mockup de interfaz de alumno para seleccionar asignaturas electivas (3° y 4° medio).
                </p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <span class="inline-flex items-center rounded-full bg-white dark:bg-slate-900/70 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-slate-200/80 dark:ring-slate-800/80">
                    Proceso: <span class="ml-2 font-semibold">Postulación 2026</span>
                </span>
                <span class="inline-flex items-center rounded-full bg-white dark:bg-slate-900/70 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-slate-200/80 dark:ring-slate-800/80">
                    Estado: <span id="processStatus" class="ml-2 font-semibold text-emerald-700 dark:text-emerald-300">Activo</span>
                </span>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 py-10">
        <section class="grid grid-cols-1 lg:grid-cols-[360px_1fr] gap-10">
            <!-- Sidebar / Resumen -->
            <aside class="flex flex-col gap-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm shadow-slate-200/40 dark:shadow-black/40 lg:sticky lg:top-6">
                <div>
                    <h2 class="text-lg font-semibold">Resumen</h2>
                    <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Administra tu selección respetando las reglas del proceso.
                    </p>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#1D0002] p-3">
                        <dt class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Nivel</dt>
                        <dd class="mt-1 font-semibold" id="currentLevel">3° Medio</dd>
                    </div>
                    <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#1D0002] p-3">
                        <dt class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Máximo electivos</dt>
                        <dd class="mt-1 font-semibold" id="maxTotal">3</dd>
                    </div>
                    <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#1D0002] p-3">
                        <dt class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Máx. por área</dt>
                        <dd class="mt-1 font-semibold" id="maxPorArea">2</dd>
                    </div>
                    <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#1D0002] p-3">
                        <dt class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Seleccionados</dt>
                        <dd class="mt-1 font-semibold" id="selectedCount">0</dd>
                    </div>
                </dl>

                <div class="space-y-2">
                    <h3 class="text-sm font-semibold">Filtrar nivel</h3>
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-full border border-[#e3e3e0] bg-white px-4 py-2 text-sm font-medium text-[#1b1b18] shadow-sm hover:bg-[#f6f6f6] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC] dark:hover:bg-[#1D1D1D]"
                            data-level="3"
                            onclick="setLevel(3)"
                        >
                            3° Medio
                        </button>
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-full border border-[#e3e3e0] bg-white px-4 py-2 text-sm font-medium text-[#1b1b18] shadow-sm hover:bg-[#f6f6f6] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC] dark:hover:bg-[#1D1D1D]"
                            data-level="4"
                            onclick="setLevel(4)"
                        >
                            4° Medio
                        </button>
                    </div>
                </div>

                <div class="pt-4">
                    <button
                        id="finalizeBtn"
                        type="button"
                        class="w-full rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60"
                        onclick="finalizarPostulacion()"
                    >
                        Finalizar Postulación
                    </button>
                    <p class="mt-2 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                        Al finalizar, no podrás modificar tu selección. (Mockup)
                    </p>
                </div>

                <div class="rounded-lg border border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#fafaf9] dark:bg-[#111110] p-4 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <p class="font-semibold">Reglas del mockup</p>
                    <ul class="mt-2 list-disc pl-4 space-y-1">
                        <li>Máximo <span class="font-semibold">3</span> electivos.</li>
                        <li>Máximo <span class="font-semibold">2</span> electivos por área.</li>
                        <li>Solo electivos del nivel seleccionado.</li>
                        <li>Las postulaciones se bloquean al finalizar.</li>
                    </ul>
                </div>
            </aside>

            <!-- Lista de electivos -->
            <section class="space-y-6">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Electivos disponibles</h2>
                        <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Selecciona los electivos según tu nivel académico.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Mostrar:</span>
                        <span class="rounded-full bg-[#FDFDFC] dark:bg-[#1D0002] px-3 py-1 text-xs font-medium border border-[#e3e3e0] dark:border-[#3E3E3A]" id="tagFiltro">3° Medio</span>
                    </div>
                </div>

                <div id="cards" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Cards renderizadas por JS -->
                </div>
            </section>
        </section>
    </main>

    <footer class="border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-6 py-6 text-xs text-slate-600 dark:text-slate-400">
        <div class="container mx-auto flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
            <p>Mockup generado para el proceso de postulación a electivos del Colegio Sagrado Corazón de Jesús.</p>
            <p>Fecha: 17-03-2026</p>
        </div>
    </footer>

    <script>
        const electivos = [
            {
                id: 1,
                nombre: 'Robótica y Tecnología',
                area: 'Tecnología',
                sector: 'Robótica',
                nivel: 3,
                descripcion: 'Aprende a diseñar y programar sistemas robóticos con énfasis en prototipos funcionales.',
                pdf: '#',
            },
            {
                id: 2,
                nombre: 'Astronomía y Astrofísica',
                area: 'Ciencias',
                sector: 'Astronomía',
                nivel: 4,
                descripcion: 'Explora el universo, sistemas solares y las últimas investigaciones en astrofísica.',
                pdf: '#',
            },
            {
                id: 3,
                nombre: 'Taller de Teatro',
                area: 'Artes',
                sector: 'Teatro',
                nivel: 3,
                descripcion: 'Trabajo en escena, improvisación y producción de montajes teatrales.',
                pdf: '#',
            },
            {
                id: 4,
                nombre: 'Diseño Gráfico Digital',
                area: 'Artes',
                sector: 'Diseño',
                nivel: 4,
                descripcion: 'Introducción a herramientas de diseño, tipografía y branding para proyectos digitales.',
                pdf: '#',
            },
            {
                id: 5,
                nombre: 'Emprendimiento y Finanzas',
                area: 'Ciencias',
                sector: 'Economía',
                nivel: 3,
                descripcion: 'Fundamentos de gestión empresarial, finanzas personales y modelos de negocio.',
                pdf: '#',
            },
            {
                id: 6,
                nombre: 'Fotografía y Medios',
                area: 'Artes',
                sector: 'Fotografía',
                nivel: 4,
                descripcion: 'Técnicas de composición, edición y narrativa visual aplicada a proyectos prácticos.',
                pdf: '#',
            },
        ];

        const state = {
            nivel: 3,
            maxTotal: 3,
            maxPorArea: 2,
            seleccionados: new Set(),
            bloqueado: false,
        };

        const cardsContainer = document.getElementById('cards');
        const selectedCountEl = document.getElementById('selectedCount');
        const tagFiltro = document.getElementById('tagFiltro');
        const currentLevelEl = document.getElementById('currentLevel');
        const finalizeBtn = document.getElementById('finalizeBtn');
        const processStatus = document.getElementById('processStatus');

        function renderCards() {
            const nivel = state.nivel;
            const electivosFiltrados = electivos.filter((e) => e.nivel === nivel);

            cardsContainer.innerHTML = electivosFiltrados
                .map((electivo) => {
                    const checked = state.seleccionados.has(electivo.id);
                    const disabled = state.bloqueado;
                    const areaCount = Array.from(state.seleccionados)
                        .map((id) => electivos.find((e) => e.id === id))
                        .filter((e) => e?.area === electivo.area).length;

                    const bloqueadoPorArea = !checked && areaCount >= state.maxPorArea;
                    const bloqueadoPorTotal = !checked && state.seleccionados.size >= state.maxTotal;

                    const disabledReason = bloqueadoPorArea
                        ? `Limite de ${state.maxPorArea} electivos para el área "${electivo.area}"`
                        : bloqueadoPorTotal
                        ? `Máximo de ${state.maxTotal} electivos alcanzado`
                        : '';

                    return `
                        <article class="group relative rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm shadow-slate-200/40 dark:shadow-black/40 transition hover:-translate-y-1 hover:shadow-lg hover:shadow-slate-200/30 dark:hover:shadow-black/50">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="space-y-2">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">${electivo.nombre}</h3>
                                    <p class="text-xs font-medium text-slate-600 dark:text-slate-400">${electivo.area} · ${electivo.sector}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a
                                        href="${electivo.pdf}"
                                        target="_blank"
                                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/70 px-3 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-white dark:hover:bg-slate-900"
                                    >
                                        PDF
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z" fill="currentColor"/>
                                            <path d="M14 2V8H20" fill="currentColor"/>
                                            <path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M9 17H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M9 9H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </a>

                                    <label class="relative inline-flex cursor-pointer items-center">
                                        <input
                                            type="checkbox"
                                            class="peer sr-only"
                                            ${checked ? 'checked' : ''}
                                            ${disabled || bloqueadoPorArea || bloqueadoPorTotal ? 'disabled' : ''}
                                            onchange="toggleElectivo(${electivo.id})"
                                        />
                                        <span class="h-6 w-11 rounded-full bg-slate-200 dark:bg-slate-800 after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow-sm after:transition-all peer-checked:bg-emerald-600 peer-checked:after:translate-x-5 peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-500"></span>
                                    </label>
                                </div>
                            </div>

                            <p class="mt-4 text-sm text-slate-600 dark:text-slate-400">${electivo.descripcion}</p>

                            ${disabledReason ? `<p class="mt-3 text-xs font-medium text-rose-600 dark:text-rose-400">${disabledReason}</p>` : ''}
                        </article>
                    `;
                })
                .join('');

            selectedCountEl.textContent = state.seleccionados.size;
        }

        function toggleElectivo(id) {
            if (state.bloqueado) {
                return;
            }

            const alreadySelected = state.seleccionados.has(id);
            if (alreadySelected) {
                state.seleccionados.delete(id);
            } else {
                state.seleccionados.add(id);
            }

            renderCards();
        }

        function setLevel(nivel) {
            if (state.bloqueado) {
                return;
            }

            state.nivel = nivel;
            state.seleccionados.clear();
            currentLevelEl.textContent = `${nivel}° Medio`;
            tagFiltro.textContent = `${nivel}° Medio`;

            renderCards();
        }

        function finalizarPostulacion() {
            if (state.seleccionados.size === 0) {
                alert('Selecciona al menos un electivo antes de finalizar.');
                return;
            }

            state.bloqueado = true;
            processStatus.textContent = 'Cerrada';
            processStatus.classList.remove('text-emerald-700', 'dark:text-emerald-300');
            processStatus.classList.add('text-[#f53003]', 'dark:text-[#FF4433]');
            finalizeBtn.disabled = true;

            renderCards();

            alert('Postulación finalizada (mockup). No se puede editar nuevamente.');
        }

        renderCards();
    </script>
</body>
</html>
