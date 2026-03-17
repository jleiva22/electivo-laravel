@extends('layouts.app')

@section('title', 'Administración de Postulaciones')

@section('content')
    <div class="flex flex-col gap-6">
        <header>
            <h1 class="text-2xl font-semibold">Administración de Postulaciones</h1>
            <p class="mt-1 text-sm text-slate-700 dark:text-slate-300">
                Gestiona procesos y revisa postulaciones.
            </p>
        </header>

        <section class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="space-y-6">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-lg font-semibold">Procesos de postulación</h2>
                    <button id="refreshBtn" class="rounded-lg bg-[#AD1133] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8E0E2A]">
                        Refrescar
                    </button>
                </div>

                <div id="processList" class="space-y-4"></div>
            </div>

            <aside class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm">
                <h2 class="text-lg font-semibold">Áreas</h2>
                <div id="areasList" class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300"></div>
                <div class="mt-6 rounded-lg border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-4 text-xs text-slate-600 dark:text-slate-300">
                    <p class="font-semibold">Nota</p>
                    <p class="mt-2">Esta vista consume la API de administración. Crea/edita procesos desde el backend (controlador).</p>
                </div>
            </aside>
        </section>
    </div>

    @push('scripts')
        <script>
            const processList = document.getElementById('processList');
            const areasList = document.getElementById('areasList');
            const refreshBtn = document.getElementById('refreshBtn');

            async function fetchProcesses() {
                const resp = await fetch('/admin/postulaciones');
                if (!resp.ok) return;

                const json = await resp.json();
                renderProcesses(json.data || []);
            }

            async function fetchAreas() {
                const resp = await fetch('/admin/areas');
                if (!resp.ok) return;

                const json = await resp.json();
                renderAreas(json.data || []);
            }

            function renderAreas(areas) {
                areasList.innerHTML = areas
                    .map((area) => `
                        <div class="rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-3">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-slate-900 dark:text-slate-100">${area.nombre}</span>
                            </div>
                        </div>
                    `)
                    .join('');
            }

            function renderProcesses(processes) {
                if (!processes.length) {
                    processList.innerHTML = `<p class="text-sm text-slate-600 dark:text-slate-300">No hay procesos de postulación registrados.</p>`;
                    return;
                }

                processList.innerHTML = processes
                    .map((p) => {
                        const statusClass = p.estado === 'activa' ? 'bg-emerald-50 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-300';

                        const reglas = (p.reglas_areas || []).map((r) => `
                            <li class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-300">
                                <span>${r.area.nombre}</span>
                                <span class="font-semibold">${r.max_permitido}</span>
                            </li>
                        `).join('');

                        return `
                            <article class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm">
                                <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">${p.descripcion}</h3>
                                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs font-medium">
                                            <span class="rounded-full px-2 py-1 ${statusClass}">${p.estado.toUpperCase()}</span>
                                            <span class="text-slate-500 dark:text-slate-400">Máx total: <span class="font-semibold">${p.max_total_electivos}</span></span>
                                        </div>
                                    </div>
                                    <button
                                        class="rounded-lg bg-[#AD1133] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8E0E2A]"
                                        onclick="closeProcess(${p.id})"
                                    >
                                        Cerrar
                                    </button>
                                </div>

                                <div class="mt-4">
                                    <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Reglas por área</h4>
                                    <ul class="mt-2 space-y-1">
                                        ${reglas || '<li class="text-sm text-slate-600 dark:text-slate-300">Sin reglas de área.</li>'}
                                    </ul>
                                </div>
                            </article>
                        `;
                    })
                    .join('');
            }

            async function closeProcess(id) {
                const resp = await fetch(`/admin/postulaciones/${id}/close`, { method: 'POST' });
                if (!resp.ok) return;
                await fetchProcesses();
            }

            refreshBtn.addEventListener('click', () => {
                fetchProcesses();
                fetchAreas();
            });

            fetchProcesses();
            fetchAreas();
        </script>
    @endpush
@endsection
