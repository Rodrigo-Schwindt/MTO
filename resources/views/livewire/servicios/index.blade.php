@section('page-title', 'Servicios')

@extends('layouts.admin')

@section('content')
<div class="space-y-6 admin-fade">

    {{-- Barra de acciones --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-slate-500">
            <span id="totalCount">{{ $services->total() }}</span> registros encontrados
        </p>
        <a href="{{ route('servicios.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nuevo servicio
        </a>
    </div>

    <div id="alertContainer"></div>

    {{-- Banner de sección --}}
    <div class="admin-card">
        <div class="flex flex-col lg:flex-row lg:items-start gap-5">
            <div class="flex-1">
                <p class="text-sm font-semibold text-slate-700 mb-1">Banner de la sección</p>
                <p class="text-xs text-slate-400 mb-4">Imagen de cabecera pública de Servicios.</p>
                <div class="flex flex-wrap gap-2">
                    <label class="btn-secondary text-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Seleccionar imagen
                        <input id="bannerInput" type="file" accept=".jpg,.jpeg,.png,.webp,.svg" class="hidden">
                    </label>
                    <button id="saveBanner" type="button" class="btn-primary hidden">Guardar banner</button>
                    @if($pageData->image_banner)
                        <button id="removeBanner" type="button" class="btn-danger">Eliminar</button>
                    @endif
                </div>
                <div class="upload-hint">
                    <strong>Formato:</strong> JPG, PNG, WebP, SVG ·
                    <strong>Tamaño:</strong> 1440×420px ·
                    <strong>Peso:</strong> máx. 3 MB
                </div>
                <p id="bannerError" class="hidden mt-2 text-sm text-red-600"></p>
            </div>

            <div class="w-full lg:w-80 h-40 rounded-lg border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center shrink-0">
                @if($pageData->image_banner)
                    <img id="bannerPreview" src="{{ Storage::url($pageData->image_banner) }}" class="w-full h-full object-cover" alt="Banner servicios">
                    <span id="bannerPlaceholder" class="hidden text-slate-400 text-sm">Sin banner</span>
                @else
                    <img id="bannerPreview" src="" class="hidden w-full h-full object-cover" alt="Banner servicios">
                    <span id="bannerPlaceholder" class="text-slate-400 text-sm">Sin banner</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Buscador --}}
    <div class="admin-card !p-4">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" id="searchInput" value="{{ $search }}"
                   placeholder="Buscar por título, descripción u orden..."
                   class="w-full pl-9 pr-9 py-2 border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#52A028] text-slate-700 text-sm transition">
            <button id="clearSearch" type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 {{ $search ? '' : 'hidden' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <p class="text-xs text-slate-400 text-center">
        Página <span id="currentPage">{{ $services->currentPage() }}</span> de <span id="lastPage">{{ $services->lastPage() }}</span> ·
        Mostrando <span id="firstItem">{{ $services->firstItem() }}</span>–<span id="lastItem">{{ $services->lastItem() }}</span> de <span id="totalItems">{{ $services->total() }}</span>
    </p>

    {{-- Tabla --}}
    <div class="overflow-x-auto admin-card !p-0">
        <table class="w-full admin-table">
            <thead>
                <tr>
                    <th class="text-center">Orden</th>
                    <th>Imagen</th>
                    <th class="text-left">Título / Descripción</th>
                    <th class="text-center">Visible</th>
                    <th class="text-center">Destacado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="servicesTable">
                @include('livewire.servicios.partials.table', ['services' => $services])
            </tbody>
        </table>
    </div>

    <div id="pagination">
        @include('livewire.servicios.partials.pagination', ['services' => $services])
    </div>
</div>

<style>
.line-clamp-2 { display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput   = document.getElementById('searchInput');
    const clearSearch   = document.getElementById('clearSearch');
    const bannerInput   = document.getElementById('bannerInput');
    const saveBanner    = document.getElementById('saveBanner');
    const removeBanner  = document.getElementById('removeBanner');
    const bannerPreview = document.getElementById('bannerPreview');
    const bannerPlaceholder = document.getElementById('bannerPlaceholder');
    const bannerError   = document.getElementById('bannerError');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        clearSearch.classList.toggle('hidden', !this.value);
        searchTimeout = setTimeout(loadServices, 300);
    });

    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        this.classList.add('hidden');
        loadServices();
    });

    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.pagination a');
        if (paginationLink) {
            e.preventDefault();
            loadServices(new URL(paginationLink.href).searchParams.get('page') || 1);
        }

        const deleteButton = e.target.closest('.delete-btn');
        if (deleteButton && confirm('¿Eliminar este servicio?')) {
            fetch(`{{ url('/admin/servicios') }}/${deleteButton.dataset.id}`, {
                method: 'DELETE', headers: requestHeaders()
            })
            .then(r => r.json())
            .then(data => { if (data.success) { showAlert(data.message, 'success'); loadServices(); } });
        }

        if (e.target.closest('.toggle-visible-btn')) toggleFlag(e.target.closest('.toggle-visible-btn').dataset.id, 'visible');
        if (e.target.closest('.toggle-destacado-btn')) toggleFlag(e.target.closest('.toggle-destacado-btn').dataset.id, 'destacado');
    });

    if (bannerInput) {
        bannerInput.addEventListener('change', function() {
            bannerError.classList.add('hidden');
            const file = this.files[0];
            saveBanner.classList.toggle('hidden', !file);
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => { bannerPreview.src = e.target.result; bannerPreview.classList.remove('hidden'); bannerPlaceholder.classList.add('hidden'); };
            reader.readAsDataURL(file);
        });
    }

    if (saveBanner) {
        saveBanner.addEventListener('click', function() {
            const file = bannerInput.files[0];
            if (!file) return;
            const formData = new FormData();
            formData.append('image_banner', file);
            fetch('{{ route('servicios.banner.save') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.url) {
                    bannerPreview.src = data.url; bannerPreview.classList.remove('hidden'); bannerPlaceholder.classList.add('hidden');
                    bannerInput.value = ''; saveBanner.classList.add('hidden');
                    showAlert(data.success || 'Banner actualizado', 'success');
                } else {
                    bannerError.textContent = data.message || data.error || 'Error al guardar el banner';
                    bannerError.classList.remove('hidden');
                }
            });
        });
    }

    if (removeBanner) {
        removeBanner.addEventListener('click', function() {
            if (!confirm('¿Eliminar banner?')) return;
            fetch('{{ route('servicios.banner.remove') }}', { method: 'DELETE', headers: requestHeaders() })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    bannerPreview.src = ''; bannerPreview.classList.add('hidden'); bannerPlaceholder.classList.remove('hidden');
                    removeBanner.remove(); showAlert(data.success, 'success');
                }
            });
        });
    }

    function loadServices(page = 1) {
        const search = searchInput.value;
        fetch(`{{ route('servicios.index') }}?page=${page}&search=${encodeURIComponent(search)}`, { headers: requestHeaders() })
        .then(r => r.json())
        .then(data => {
            document.getElementById('servicesTable').innerHTML = data.html;
            document.getElementById('pagination').innerHTML = data.pagination;
            document.getElementById('totalCount').textContent  = data.total || 0;
            document.getElementById('currentPage').textContent = data.currentPage || 1;
            document.getElementById('lastPage').textContent    = data.lastPage || 1;
            document.getElementById('firstItem').textContent   = data.firstItem || 0;
            document.getElementById('lastItem').textContent    = data.lastItem || 0;
            document.getElementById('totalItems').textContent  = data.total || 0;
        });
    }

    function toggleFlag(id, flag) {
        fetch(`{{ url('/admin/servicios') }}/${id}/${flag}`, { method: 'POST', headers: requestHeaders() })
        .then(r => r.json()).then(data => { if (data.success) loadServices(); });
    }

    function requestHeaders() {
        return { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };
    }

    function showAlert(message, type = 'info') {
        const alertContainer = document.getElementById('alertContainer');
        alertContainer.innerHTML = `<div class="${type === 'success' ? 'admin-alert-ok' : 'admin-alert-err'}">${message}</div>`;
        setTimeout(() => alertContainer.innerHTML = '', 4000);
    }
});
</script>
@endsection
