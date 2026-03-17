<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mockup - Administración de Postulaciones</title>

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
                <h1 class="text-2xl font-semibold tracking-tight">Administración de Postulaciones</h1>
                <p class="mt-1 text-sm text-black-600 dark:text-slate-300">
                    Mockup de interfaz para gestionar postulaciones de alumnos a electivos.
                </p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <span class="inline-flex items-center rounded-full bg-white dark:bg-slate-900/70 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-slate-200/80 dark:ring-slate-800/80">
                    Total registros: <span id="totalCount" class="ml-2 font-semibold">0</span>
                </span>
                <span class="inline-flex items-center rounded-full bg-white dark:bg-slate-900/70 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-slate-200/80 dark:ring-slate-800/80">
                    Última actualización: <span id="lastUpdated" class="ml-2 font-semibold">-</span>
                </span>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 py-10">
        <section class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-10">
            <!-- Sidebar / Controles -->
            <aside class="flex flex-col gap-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm shadow-slate-200/40 dark:shadow-black/40 lg:sticky lg:top-6">
                <div>
                    <h2 class="text-lg font-semibold">Resumen</h2>
                    <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Usa los filtros para ver postulaciones por estado y nivel.
                    </p>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#1D0002] p-3">
                        <dt class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Pendientes</dt>
                        <dd class="mt-1 font-semibold" id="countPending">0</dd>
                    </div>
                    <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#1D0002] p-3">
                        <dt class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Aprobadas</dt>
                        <dd class="mt-1 font-semibold" id="countApproved">0</dd>
                    </div>
                    <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#1D0002] p-3">
                        <dt class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Rechazadas</dt>
                        <dd class="mt-1 font-semibold" id="countRejected">0</dd>
                    </div>
                    <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#1D0002] p-3">
                        <dt class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Nivel</dt>
                        <dd class="mt-1 font-semibold" id="currentLevel">3° Medio</dd>
                    </div>
                </dl>

                <div class="space-y-2">
                    <h3 class="text-sm font-semibold">Filtrar</h3>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A]">Estado</label>
                        <select id="filterStatus" class="w-full rounded-lg border border-[#e3e3e0] bg-white px-3 py-2 text-sm text-[#1b1b18] shadow-sm focus:outline-none focus:ring-2 focus:ring-[#AD1133] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]" onchange="renderTable()">
                            <option value="all">Todos</option>
                            <option value="pending">Pendientes</option>
                            <option value="approved">Aprobadas</option>
                            <option value="rejected">Rechazadas</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A]">Nivel</label>
                        <select id="filterLevel" class="w-full rounded-lg border border-[#e3e3e0] bg-white px-3 py-2 text-sm text-[#1b1b18] shadow-sm focus:outline-none focus:ring-2 focus:ring-[#AD1133] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]" onchange="setLevel()">
                            <option value="3">3° Medio</option>
                            <option value="4">4° Medio</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4">
                    <button
                        id="refreshBtn"
                        type="button"
                        class="w-full rounded-lg bg-[#AD1133] px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#8E0E2A]"
                        onclick="refreshData()"
                    >
                        Refrescar datos
                    </button>
                    <p class="mt-2 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                        Este mockup no persiste cambios, solo simula acciones de administración.
                    </p>
                </div>
            </aside>

            <!-- Tabla principal -->
            <section class="space-y-6">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Listado de postulaciones</h2>
                        <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Revisa y gestiona las postulaciones de alumnos.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Mostrando</span>
                        <span class="rounded-full bg-[#FDFDFC] dark:bg-[#1D0002] px-3 py-1 text-xs font-medium border border-[#e3e3e0] dark:border-[#3E3E3A]" id="tagFiltro">Todos</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-sm" id="postulacionesTable">
                        <thead class="bg-slate-100 dark:bg-slate-900 text-black dark:text-slate-300">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Estudiante</th>
                                <th class="px-4 py-3 text-left font-semibold">Nivel</th>
                                <th class="px-4 py-3 text-left font-semibold">Electivo</th>
                                <th class="px-4 py-3 text-left font-semibold">Estado</th>
                                <th class="px-4 py-3 text-left font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800" id="tableBody">
                            <!-- Filas renderizadas por JS -->
                        </tbody>
                    </table>
                </div>

                <div class="rounded-lg border border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#fafaf9] dark:bg-[#111110] p-4 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <p class="font-semibold">Notas</p>
                    <ul class="mt-2 list-disc pl-4 space-y-1">
                        <li>Usa los botones de acción para cambiar el estado de una postulación.</li>
                        <li>El filtro por estado y nivel se aplica en el listado.</li>
                        <li>Los datos son estáticos y se regeneran al pulsar "Refrescar datos".</li>
                    </ul>
                </div>
            </section>
        </section>
    </main>

    <footer class="border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-6 py-6 text-xs text-slate-600 dark:text-slate-400">
        <div class="container mx-auto flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
            <p>Mockup de administración de postulaciones del Colegio Sagrado Corazón de Jesús.</p>
            <p>Fecha: 17-03-2026</p>
        </div>
    </footer>

    <script>
        const postulacionesBase = [
            {
                id: 1,
                estudiante: 'María Fernández',
                nivel: 3,
                electivo: 'Robótica y Tecnología',
                estado: 'pending',
                fecha: '17-03-2026',
            },
            {
                id: 2,
                estudiante: 'Javier Soto',
                nivel: 4,
                electivo: 'Diseño Gráfico Digital',
                estado: 'approved',
                fecha: '16-03-2026',
            },
            {
                id: 3,
                estudiante: 'Ana Ruiz',
                nivel: 3,
                electivo: 'Taller de Teatro',
                estado: 'rejected',
                fecha: '17-03-2026',
            },
            {
                id: 4,
                estudiante: 'Luis Ramírez',
                nivel: 4,
                electivo: 'Astronomía y Astrofísica',
                estado: 'pending',
                fecha: '17-03-2026',
            },
            {
                id: 5,
                estudiante: 'Camila González',
                nivel: 3,
                electivo: 'Emprendimiento y Finanzas',
                estado: 'approved',
                fecha: '15-03-2026',
            },
        ];

        let postulaciones = [...postulacionesBase];

        function statusLabel(status) {
            const map = {
                pending: { label: 'Pendiente', color: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200' },
                approved: { label: 'Aprobada', color: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200' },
                rejected: { label: 'Rechazada', color: 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200' },
            };
            return map[status] || map.pending;
        }

        function formatLevel(level) {
            return `${level}° Medio`;
        }

        function applyFilters() {
            const status = document.getElementById('filterStatus').value;
            const level = Number(document.getElementById('filterLevel').value);

            return postulaciones.filter((p) => {
                const matchesLevel = p.nivel === level;
                const matchesStatus = status === 'all' ? true : p.estado === status;
                return matchesLevel && matchesStatus;
            });
        }

        function updateSummary() {
            const total = postulaciones.length;
            const pending = postulaciones.filter((p) => p.estado === 'pending').length;
            const approved = postulaciones.filter((p) => p.estado === 'approved').length;
            const rejected = postulaciones.filter((p) => p.estado === 'rejected').length;

            document.getElementById('totalCount').textContent = total;
            document.getElementById('countPending').textContent = pending;
            document.getElementById('countApproved').textContent = approved;
            document.getElementById('countRejected').textContent = rejected;
            document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
            document.getElementById('currentLevel').textContent = formatLevel(Number(document.getElementById('filterLevel').value));
        }

        function renderTable() {
            const rows = applyFilters();
            const tbody = document.getElementById('tableBody');
            const tag = document.getElementById('tagFiltro');
            const status = document.getElementById('filterStatus').value;

            tag.textContent = status === 'all' ? 'Todos' : status === 'pending' ? 'Pendientes' : status === 'approved' ? 'Aprobadas' : 'Rechazadas';

            tbody.innerHTML = rows
                .map((p) => {
                    const statusStyle = statusLabel(p.estado);
                    return `
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900">
                            <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">${p.estudiante}</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">${formatLevel(p.nivel)}</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">${p.electivo}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ${statusStyle.color}">${statusStyle.label}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <button class="rounded-md bg-emerald-600 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-700" onclick="updateStatus(${p.id}, 'approved')">Aprobar</button>
                                    <button class="rounded-md bg-rose-600 px-3 py-1 text-xs font-semibold text-white hover:bg-rose-700" onclick="updateStatus(${p.id}, 'rejected')">Rechazar</button>
                                </div>
                            </td>
                        </tr>
                    `;
                })
                .join('');

            updateSummary();
        }

        function updateStatus(id, newStatus) {
            postulaciones = postulaciones.map((p) => (p.id === id ? { ...p, estado: newStatus } : p));
            renderTable();
        }

        function setLevel() {
            document.getElementById('currentLevel').textContent = formatLevel(Number(document.getElementById('filterLevel').value));
            renderTable();
        }

        function refreshData() {
            postulaciones = [...postulacionesBase];
            document.getElementById('filterStatus').value = 'all';
            document.getElementById('filterLevel').value = '3';
            renderTable();
        }

        // Inicializar
        renderTable();
    </script>
</body>
</html>
