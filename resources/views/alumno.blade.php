@extends('layouts.app')

@section('title', 'Postulación de Electivos')

@section('content')
    <div class="flex flex-col gap-6">
        <header>
            <h1 class="text-2xl font-semibold">Postulación de Electivos</h1>
            <p class="mt-1 text-sm text-slate-700 dark:text-slate-300">
                Selecciona tus electivos y finaliza tu postulación cuando termines.
            </p>
        </header>

        <section class="grid gap-6 lg:grid-cols-[320px_1fr]">
            <aside class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm">
                <h2 class="text-lg font-semibold">Resumen</h2>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    <p><span class="font-semibold" id="processLabel">-</span></p>
                    <p>
                        <span class="font-semibold" id="selectedCount">0</span> de
                        <span class="font-semibold" id="maxTotal">0</span> electivos seleccionados
                    </p>
                    <p>
                        <span class="font-semibold" id="maxPorArea">-</span> por área (según reglas)
                    </p>
                </div>

                <button id="finishBtn" class="mt-6 w-full rounded-lg bg-[#AD1133] px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#8E0E2A] disabled:cursor-not-allowed disabled:opacity-60">
                    Finalizar Postulación
                </button>

                <div id="alert" class="mt-4 hidden rounded-lg border border-rose-300/70 bg-rose-50/60 px-4 py-3 text-sm text-rose-700"></div>
            </aside>

            <section class="space-y-4">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Electivos disponibles</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Solo se muestran los electivos válidos para tu nivel.
                        </p>
                    </div>
                    <div class="text-sm text-slate-600 dark:text-slate-300">
                        <span class="font-semibold" id="levelLabel">Nivel</span>
                    </div>
                </div>

                <div id="cards" class="grid gap-4 sm:grid-cols-2"></div>
            </section>
        </section>
    </div>

    @push('scripts')
        <script>
            const alertBox = document.getElementById('alert');
            const finishBtn = document.getElementById('finishBtn');
            const selectedCountEl = document.getElementById('selectedCount');
            const maxTotalEl = document.getElementById('maxTotal');
            const maxPorAreaEl = document.getElementById('maxPorArea');
            const processLabel = document.getElementById('processLabel');
            const levelLabel = document.getElementById('levelLabel');
            const cardsContainer = document.getElementById('cards');

            let apiData = {
                postulacion: null,
                seleccion: null,
                electivos: [],
            };

            function showAlert(message, type = 'error') {
                const colors = {
                    error: ['border-rose-300/70', 'bg-rose-50/70', 'text-rose-700'],
                    success: ['border-emerald-300/70', 'bg-emerald-50/70', 'text-emerald-700'],
                };

                const [border, bg, text] = colors[type] || colors.error;
                alertBox.className = `mt-4 rounded-lg border px-4 py-3 text-sm ${border} ${bg} ${text}`;
                alertBox.textContent = message;
                alertBox.classList.remove('hidden');

                setTimeout(() => alertBox.classList.add('hidden'), 5000);
            }

            async function fetchPostulacion() {
                try {
                    const resp = await fetch('/alumno/postulacion');
                    if (!resp.ok) throw resp;

                    const json = await resp.json();
                    apiData = json;

                    updateSummary();
                    renderCards();
                } catch (err) {
                    showAlert('No se puede obtener el proceso activo. Verifica sesión o estado.', 'error');
                }
            }

            function updateSummary() {
                const seleccionCount = apiData.seleccion?.selecciones?.length || 0;
                const maxTotal = apiData.postulacion?.max_total_electivos ?? 0;

                selectedCountEl.textContent = seleccionCount;
                maxTotalEl.textContent = maxTotal;
                processLabel.textContent = apiData.postulacion?.descripcion ?? '-';
                levelLabel.textContent = `Nivel ${apiData.postulacion?.nivel ?? '-'}`;

                const reglaArea = apiData.postulacion?.reglas_areas?.[0]?.max_permitido ?? '-';
                maxPorAreaEl.textContent = reglaArea;

                finishBtn.disabled = seleccionCount === 0;
            }

            function buildCard(electivo) {
                const selected = apiData.seleccion?.selecciones?.some((s) => s.electivo?.id === electivo.id);
                const disabled = apiData.seleccion?.estado_cierre;

                const card = document.createElement('article');
                card.className = 'rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-5 shadow-sm';

                card.innerHTML = `
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">${electivo.nombre}</h3>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">${electivo.descripcion_breve ?? ''}</p>
                        </div>
                        <button
                            class="ml-4 rounded-full border px-3 py-1 text-xs font-semibold ${selected ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-700 border-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-700'} ${disabled ? 'opacity-60 cursor-not-allowed' : 'hover:bg-emerald-600 hover:text-white'}"
                            ${disabled ? 'disabled' : ''}
                        >
                            ${selected ? 'Seleccionado' : 'Seleccionar'}
                        </button>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2 text-xs font-medium text-slate-500 dark:text-slate-400">
                        <span class="rounded-full bg-slate-100 px-2 py-1 dark:bg-slate-800">${electivo.sector.nombre}</span>
                        <span class="rounded-full bg-slate-100 px-2 py-1 dark:bg-slate-800">${electivo.sector.area.nombre}</span>
                        <span class="rounded-full bg-slate-100 px-2 py-1 dark:bg-slate-800">Nivel: ${electivo.nivel_aplicacion}</span>
                        ${electivo.pdf_path ? `<a href="${electivo.pdf_path}" target="_blank" class="rounded-full bg-slate-100 px-2 py-1 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700">PDF</a>` : ''}
                    </div>
                `;

                const button = card.querySelector('button');
                button.addEventListener('click', () => toggleSelection(electivo, selected));

                return card;
            }

            function renderCards() {
                cardsContainer.innerHTML = '';
                apiData.electivos.forEach((electivo) => {
                    cardsContainer.appendChild(buildCard(electivo));
                });
            }

            async function toggleSelection(electivo, selected) {
                try {
                    const url = selected ? '/alumno/postulacion/remover' : '/alumno/postulacion/seleccionar';
                    const body = selected
                        ? { seleccion_id: apiData.seleccion.selecciones.find((s) => s.electivo.id === electivo.id).id }
                        : { electivo_id: electivo.id };

                    const resp = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(body),
                    });

                    if (!resp.ok) {
                        const json = await resp.json();
                        throw new Error(json.message ?? 'Error al actualizar la selección');
                    }

                    await fetchPostulacion();
                } catch (err) {
                    showAlert(err.message);
                }
            }

            async function finalize() {
                try {
                    const resp = await fetch('/alumno/postulacion/finalizar', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                    });

                    if (!resp.ok) {
                        const json = await resp.json();
                        throw new Error(json.message ?? 'Error al finalizar');
                    }

                    showAlert('Postulación finalizada', 'success');
                    await fetchPostulacion();
                } catch (err) {
                    showAlert(err.message);
                }
            }

            finishBtn.addEventListener('click', finalize);

            fetchPostulacion();
        </script>
    @endpush
@endsection
