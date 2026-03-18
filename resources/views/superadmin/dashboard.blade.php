@extends('layouts.app')

@section('title', 'Superadmin - Panel')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
        <aside class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4">
                <div class="space-y-1">
                    <h1 class="text-xl font-semibold">Superadmin</h1>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Panel de administración general.</p>
                </div>

                <nav class="flex flex-col gap-2 pt-3">
                    <a href="#dashboard" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        Dashboard
                    </a>
                    <a href="#import" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        Importar alumnos
                    </a>
                    <a href="#users" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        Usuarios
                    </a>
                    <a href="#alumnos" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        Alumnos
                    </a>
                </nav>
            </div>
        </aside>

        <main class="space-y-6">
            <section id="dashboard" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <header class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Resumen rápido</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Métricas generales del sistema.</p>
                    </div>
                </header>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Usuarios</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $totalUsers }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Alumnos</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $totalAlumnos }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Electivos</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $totalElectivos }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Postulaciones</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $totalPostulaciones }}</p>
                    </div>
                </div>
            </section>

            <section id="import" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <header class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Importar alumnos</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Sube un archivo CSV con los campos: <strong>N° de lista, Run, Apellido paterno, Apellido materno, Nombres, Email</strong>.
                        </p>
                    </div>
                </header>

                <div class="mt-6">
                    @include('superadmin.partials.import-form')
                </div>
            </section>

            <section id="users" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Usuarios</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Buscar, crear, editar y eliminar usuarios desde el panel.</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="flex w-full max-w-sm items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                            <input id="userSearch" type="text" placeholder="Buscar por nombre o email" class="w-full bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400 dark:text-slate-200 dark:placeholder:text-slate-500" />
                            <button id="searchBtn" class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Buscar</button>
                        </div>
                        <button id="newUserBtn" class="w-full rounded-lg bg-[#AD1133] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8E0E2A] sm:w-auto">
                            Nuevo usuario
                        </button>
                    </div>
                </header>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700 dark:text-slate-200">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:text-slate-400">
                            <tr>
                                <th class="px-3 py-2">Nombre</th>
                                <th class="px-3 py-2">Email</th>
                                <th class="px-3 py-2">Rol</th>
                                <th class="px-3 py-2">Creado</th>
                                <th class="px-3 py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody" class="divide-y divide-slate-200 dark:divide-slate-800">
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Cargando usuarios...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex flex-col items-center justify-between gap-3 sm:flex-row">
                    <div id="paginationInfo" class="text-sm text-slate-600 dark:text-slate-300"></div>
                    <div class="flex items-center gap-2">
                        <button id="prevPage" class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" disabled>Anterior</button>
                        <button id="nextPage" class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" disabled>Siguiente</button>
                    </div>
                </div>
            </section>

            <!-- Modal -->
            <div id="userModal" class="fixed inset-0 hidden items-center justify-center bg-black/40 p-4 z-50">
                <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-xl dark:bg-slate-950">
                    <div class="flex items-start justify-between">
                        <h3 id="modalTitle" class="text-lg font-semibold">Nuevo usuario</h3>
                        <button id="closeModal" class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                            ✕
                        </button>
                    </div>

                    <form id="userForm" class="mt-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Nombre</span>
                                <input id="userName" type="text" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                            </label>

                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Apellido</span>
                                <input id="userApellido" type="text" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                            </label>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Email</span>
                                <input id="userEmail" type="email" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                            </label>

                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Rol</span>
                                <select id="userRole" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                                    <option value="superadmin">Superadmin</option>
                                    <option value="admin">Admin</option>
                                    <option value="alumno">Alumno</option>
                                </select>
                            </label>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Contraseña (opcional)</span>
                                <input id="userPassword" type="password" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                            </label>
                        </div>

                        <div class="flex flex-col items-stretch gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div id="userFormError" class="text-sm text-rose-600 dark:text-rose-300"></div>
                            <div class="flex gap-2">
                                <button type="button" id="cancelUser" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                                    Cancelar
                                </button>
                                <button type="submit" id="saveUser" class="rounded-lg bg-[#AD1133] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8E0E2A]">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sección Alumnos -->
            <section id="alumnos" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Alumnos</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Buscar, crear y editar perfiles completos de alumnos.</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="flex w-full max-w-sm items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                            <input id="alumnoSearch" type="text" placeholder="Buscar por nombre, rut o email" class="w-full bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400 dark:text-slate-200 dark:placeholder:text-slate-500" />
                            <button id="searchAlumnoBtn" class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Buscar</button>
                        </div>
                        <button id="newAlumnoBtn" class="w-full rounded-lg bg-[#AD1133] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8E0E2A] sm:w-auto">
                            Nuevo Alumno
                        </button>
                    </div>
                </header>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700 dark:text-slate-200">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:text-slate-400">
                            <tr>
                                <th class="px-3 py-2">RUT</th>
                                <th class="px-3 py-2">Nombre Completo</th>
                                <th class="px-3 py-2">Email</th>
                                <th class="px-3 py-2">Curso / Nivel</th>
                                <th class="px-3 py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="alumnosTableBody" class="divide-y divide-slate-200 dark:divide-slate-800">
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Cargando alumnos...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex flex-col items-center justify-between gap-3 sm:flex-row">
                    <div id="paginationAlumnoInfo" class="text-sm text-slate-600 dark:text-slate-300"></div>
                    <div class="flex items-center gap-2">
                        <button id="prevAlumnoPage" class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" disabled>Anterior</button>
                        <button id="nextAlumnoPage" class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" disabled>Siguiente</button>
                    </div>
                </div>
            </section>

            <!-- Modal Alumno -->
            <div id="alumnoModal" class="fixed inset-0 hidden items-center justify-center bg-black/40 p-4 z-50 overflow-y-auto">
                <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-xl dark:bg-slate-950 my-auto">
                    <div class="flex items-start justify-between">
                        <h3 id="alumnoModalTitle" class="text-lg font-semibold">Nuevo Alumno</h3>
                        <button id="closeAlumnoModal" class="rounded-full p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                            ✕
                        </button>
                    </div>

                    <form id="alumnoForm" class="mt-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Nombres</span>
                                <input id="alName" type="text" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                            </label>
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Apellidos</span>
                                <input id="alApellido" type="text" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                            </label>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Email</span>
                                <input id="alEmail" type="email" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                            </label>
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">RUT (sin puntos ni guión)</span>
                                <input id="alRut" type="text" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                            </label>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Curso (Ej. 3A)</span>
                                <input id="alCurso" type="text" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                            </label>
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Nivel Educacional</span>
                                <select id="alNivel" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                                    <option value="3">3° Medio</option>
                                    <option value="4">4° Medio</option>
                                </select>
                            </label>
                        </div>
                        <label class="block">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Contraseña (Vacío para mantener)</span>
                            <input id="alPassword" type="password" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                        </label>

                        <div class="flex items-center justify-between">
                            <div id="alumnoFormError" class="text-sm text-rose-600 dark:text-rose-300"></div>
                            <div class="flex gap-2">
                                <button type="button" id="cancelAlumno" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-900">Cancelar</button>
                                <button type="submit" id="saveAlumno" class="rounded-lg bg-[#AD1133] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8E0E2A]">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    @push('scripts')
        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const state = {
                page: 1,
                lastPage: 1,
                query: '',
                currentUser: null,
            };

            const stateAlumno = {
                page: 1,
                lastPage: 1,
                query: '',
                currentUser: null,
            };

            const elements = {
                usersTableBody: document.getElementById('usersTableBody'),
                paginationInfo: document.getElementById('paginationInfo'),
                prevPage: document.getElementById('prevPage'),
                nextPage: document.getElementById('nextPage'),
                searchInput: document.getElementById('userSearch'),
                searchBtn: document.getElementById('searchBtn'),
                newUserBtn: document.getElementById('newUserBtn'),
                userModal: document.getElementById('userModal'),
                modalTitle: document.getElementById('modalTitle'),
                closeModal: document.getElementById('closeModal'),
                userForm: document.getElementById('userForm'),
                userName: document.getElementById('userName'),
                userApellido: document.getElementById('userApellido'),
                userEmail: document.getElementById('userEmail'),
                userRole: document.getElementById('userRole'),
                userPassword: document.getElementById('userPassword'),
                userFormError: document.getElementById('userFormError'),
                cancelUser: document.getElementById('cancelUser'),
                saveUser: document.getElementById('saveUser'),

                // Alumnos
                alumnosTableBody: document.getElementById('alumnosTableBody'),
                paginationAlumnoInfo: document.getElementById('paginationAlumnoInfo'),
                prevAlumnoPage: document.getElementById('prevAlumnoPage'),
                nextAlumnoPage: document.getElementById('nextAlumnoPage'),
                searchInputAlumno: document.getElementById('alumnoSearch'),
                searchAlumnoBtn: document.getElementById('searchAlumnoBtn'),
                newAlumnoBtn: document.getElementById('newAlumnoBtn'),
                alumnoModal: document.getElementById('alumnoModal'),
                alumnoModalTitle: document.getElementById('alumnoModalTitle'),
                closeAlumnoModal: document.getElementById('closeAlumnoModal'),
                alumnoForm: document.getElementById('alumnoForm'),
                alName: document.getElementById('alName'),
                alApellido: document.getElementById('alApellido'),
                alEmail: document.getElementById('alEmail'),
                alRut: document.getElementById('alRut'),
                alCurso: document.getElementById('alCurso'),
                alNivel: document.getElementById('alNivel'),
                alPassword: document.getElementById('alPassword'),
                alumnoFormError: document.getElementById('alumnoFormError'),
                cancelAlumno: document.getElementById('cancelAlumno'),
                saveAlumno: document.getElementById('saveAlumno'),
            };

            function showToast(message, type = 'info') {
                const colorMap = {
                    info: 'text-slate-700 dark:text-slate-200',
                    success: 'text-emerald-700 dark:text-emerald-200',
                    error: 'text-rose-700 dark:text-rose-200',
                };

                const toast = document.createElement('div');
                toast.className = `fixed bottom-6 right-6 z-50 max-w-sm rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-lg ${colorMap[type]}`;
                toast.textContent = message;

                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 4000);
            }

            async function fetchUsers() {
                const params = new URLSearchParams({
                    page: state.page,
                    q: state.query,
                });

                const resp = await fetch(`/superadmin/users?${params.toString()}`);
                if (!resp.ok) throw new Error('Error al cargar usuarios.');

                const data = await resp.json();
                state.lastPage = data.last_page;
                renderUsers(data.data);
                renderPagination(data);
            }

            function renderUsers(users) {
                if (!users.length) {
                    elements.usersTableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    `;
                    return;
                }

                elements.usersTableBody.innerHTML = users
                    .map((user) => {
                        return `
                            <tr>
                                <td class="px-3 py-3 font-medium text-slate-900 dark:text-slate-100">${[user.name, user.apellido].filter(Boolean).join(' ') || '-'}</td>
                                <td class="px-3 py-3">${user.email}</td>
                                <td class="px-3 py-3">${user.rol}</td>
                                <td class="px-3 py-3 text-slate-500 dark:text-slate-400">${new Date(user.created_at).toLocaleString()}</td>
                                <td class="px-3 py-3 flex gap-2">
                                    <button data-user-id="${user.id}" class="edit-user rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                                        Editar
                                    </button>
                                    <button data-user-id="${user.id}" class="delete-user rounded-lg bg-rose-600 px-3 py-1 text-xs font-semibold text-white hover:bg-rose-700">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    })
                    .join('');

                bindTableActions();
            }

            function renderPagination(data) {
                elements.paginationInfo.textContent = `Página ${data.current_page} de ${data.last_page} (${data.total} usuarios)`;
                elements.prevPage.disabled = data.current_page <= 1;
                elements.nextPage.disabled = data.current_page >= data.last_page;
            }

            function bindTableActions() {
                document.querySelectorAll('.edit-user').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const userId = btn.dataset.userId;
                        openEditModal(userId);
                    });
                });

                document.querySelectorAll('.delete-user').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const userId = btn.dataset.userId;
                        deleteUser(userId);
                    });
                });
            }

            async function deleteUser(userId) {
                if (!confirm('¿Eliminar este usuario?')) return;

                try {
                    const resp = await fetch(`/superadmin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    if (!resp.ok) throw new Error('No se pudo eliminar');
                    showToast('Usuario eliminado', 'success');
                    fetchUsers();
                } catch (err) {
                    showToast(err.message, 'error');
                }
            }

            function openModal() {
                elements.userModal.classList.remove('hidden');
                elements.userModal.classList.add('flex');
            }

            function closeModal() {
                elements.userModal.classList.add('hidden');
                elements.userModal.classList.remove('flex');
                elements.userForm.reset();
                elements.userFormError.textContent = '';
                state.currentUser = null;
            }

            function openNewUserModal() {
                state.currentUser = null;
                elements.modalTitle.textContent = 'Nuevo usuario';
                elements.userPassword.required = true;
                openModal();
            }

            function openEditModal(userId) {
                state.currentUser = userId;
                elements.modalTitle.textContent = 'Editar usuario';
                elements.userPassword.required = false;

                fetch(`/superadmin/users?user_id=${userId}`)
                    .then((resp) => resp.json())
                    .then((data) => {
                        const user = data.data?.[0];
                        if (!user) throw new Error('Usuario no encontrado');

                        elements.userName.value = user.name;
                        elements.userApellido.value = user.apellido || '';
                        elements.userEmail.value = user.email;
                        elements.userRole.value = user.rol;
                    })
                    .catch((err) => showToast(err.message, 'error'));

                openModal();
            }

            async function saveUser(event) {
                event.preventDefault();
                elements.userFormError.textContent = '';

                const payload = {
                    name: elements.userName.value.trim(),
                    apellido: elements.userApellido.value.trim(),
                    email: elements.userEmail.value.trim(),
                    rol: elements.userRole.value,
                    password: elements.userPassword.value.trim() || undefined,
                };

                const url = state.currentUser ? `/superadmin/users/${state.currentUser}` : '/superadmin/users';
                const method = state.currentUser ? 'PATCH' : 'POST';

                try {
                    const resp = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!resp.ok) {
                        const json = await resp.json();
                        throw new Error(json.message || 'Error guardando usuario');
                    }

                    closeModal();
                    showToast('Usuario guardado', 'success');
                    fetchUsers();
                } catch (err) {
                    elements.userFormError.textContent = err.message;
                }
            }

            elements.searchBtn.addEventListener('click', () => {
                state.query = elements.searchInput.value.trim();
                state.page = 1;
                fetchUsers();
            });

            elements.prevPage.addEventListener('click', () => {
                if (state.page > 1) {
                    state.page -= 1;
                    fetchUsers();
                }
            });

            elements.nextPage.addEventListener('click', () => {
                if (state.page < state.lastPage) {
                    state.page += 1;
                    fetchUsers();
                }
            });

            elements.newUserBtn.addEventListener('click', openNewUserModal);
            elements.closeModal.addEventListener('click', closeModal);
            elements.cancelUser.addEventListener('click', closeModal);
            elements.userForm.addEventListener('submit', saveUser);

            // ========================
            // ===== LÓGICA ALUMNOS ===
            // ========================
            async function fetchAlumnos() {
                const params = new URLSearchParams({
                    page: stateAlumno.page,
                    q: stateAlumno.query,
                    rol: 'alumno'
                });

                const resp = await fetch(`/superadmin/users?${params.toString()}`);
                if (!resp.ok) throw new Error('Error al cargar alumnos.');

                const data = await resp.json();
                stateAlumno.lastPage = data.last_page;
                renderAlumnos(data.data);
                renderAlumnoPagination(data);
            }

            function renderAlumnos(alumnos) {
                if (!alumnos.length) {
                    elements.alumnosTableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                No se encontraron alumnos.
                            </td>
                        </tr>
                    `;
                    return;
                }

                elements.alumnosTableBody.innerHTML = alumnos
                    .map((user) => {
                        const al = user.alumno || {};
                        return `
                            <tr>
                                <td class="px-3 py-3 font-medium text-slate-900 dark:text-slate-100">${al.rut || '-'}</td>
                                <td class="px-3 py-3">${[user.name, user.apellido].filter(Boolean).join(' ') || '-'}</td>
                                <td class="px-3 py-3">${user.email}</td>
                                <td class="px-3 py-3 text-slate-500 dark:text-slate-400">
                                    <span class="block">${al.curso || '-'}</span>
                                    <span class="text-xs">${al.nivel_actual ? `${al.nivel_actual}° Medio` : '-'}</span>
                                </td>
                                <td class="px-3 py-3 flex gap-2">
                                    <button data-alumno-id="${user.id}" class="edit-alumno rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                                        Editar
                                    </button>
                                    <button data-alumno-id="${user.id}" class="delete-alumno rounded-lg bg-rose-600 px-3 py-1 text-xs font-semibold text-white hover:bg-rose-700">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    })
                    .join('');

                bindAlumnoActions();
            }

            function renderAlumnoPagination(data) {
                elements.paginationAlumnoInfo.textContent = `Página ${data.current_page} de ${data.last_page} (${data.total} alumnos)`;
                elements.prevAlumnoPage.disabled = data.current_page <= 1;
                elements.nextAlumnoPage.disabled = data.current_page >= data.last_page;
            }

            function bindAlumnoActions() {
                document.querySelectorAll('.edit-alumno').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        openEditAlumnoModal(btn.dataset.alumnoId);
                    });
                });
                document.querySelectorAll('.delete-alumno').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        deleteUser(btn.dataset.alumnoId).then(() => fetchAlumnos());
                    });
                });
            }

            function openAlumnoModal() {
                elements.alumnoModal.classList.remove('hidden');
                elements.alumnoModal.classList.add('flex');
            }
            function closeAlumnoModal() {
                elements.alumnoModal.classList.add('hidden');
                elements.alumnoModal.classList.remove('flex');
                elements.alumnoForm.reset();
                elements.alumnoFormError.textContent = '';
                stateAlumno.currentUser = null;
            }

            function openNewAlumnoModal() {
                stateAlumno.currentUser = null;
                elements.alumnoModalTitle.textContent = 'Nuevo Alumno';
                elements.alPassword.required = true;
                openAlumnoModal();
            }

            function openEditAlumnoModal(userId) {
                stateAlumno.currentUser = userId;
                elements.alumnoModalTitle.textContent = 'Editar Alumno';
                elements.alPassword.required = false;

                fetch(`/superadmin/users?user_id=${userId}`)
                    .then((resp) => resp.json())
                    .then((data) => {
                        const user = data.data?.[0];
                        if (!user) throw new Error('Alumno no encontrado');

                        elements.alName.value = user.name;
                        elements.alApellido.value = user.apellido || '';
                        elements.alEmail.value = user.email;
                        
                        const al = user.alumno || {};
                        elements.alRut.value = al.rut || '';
                        elements.alCurso.value = al.curso || '';
                        elements.alNivel.value = al.nivel_actual || '3';
                    })
                    .catch((err) => showToast(err.message, 'error'));

                openAlumnoModal();
            }

            async function saveAlumno(event) {
                event.preventDefault();
                elements.alumnoFormError.textContent = '';

                const payload = {
                    name: elements.alName.value.trim(),
                    apellido: elements.alApellido.value.trim(),
                    email: elements.alEmail.value.trim(),
                    rol: 'alumno', // Force rol
                    rut: elements.alRut.value.trim(),
                    curso: elements.alCurso.value.trim(),
                    nivel_actual: elements.alNivel.value,
                    password: elements.alPassword.value.trim() || undefined,
                };

                const url = stateAlumno.currentUser ? `/superadmin/users/${stateAlumno.currentUser}` : '/superadmin/users';
                const method = stateAlumno.currentUser ? 'PATCH' : 'POST';

                try {
                    const resp = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!resp.ok) {
                        const json = await resp.json();
                        throw new Error(json.message || 'Error guardando alumno');
                    }

                    closeAlumnoModal();
                    showToast('Alumno guardado correctamente', 'success');
                    fetchAlumnos();
                    fetchUsers(); // Update the main table too if needed
                } catch (err) {
                    elements.alumnoFormError.textContent = err.message;
                }
            }

            elements.searchAlumnoBtn.addEventListener('click', () => {
                stateAlumno.query = elements.searchInputAlumno.value.trim();
                stateAlumno.page = 1;
                fetchAlumnos();
            });

            elements.prevAlumnoPage.addEventListener('click', () => {
                if (stateAlumno.page > 1) {
                    stateAlumno.page -= 1;
                    fetchAlumnos();
                }
            });

            elements.nextAlumnoPage.addEventListener('click', () => {
                if (stateAlumno.page < stateAlumno.lastPage) {
                    stateAlumno.page += 1;
                    fetchAlumnos();
                }
            });

            elements.newAlumnoBtn.addEventListener('click', openNewAlumnoModal);
            elements.closeAlumnoModal.addEventListener('click', closeAlumnoModal);
            elements.cancelAlumno.addEventListener('click', closeAlumnoModal);
            elements.alumnoForm.addEventListener('submit', saveAlumno);

            fetchUsers();
            fetchAlumnos();
        </script>
    @endpush
@endsection
