@extends('layouts.app')

@section('title', 'Resultados de Postulación - Administración')

@section('content')
    <div class="flex flex-col gap-6">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="/admin" class="text-sm text-blue-600 hover:underline mb-2 inline-block">&larr; Volver a Procesos</a>
                <h1 class="text-2xl font-semibold">Resultados de la Postulación</h1>
                <p class="mt-1 text-sm text-slate-700 dark:text-slate-300">
                    Revisión de estudiantes y sus electivos seleccionados.
                </p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex flex-col items-end">
                    <span class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Total Alumnos</span>
                    <span id="totalStudents" class="text-2xl font-bold">0</span>
                </div>
            </div>
        </header>

        <section class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                <thead class="border-b border-slate-200 dark:border-slate-800 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3 font-semibold">RUT</th>
                        <th class="px-4 py-3 font-semibold">Alumno</th>
                        <th class="px-4 py-3 font-semibold">Curso / Nivel</th>
                        <th class="px-4 py-3 font-semibold">Estado</th>
                        <th class="px-4 py-3 font-semibold">Electivos Seleccionados</th>
                    </tr>
                </thead>
                <tbody id="resultsTableBody" class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <tr><td colspan="5" class="py-4 text-center">Cargando resultados...</td></tr>
                </tbody>
            </table>
        </section>
    </div>

    @push('scripts')
        <script>
            const postId = {{ $id }};
            
            async function fetchResults() {
                try {
                    const res = await fetch(`/admin/postulaciones/${postId}/resultados`);
                    if (!res.ok) throw new Error('Error al obtener datos');
                    const json = await res.json();
                    renderTable(json.data || []);
                } catch (error) {
                    document.getElementById('resultsTableBody').innerHTML = `
                        <tr><td colspan="5" class="py-4 text-center text-red-500">Error al cargar resultados</td></tr>
                    `;
                }
            }

            function renderTable(results) {
                document.getElementById('totalStudents').innerText = results.length;

                if (!results.length) {
                    document.getElementById('resultsTableBody').innerHTML = `
                        <tr><td colspan="5" class="py-8 text-center text-slate-500">Aún no hay alumnos participando en esta postulación.</td></tr>
                    `;
                    return;
                }

                document.getElementById('resultsTableBody').innerHTML = results.map(row => {
                    const alumno = row.alumno || {};
                    const user = alumno.user || {};
                    const selecciones = row.selecciones || [];

                    const listElectivos = selecciones.map(s => {
                        const electivo = s.electivo || {};
                        return `<div class="inline-flex items-center rounded-md bg-slate-100 dark:bg-slate-800 px-2 py-1 text-xs font-medium text-slate-600 dark:text-slate-300 mr-2 mb-1 border border-slate-200 dark:border-slate-700">
                                  ${electivo.nombre}
                                </div>`;
                    }).join('');

                    const isClosed = row.estado_cierre;
                    const statusBadge = isClosed 
                        ? `<span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-400 dark:ring-emerald-400/20">Finalizada</span>`
                        : `<span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-400/10 dark:text-yellow-500 dark:ring-yellow-400/20">Pendiente</span>`;

                    return `
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap font-medium">${alumno.rut || 'N/A'}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900 dark:text-white">${alumno.nombre} ${alumno.apellido}</div>
                                <div class="text-xs text-slate-500">${user.email}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="block">${alumno.curso || 'Sin Curso'}</span>
                                <span class="text-xs text-slate-500">${alumno.nivel_actual}° Medio</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">${statusBadge}</td>
                            <td class="px-4 py-3 min-w-[300px]">
                                ${listElectivos || '<span class="text-xs italic text-slate-400">Sin selecciones</span>'}
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            document.addEventListener('DOMContentLoaded', fetchResults);
        </script>
    @endpush
@endsection
