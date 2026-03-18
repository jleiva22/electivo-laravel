@extends('layouts.app')

@section('title', 'Superadmin - Importar Alumnos')

@section('content')
    <div class="flex flex-col gap-6">
        <header>
            <h1 class="text-2xl font-semibold">Importar alumnos desde CSV</h1>
            <p class="mt-1 text-sm text-slate-700 dark:text-slate-300">
                Arrastra o selecciona un archivo CSV con los campos: <strong>N° de lista, Run, Apellido paterno, Apellido materno, Nombres.</strong>
            </p>
        </header>

        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
            <div id="dropArea" class="relative mx-auto flex max-w-lg flex-col items-center justify-center gap-4 rounded-xl border-2 border-dashed border-slate-300 p-10">
                <p class="text-sm text-slate-600">Arrastra tu archivo aquí o haz clic para seleccionar.</p>
                <input id="fileInput" type="file" accept=".csv" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" />
                <p class="text-xs text-slate-400">El archivo debe ser CSV con cabeceras o en el orden correcto.</p>
            </div>

            <div class="mt-6 flex flex-col items-center gap-3">
                <button id="uploadBtn" class="w-full max-w-xs rounded-lg bg-[#AD1133] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8E0E2A] disabled:cursor-not-allowed disabled:opacity-60" disabled>
                    Importar alumnos
                </button>
                <p id="status" class="text-sm text-slate-600"></p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const dropArea = document.getElementById('dropArea');
            const fileInput = document.getElementById('fileInput');
            const uploadBtn = document.getElementById('uploadBtn');
            const statusEl = document.getElementById('status');
            let selectedFile = null;

            function setStatus(message, type = 'info') {
                const colors = {
                    info: 'text-slate-600',
                    success: 'text-emerald-700',
                    error: 'text-rose-600',
                };
                statusEl.textContent = message;
                statusEl.className = `text-sm ${colors[type]}`;
            }

            function updateState() {
                uploadBtn.disabled = !selectedFile;
            }

            function handleFile(file) {
                selectedFile = file;
                setStatus(`Archivo listo: ${file.name}`);
                updateState();
            }

            dropArea.addEventListener('dragover', (event) => {
                event.preventDefault();
                dropArea.classList.add('border-emerald-500', 'bg-emerald-50');
            });

            dropArea.addEventListener('dragleave', () => {
                dropArea.classList.remove('border-emerald-500', 'bg-emerald-50');
            });

            dropArea.addEventListener('drop', (event) => {
                event.preventDefault();
                dropArea.classList.remove('border-emerald-500', 'bg-emerald-50');
                const file = event.dataTransfer.files[0];
                if (file) handleFile(file);
            });

            fileInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) handleFile(file);
            });

            uploadBtn.addEventListener('click', async () => {
                if (!selectedFile) return;

                setStatus('Importando...', 'info');

                const form = new FormData();
                form.append('file', selectedFile);

                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const resp = await fetch('/superadmin/import', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                        },
                        body: form,
                    });

                    if (!resp.ok) {
                        const json = await resp.json();
                        throw new Error(json.message || 'Error al importar');
                    }

                    const json = await resp.json();
                    setStatus(`Importados: ${json.created}. Omitidos: ${json.skipped}.`, 'success');
                } catch (err) {
                    setStatus(err.message, 'error');
                }
            });
        </script>
    @endpush
@endsection
