@extends('layouts.app')

@section('title', 'Catálogo Académico - Administración')

@section('content')
    <div class="flex flex-col gap-6">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">Catálogo Académico</h1>
                <p class="mt-1 text-sm text-slate-700 dark:text-slate-300">
                    Gestiona las áreas, sectores y electivos.
                </p>
            </div>
            <nav class="flex items-center gap-2">
                <a href="/admin" class="rounded-lg bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-400">Procesos</a>
                <a href="/admin/catalogo" class="rounded-lg bg-slate-200 dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-800 dark:text-slate-100">Catálogo Académico</a>
            </nav>
        </header>

        <section class="grid gap-6 lg:grid-cols-3">
            <!-- Áreas -->
            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Áreas</h2>
                    <button onclick="openModal('areaModal')" class="rounded-lg bg-slate-100 dark:bg-slate-900 px-3 py-1 text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-800">+</button>
                </div>
                <div id="areasList" class="flex flex-col gap-2"></div>
            </div>

            <!-- Sectores -->
            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Sectores</h2>
                    <button onclick="openModal('sectorModal')" class="rounded-lg bg-slate-100 dark:bg-slate-900 px-3 py-1 text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-800">+</button>
                </div>
                <div id="sectorsList" class="flex flex-col gap-2"></div>
            </div>

            <!-- Electivos -->
            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Electivos</h2>
                    <button onclick="openModal('electivoModal')" class="rounded-lg bg-[#AD1133] text-white px-3 py-1 text-sm font-semibold hover:bg-[#8E0E2A]">+</button>
                </div>
                <div id="electivosList" class="flex flex-col gap-2"></div>
            </div>
        </section>
    </div>

    <!-- Modal Área -->
    <dialog id="areaModal" class="rounded-xl p-6 shadow-xl backdrop:bg-black/50 dark:bg-slate-900 dark:text-white w-full max-w-sm">
        <form id="areaForm" onsubmit="saveArea(event)" class="flex flex-col gap-4">
            <h3 class="text-lg font-semibold" id="areaModalTitle">Nueva Área</h3>
            <input type="hidden" id="areaId">
            <label class="flex flex-col gap-1 text-sm font-medium">
                Nombre del Área
                <input type="text" id="areaName" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2">
            </label>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('areaModal')" class="px-4 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">Cancelar</button>
                <button type="submit" class="rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-4 py-2 text-sm font-semibold">Guardar</button>
            </div>
        </form>
    </dialog>

    <!-- Modal Sector -->
    <dialog id="sectorModal" class="rounded-xl p-6 shadow-xl backdrop:bg-black/50 dark:bg-slate-900 dark:text-white w-full max-w-sm">
        <form id="sectorForm" onsubmit="saveSector(event)" class="flex flex-col gap-4">
            <h3 class="text-lg font-semibold" id="sectorModalTitle">Nuevo Sector</h3>
            <input type="hidden" id="sectorId">
            <label class="flex flex-col gap-1 text-sm font-medium">
                Área
                <select id="sectorAreaId" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2"></select>
            </label>
            <label class="flex flex-col gap-1 text-sm font-medium">
                Nombre del Sector
                <input type="text" id="sectorName" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2">
            </label>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('sectorModal')" class="px-4 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">Cancelar</button>
                <button type="submit" class="rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-4 py-2 text-sm font-semibold">Guardar</button>
            </div>
        </form>
    </dialog>

    <!-- Modal Electivo -->
    <dialog id="electivoModal" class="rounded-xl p-6 shadow-xl backdrop:bg-black/50 dark:bg-slate-900 dark:text-white w-full max-w-md">
        <form id="electivoForm" onsubmit="saveElectivo(event)" class="flex flex-col gap-4">
            <h3 class="text-lg font-semibold" id="electivoModalTitle">Nuevo Electivo</h3>
            <input type="hidden" id="electivoId">
            <label class="flex flex-col gap-1 text-sm font-medium">
                Sector
                <select id="electivoSectorId" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2"></select>
            </label>
            <label class="flex flex-col gap-1 text-sm font-medium">
                Nombre
                <input type="text" id="electivoName" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2">
            </label>
            <label class="flex flex-col gap-1 text-sm font-medium">
                Descripción
                <textarea id="electivoDesc" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 h-20"></textarea>
            </label>
            <label class="flex flex-col gap-1 text-sm font-medium">
                Nivel Aplicación
                <select id="electivoNivel" required class="rounded-lg border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2">
                    <option value="3">3° Medio</option>
                    <option value="4">4° Medio</option>
                    <option value="comun">Común Ambos</option>
                </select>
            </label>
            <label class="flex flex-col gap-1 text-sm font-medium">
                Subir PDF (Opcional)
                <input type="file" id="electivoPdf" accept=".pdf" class="text-xs file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 dark:file:bg-slate-800 file:px-4 file:py-2">
            </label>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('electivoModal')" class="px-4 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">Cancelar</button>
                <button type="submit" class="rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-4 py-2 text-sm font-semibold">Guardar</button>
            </div>
        </form>
    </dialog>

    @push('scripts')
        <script>
            let areasData = [];
            let sectorsData = [];

            const fetchData = async () => {
                await Promise.all([loadAreas(), loadSectors(), loadElectivos()]);
            };

            const loadAreas = async () => {
                const res = await fetch('/admin/areas-crud');
                areasData = await res.json();
                renderAreas();
                updateAreaSelect();
            };

            const loadSectors = async () => {
                const res = await fetch('/admin/sectores');
                sectorsData = await res.json();
                renderSectors();
                updateSectorSelect();
            };

            const loadElectivos = async () => {
                const res = await fetch('/admin/electivos');
                const data = await res.json();
                renderElectivos(data);
            };

            const renderAreas = () => {
                document.getElementById('areasList').innerHTML = areasData.map(a => `
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 dark:border-slate-800 p-3 text-sm">
                        <span class="font-medium">${a.nombre}</span>
                        <div class="flex gap-2">
                            <button onclick="editArea(${a.id}, '${a.nombre}')" class="text-blue-600 hover:text-blue-800">Edit</button>
                            <button onclick="deleteEntity('/admin/areas-crud/${a.id}')" class="text-red-600 hover:text-red-800">Del</button>
                        </div>
                    </div>
                `).join('');
            };

            const renderSectors = () => {
                document.getElementById('sectorsList').innerHTML = sectorsData.map(s => `
                    <div class="flex flex-col gap-1 rounded-lg border border-slate-100 dark:border-slate-800 p-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">${s.nombre}</span>
                            <div class="flex gap-2">
                                <button onclick="editSector(${s.id}, ${s.area_id}, '${s.nombre}')" class="text-blue-600 hover:text-blue-800">Edit</button>
                                <button onclick="deleteEntity('/admin/sectores/${s.id}')" class="text-red-600 hover:text-red-800">Del</button>
                            </div>
                        </div>
                        <span class="text-xs text-slate-500">${s.area.nombre}</span>
                    </div>
                `).join('');
            };

            const renderElectivos = (electivos) => {
                document.getElementById('electivosList').innerHTML = electivos.map(e => `
                    <div class="flex flex-col gap-2 rounded-lg border border-slate-100 dark:border-slate-800 p-3 text-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col">
                                <span class="font-semibold">${e.nombre}</span>
                                <span class="text-xs text-slate-500">${e.sector.area.nombre} / ${e.sector.nombre}</span>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <button onclick="editElectivo(${e.id}, ${e.sector_id}, '${e.nombre}', '${e.descripcion_breve}', '${e.nivel_aplicacion}')" class="text-blue-600 hover:text-blue-800">Edit</button>
                                <button onclick="deleteEntity('/admin/electivos/${e.id}')" class="text-red-600 hover:text-red-800">Del</button>
                            </div>
                        </div>
                        ${e.pdf_path ? `<a href="/storage/${e.pdf_path}" target="_blank" class="text-xs text-emerald-600 underline">Ver PDF Adjunto</a>` : ''}
                    </div>
                `).join('');
            };

            const updateAreaSelect = () => {
                document.getElementById('sectorAreaId').innerHTML = areasData.map(a => `<option value="${a.id}">${a.nombre}</option>`).join('');
            };
            const updateSectorSelect = () => {
                document.getElementById('electivoSectorId').innerHTML = sectorsData.map(s => `<option value="${s.id}">${s.area.nombre} - ${s.nombre}</option>`).join('');
            };

            // Helpers para Modales
            const openModal = (id) => {
                document.getElementById(id).showModal();
            };
            const closeModal = (id) => {
                document.getElementById(id).close();
                document.getElementById(id.replace('Modal', 'Form')).reset();
                if(document.getElementById(id.replace('Modal', 'Id'))) document.getElementById(id.replace('Modal', 'Id')).value = '';
            };

            // CRUD Área
            const saveArea = async (e) => {
                e.preventDefault();
                const id = document.getElementById('areaId').value;
                const url = id ? `/admin/areas-crud/${id}` : '/admin/areas-crud';
                const method = id ? 'PUT' : 'POST';
                const body = JSON.stringify({ nombre: document.getElementById('areaName').value });
                
                await executeRequest(url, method, body);
                closeModal('areaModal');
                fetchData();
            };
            const editArea = (id, name) => {
                document.getElementById('areaId').value = id;
                document.getElementById('areaName').value = name;
                document.getElementById('areaModalTitle').innerText = 'Editar Área';
                openModal('areaModal');
            };

            // CRUD Sector
            const saveSector = async (e) => {
                e.preventDefault();
                const id = document.getElementById('sectorId').value;
                const url = id ? `/admin/sectores/${id}` : '/admin/sectores';
                const method = id ? 'PUT' : 'POST';
                const body = JSON.stringify({ 
                    nombre: document.getElementById('sectorName').value,
                    area_id: document.getElementById('sectorAreaId').value 
                });
                
                await executeRequest(url, method, body);
                closeModal('sectorModal');
                fetchData();
            };
            const editSector = (id, areaId, name) => {
                document.getElementById('sectorId').value = id;
                document.getElementById('sectorName').value = name;
                document.getElementById('sectorAreaId').value = areaId;
                document.getElementById('sectorModalTitle').innerText = 'Editar Sector';
                openModal('sectorModal');
            };

            // CRUD Electivo
            const saveElectivo = async (e) => {
                e.preventDefault();
                const id = document.getElementById('electivoId').value;
                const url = id ? `/admin/electivos/${id}` : '/admin/electivos';
                
                const formData = new FormData();
                if (id) formData.append('_method', 'PUT'); // Laravel workaround for multipart/form-data with PUT
                formData.append('nombre', document.getElementById('electivoName').value);
                formData.append('sector_id', document.getElementById('electivoSectorId').value);
                formData.append('descripcion_breve', document.getElementById('electivoDesc').value);
                formData.append('nivel_aplicacion', document.getElementById('electivoNivel').value);
                
                const fileInput = document.getElementById('electivoPdf');
                if (fileInput.files[0]) {
                    formData.append('pdf_path', fileInput.files[0]);
                }

                const res = await fetch(url, {
                    method: 'POST', // Always POST, method overrides handle PUT
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (res.ok) {
                    closeModal('electivoModal');
                    fetchData();
                } else {
                    const err = await res.json();
                    alert(err.message || 'Error al guardar');
                }
            };
            const editElectivo = (id, sectorId, nombre, desc, nivel) => {
                document.getElementById('electivoId').value = id;
                document.getElementById('electivoName').value = nombre;
                document.getElementById('electivoDesc').value = desc;
                document.getElementById('electivoSectorId').value = sectorId;
                document.getElementById('electivoNivel').value = nivel;
                document.getElementById('electivoModalTitle').innerText = 'Editar Electivo';
                openModal('electivoModal');
            };

            const deleteEntity = async (url) => {
                if(!confirm('¿Seguro que deseas eliminar este registro?')) return;
                await executeRequest(url, 'DELETE', null);
                fetchData();
            };

            const executeRequest = async (url, method, body) => {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body
                });
                if (!res.ok) {
                    const err = await res.json();
                    alert(err.message || 'Error en la petición');
                }
            };

            document.addEventListener('DOMContentLoaded', fetchData);
        </script>
    @endpush
@endsection
