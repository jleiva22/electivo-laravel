<div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-6 dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col items-center justify-center gap-4">
        <div id="dropArea" class="relative flex w-full max-w-lg flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-slate-300 bg-white p-8 text-center shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <p class="text-sm text-slate-600 dark:text-slate-300">Arrastra tu archivo aquí o haz clic para seleccionar</p>
            <input id="fileInput" type="file" accept=".csv" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" />
            <p class="text-xs text-slate-400">El archivo debe ser CSV con cabeceras o en el orden correcto.</p>
        </div>

        <button id="uploadBtn" class="w-full max-w-xs rounded-lg bg-[#AD1133] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8E0E2A] disabled:cursor-not-allowed disabled:opacity-60" disabled>
            Importar alumnos
        </button>

        <p id="status" class="text-sm text-slate-600 dark:text-slate-300"></p>
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

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const resp = await fetch('/superadmin/import', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                        },
                        body: form,
                    });

                    const json = await resp.json();
                    console.log('Import response:', { status: resp.status, ok: resp.ok, body: json });
                    if (json.skipped_details && json.skipped_details.length) {
                        console.table(json.skipped_details);
                    }

                    if (!resp.ok) {
                        throw new Error(json.message || 'Error al importar');
                    }

                    setStatus(`Importados: ${json.created}. Omitidos: ${json.skipped}.`, 'success');
                } catch (err) {
                    console.error('Import failed:', err);
                    setStatus(err.message, 'error');
                }
            });
        </script>
    @endpush
</div>
