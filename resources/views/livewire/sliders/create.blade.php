@section('page-title', 'Sliders / Crear')

@extends('layouts.admin')

@section('content')
<div class="max-w-3xl space-y-6 admin-fade">

    <div class="flex items-center justify-end">
        <a href="{{ route('sliders.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
    </div>

    <form id="sliderForm" enctype="multipart/form-data" class="admin-card space-y-6">
        @csrf

        {{-- Archivo --}}
        <div>
            <label for="image" class="block text-sm font-medium text-slate-800 mb-2">Imagen o video <span class="text-red-500">*</span></label>

            <div id="filePreview" class="mb-3 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 hidden p-3">
                <img id="imagePreview" src="" class="w-full h-48 object-cover rounded-md hidden" alt="">
                <video id="videoPreview" class="w-full h-48 object-cover rounded-md hidden" controls></video>
                <div class="mt-2 text-xs text-slate-600 space-y-0.5">
                    <p><strong>Archivo:</strong> <span id="fileName"></span></p>
                    <p><strong>Tamaño:</strong> <span id="fileSize"></span> MB · <strong>Tipo:</strong> <span id="fileType"></span></p>
                </div>
            </div>

            <input type="file" id="image" name="image"
                   accept=".jpg,.jpeg,.png,.gif,.svg,.mp4,.webm,.ogg,.mov,.avi"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white file:bg-[#52A028] file:text-white file:px-4 file:py-1.5 file:rounded-md file:border-0 file:cursor-pointer file:mr-3 cursor-pointer focus:ring-2 focus:ring-[#52A028] focus:outline-none">
            <p id="image-error" class="mt-2 text-red-600 text-sm hidden"></p>

            <div id="fileLoading" class="mt-2 flex items-center gap-2 text-sm text-slate-500 hidden">
                <div class="h-4 w-4 border-2 border-[#52A028] border-t-transparent rounded-full animate-spin"></div>
                Procesando archivo…
            </div>

            <div class="upload-hint">
                <strong>Imagen:</strong> JPG, PNG, WebP, SVG — 1360×670px, máx. 5 MB ·
                <strong>Video:</strong> MP4, WebM, OGG, MOV — máx. 100 MB
            </div>
        </div>

        {{-- Título --}}
        <div>
            <label for="title" class="block text-sm font-medium text-slate-800 mb-2">Título</label>
            <input type="text" id="title" name="title" placeholder="Ingresá el título" class="admin-input">
            <p id="title-error" class="mt-1 text-red-600 text-sm hidden"></p>
        </div>

        {{-- Descripción --}}
        <div>
            <label for="description" class="block text-sm font-medium text-slate-800 mb-2">Descripción</label>
            <textarea id="description" name="description" rows="3" placeholder="Descripción opcional" class="admin-input resize-none"></textarea>
            <p id="description-error" class="mt-1 text-red-600 text-sm hidden"></p>
        </div>

        {{-- Botón --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="button_text" class="block text-sm font-medium text-slate-800 mb-2">Texto del botón</label>
                <input type="text" id="button_text" name="button_text" placeholder="Ej: Ver productos" class="admin-input">
                <p id="button_text-error" class="mt-1 text-red-600 text-sm hidden"></p>
            </div>
            <div>
                <label for="button_target" class="block text-sm font-medium text-slate-800 mb-2">Apertura del link</label>
                <select id="button_target" name="button_target" class="admin-input">
                    <option value="_self" selected>Misma pestaña</option>
                    <option value="_blank">Nueva pestaña</option>
                </select>
            </div>
        </div>

        <div>
            <label for="url" class="block text-sm font-medium text-slate-800 mb-2">Link del botón</label>
            <input type="text" id="url" name="url" placeholder="Ej: /servicios o https://sitio.com" class="admin-input">
            <p class="text-xs text-slate-400 mt-1">Acepta links internos (/ruta) y externos (https://…).</p>
            <p id="url-error" class="mt-1 text-red-600 text-sm hidden"></p>
        </div>

        {{-- Orden --}}
        <div>
            <label for="orden" class="block text-sm font-medium text-slate-800 mb-2">Orden <span class="text-red-500">*</span></label>
            <input type="text" id="orden" name="orden" placeholder="AA, AB, AC…" class="admin-input font-mono uppercase tracking-wider">
            <p class="text-xs text-slate-400 mt-1">Orden alfabético: AA → AB → AC…</p>
            <p id="orden-error" class="mt-1 text-red-600 text-sm hidden"></p>
        </div>

        <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
            <a href="{{ route('sliders.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" id="submitBtn" class="btn-primary">
                <span id="submitText">Crear slider</span>
                <span id="submitLoading" class="hidden items-center gap-2">
                    <div class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    Creando…
                </span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let editorDescription;
    if (document.getElementById('description') && window.CKEDITOR) editorDescription = CKEDITOR.replace('description');

    const form          = document.getElementById('sliderForm');
    const imageInput    = document.getElementById('image');
    const filePreview   = document.getElementById('filePreview');
    const imagePreview  = document.getElementById('imagePreview');
    const videoPreview  = document.getElementById('videoPreview');
    const fileName      = document.getElementById('fileName');
    const fileSize      = document.getElementById('fileSize');
    const fileType      = document.getElementById('fileType');
    const fileLoading   = document.getElementById('fileLoading');
    const submitBtn     = document.getElementById('submitBtn');
    const submitText    = document.getElementById('submitText');
    const submitLoading = document.getElementById('submitLoading');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) { filePreview.classList.add('hidden'); return; }
        fileLoading.classList.remove('hidden');
        const reader = new FileReader();
        reader.onload = function(e) {
            fileLoading.classList.add('hidden');
            if (file.type.startsWith('video/')) {
                videoPreview.src = e.target.result; videoPreview.classList.remove('hidden'); imagePreview.classList.add('hidden');
            } else {
                imagePreview.src = e.target.result; imagePreview.classList.remove('hidden'); videoPreview.classList.add('hidden');
            }
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024 / 1024).toFixed(2);
            fileType.textContent = file.type;
            filePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();
        if (editorDescription) editorDescription.updateElement();
        const formData = new FormData(form);
        if (editorDescription) formData.set('description', editorDescription.getData());
        submitBtn.disabled = true; submitText.classList.add('hidden'); submitLoading.classList.remove('hidden');
        fetch('{{ route("sliders.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => window.location.href = data.redirect, 1000);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const el = document.getElementById(`${field}-error`);
                        if (el) { el.textContent = Array.isArray(data.errors[field]) ? data.errors[field][0] : data.errors[field]; el.classList.remove('hidden'); }
                    });
                } else {
                    showAlert(data.message || 'Error al crear el slider', 'error');
                }
            }
        })
        .catch(() => showAlert('Error al crear el slider', 'error'))
        .finally(() => { submitBtn.disabled = false; submitText.classList.remove('hidden'); submitLoading.classList.add('hidden'); });
    });

    function clearErrors() {
        document.querySelectorAll('[id$="-error"]').forEach(el => { el.classList.add('hidden'); el.textContent = ''; });
    }

    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = type === 'success' ? 'admin-alert-ok' : 'admin-alert-err';
        alertDiv.textContent = message;
        form.parentElement.insertBefore(alertDiv, form);
        setTimeout(() => alertDiv.remove(), 4000);
    }
});
</script>
@endsection
