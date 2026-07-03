@section('page-title', 'Novedades')

@extends('layouts.admin')

@section('content')
<div class="space-y-6 admin-fade">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-slate-500">
            <span id="total-count">{{ $novedades->total() }}</span> registros encontrados
        </p>
        <a href="{{ route('novedades.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva novedad
        </a>
    </div>

    <div id="alert-success" class="hidden admin-alert-ok"></div>

    {{-- Banner --}}
    <div class="admin-card">
        <div class="flex flex-col lg:flex-row lg:items-start gap-5">
            <div class="w-full lg:w-72 h-40 rounded-lg border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center shrink-0">
                @if($banner && $banner->image_banner)
                    <img id="banner-preview" src="{{ Storage::url($banner->image_banner) }}" alt="Banner" class="h-full w-full object-cover">
                    <div id="banner-placeholder" class="hidden flex h-full w-full items-center justify-center text-sm text-slate-400">Sin banner</div>
                @else
                    <img id="banner-preview" src="" alt="Banner" class="hidden h-full w-full object-cover">
                    <div id="banner-placeholder" class="flex h-full w-full items-center justify-center text-sm text-slate-400">Sin banner</div>
                @endif
            </div>

            <div class="flex-1">
                <p class="text-sm font-semibold text-slate-700 mb-1">Banner de la página pública</p>
                <p class="text-xs text-slate-400 mb-4">Imagen de cabecera en la sección de novedades.</p>

                <div class="flex flex-wrap gap-2">
                    <label for="file-image_banner" class="btn-secondary text-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Seleccionar imagen
                    </label>
                    <input id="file-image_banner" type="file" accept="image/*" class="hidden">
                    <button type="button" id="btn-save-banner" class="btn-primary hidden">Guardar banner</button>
                    <button type="button" id="btn-remove-banner" class="{{ $banner && $banner->image_banner ? '' : 'hidden' }} btn-danger">Eliminar</button>
                </div>

                <div class="upload-hint">
                    <strong>Formato:</strong> JPG, PNG, WebP ·
                    <strong>Tamaño:</strong> 1440×420px ·
                    <strong>Peso:</strong> máx. 3 MB
                </div>
                <p id="banner-error" class="hidden mt-2 text-sm text-red-600"></p>
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
            <input type="text" id="search-input" value="{{ $search }}" placeholder="Buscar por título o descripción…"
                   class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#52A028] text-slate-700 text-sm transition">
        </div>
    </div>

    <p class="text-xs text-slate-400 text-center" id="pagination-info">
        Página {{ $novedades->currentPage() }} de {{ $novedades->lastPage() }} ·
        Mostrando {{ $novedades->firstItem() }}–{{ $novedades->lastItem() }} de {{ $novedades->total() }}
    </p>

    <div id="ajax-wrapper">
        <div class="overflow-x-auto admin-card !p-0">
            <table class="w-full admin-table">
                <thead>
                    <tr>
                        <th class="text-center w-20">Orden</th>
                        <th class="text-left">Título</th>
                        <th class="text-center w-20">Imagen</th>
                        <th class="text-center w-28">Destacado</th>
                        <th class="text-center w-48">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($novedades as $nov)
                        <tr>
                            <td class="text-center font-mono uppercase">{{ $nov->orden }}</td>
                            <td class="font-medium text-slate-900">{{ $nov->title }}</td>
                            <td class="text-center">
                                @if($nov->image)
                                    <img src="{{ Storage::url($nov->image) }}" class="h-10 w-10 object-cover rounded-md mx-auto border border-slate-200">
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button data-id="{{ $nov->id }}" class="btn-toggle-destacado text-xl hover:scale-110 transition cursor-pointer">
                                    {{ $nov->destacado ? '⭐' : '—' }}
                                </button>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('novedades.edit', $nov->id) }}" class="text-slate-400 hover:text-[#52A028] transition" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                    <button data-id="{{ $nov->id }}" class="btn-delete text-slate-400 hover:text-red-600 transition" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H4"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($novedades->count() === 0)
                        <tr><td colspan="5" class="py-10 text-center text-slate-400 text-sm">No hay novedades disponibles.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="pt-4 flex justify-center">
            @if($novedades->lastPage() > 1)
                <div class="flex gap-1.5">
                    @for($i = 1; $i <= $novedades->lastPage(); $i++)
                        <a href="#" data-page="{{ $i }}"
                           class="ajax-page px-3 py-1.5 border rounded-lg text-sm font-medium transition
                                  {{ $i == $novedades->currentPage() ? 'bg-[#52A028] text-white border-[#52A028]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}">
                            {{ $i }}
                        </a>
                    @endfor
                </div>
            @endif
        </div>
    </div>
</div>

<script>
(function() {
    if (window.__novedadesAdminReady) return;
    window.__novedadesAdminReady = true;

    const searchInput      = document.getElementById('search-input');
    const alertBox         = document.getElementById('alert-success');
    const bannerInput      = document.getElementById('file-image_banner');
    const bannerPreview    = document.getElementById('banner-preview');
    const bannerPlaceholder= document.getElementById('banner-placeholder');
    const btnSaveBanner    = document.getElementById('btn-save-banner');
    const btnRemoveBanner  = document.getElementById('btn-remove-banner');
    const bannerError      = document.getElementById('banner-error');
    let currentPage = {{ $novedades->currentPage() }};
    let searchTimeout;

    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentPage = 1; loadTable(currentPage); }, 300);
    });

    document.addEventListener('click', (e) => {
        const pageLink = e.target.closest('.ajax-page');
        if (pageLink) { e.preventDefault(); currentPage = parseInt(pageLink.dataset.page); loadTable(currentPage); }

        const del = e.target.closest('.btn-delete');
        if (del && confirm('¿Eliminar novedad?')) deleteNovedad(del.dataset.id);

        const tog = e.target.closest('.btn-toggle-destacado');
        if (tog) toggleDestacado(tog.dataset.id);
    });

    bannerInput?.addEventListener('change', () => {
        bannerError.classList.add('hidden');
        const file = bannerInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = ev => { bannerPreview.src = ev.target.result; bannerPreview.classList.remove('hidden'); bannerPlaceholder.classList.add('hidden'); };
            reader.readAsDataURL(file);
            btnSaveBanner.classList.remove('hidden');
        } else btnSaveBanner.classList.add('hidden');
    });

    btnSaveBanner?.addEventListener('click', () => {
        const file = bannerInput.files[0];
        if (!file) return;
        const formData = new FormData();
        formData.append('image_banner', file);
        btnSaveBanner.disabled = true; btnSaveBanner.textContent = 'Guardando…';
        fetch('{{ route('novedades.banner.save') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: formData
        })
        .then(async r => { const d = await r.json().catch(() => ({})); if (!r.ok) throw d; return d; })
        .then(data => {
            if (data.errors || data.error) {
                bannerError.textContent = data.errors?.image_banner?.[0] || data.error || 'Error al guardar el banner';
                bannerError.classList.remove('hidden');
            } else {
                showSuccess(data.success || 'Banner actualizado');
                if (data.url) { bannerPreview.src = data.url; bannerPreview.classList.remove('hidden'); bannerPlaceholder.classList.add('hidden'); btnRemoveBanner?.classList.remove('hidden'); }
                bannerInput.value = ''; btnSaveBanner.classList.add('hidden');
            }
        })
        .catch(err => { bannerError.textContent = err?.errors?.image_banner?.[0] || err?.error || 'No se pudo subir el banner'; bannerError.classList.remove('hidden'); })
        .finally(() => { btnSaveBanner.disabled = false; btnSaveBanner.textContent = 'Guardar banner'; });
    });

    btnRemoveBanner?.addEventListener('click', () => {
        if (!confirm('¿Eliminar banner?')) return;
        fetch('{{ route('novedades.banner.remove') }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => { if (data.success) { showSuccess(data.success); bannerPreview.src = ''; bannerPreview.classList.add('hidden'); bannerPlaceholder.classList.remove('hidden'); btnRemoveBanner.classList.add('hidden'); } });
    });

    function loadTable(page = 1) {
        const search = searchInput ? searchInput.value : '';
        fetch(`{{ route('novedades.index') }}?page=${page}&search=${encodeURIComponent(search)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (data.html) {
                document.getElementById('ajax-wrapper').innerHTML = data.html;
                const totalCount = document.getElementById('total-count');
                if (totalCount) totalCount.innerText = data.total || 0;
                const info = document.getElementById('pagination-info');
                if (info && data.total > 0) info.innerText = `Página ${page} de ${data.pages} · Mostrando ${data.from || 0}–${data.to || 0} de ${data.total}`;
            }
        });
    }

    function deleteNovedad(id) {
        fetch(`{{ url('/admin/novedades') }}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => { if (data.success) { showSuccess(data.success); loadTable(currentPage); } });
    }

    function toggleDestacado(id) {
        fetch(`{{ url('/admin/novedades') }}/${id}/destacado`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => { if (data.success) { showSuccess(data.success); loadTable(currentPage); } });
    }

    function showSuccess(msg) {
        if (!alertBox) return;
        alertBox.innerText = msg; alertBox.classList.remove('hidden');
        setTimeout(() => alertBox.classList.add('hidden'), 2500);
    }
})();
</script>
@endsection
