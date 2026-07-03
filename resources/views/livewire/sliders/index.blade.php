@section('page-title', 'Sliders')

@extends('layouts.admin')

@section('content')
<div class="space-y-6 admin-fade">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-slate-500">
            <span id="totalCount">{{ $sliders->total() }}</span> registros encontrados
        </p>
        <a href="{{ route('sliders.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nuevo slider
        </a>
    </div>

    <div id="alertContainer"></div>

    <div class="admin-card !p-4">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" id="searchInput" placeholder="Buscar por título u orden…"
                   class="w-full pl-9 pr-9 py-2 border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#52A028] text-slate-700 text-sm transition">
            <button id="clearSearch" type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 hidden">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <p id="searchResults" class="mt-2 text-xs text-slate-500 hidden"></p>
    </div>

    <p class="text-xs text-slate-400 text-center">
        Página <span id="currentPage">{{ $sliders->currentPage() }}</span> de <span id="lastPage">{{ $sliders->lastPage() }}</span> ·
        Mostrando <span id="firstItem">{{ $sliders->firstItem() }}</span>–<span id="lastItem">{{ $sliders->lastItem() }}</span> de <span id="totalItems">{{ $sliders->total() }}</span>
    </p>

    <div class="overflow-x-auto admin-card !p-0">
        <table class="w-full admin-table">
            <thead>
                <tr>
                    <th class="text-center cursor-pointer hover:text-slate-700 sort-header" data-sort="orden">Orden</th>
                    <th>Archivo</th>
                    <th class="text-left cursor-pointer hover:text-slate-700 sort-header" data-sort="title">Título / Descripción</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="slidersTable">
                @include('livewire.sliders.partials.table', ['sliders' => $sliders])
            </tbody>
        </table>
    </div>

    <div id="pagination">
        @include('livewire.sliders.partials.pagination', ['sliders' => $sliders])
    </div>
</div>

<style>
.line-clamp-2 { display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput  = document.getElementById('searchInput');
    const clearSearch  = document.getElementById('clearSearch');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        clearSearch.classList.toggle('hidden', !this.value);
        searchResults.classList.toggle('hidden', !this.value);
        searchTimeout = setTimeout(loadSliders, 300);
    });

    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        this.classList.add('hidden');
        searchResults.classList.add('hidden');
        loadSliders();
    });

    document.querySelectorAll('.sort-header').forEach(h => {
        h.addEventListener('click', () => loadSliders(1, h.dataset.sort));
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            if (confirm('¿Eliminar este slider?')) {
                fetch(`/admin/sliders/${btn.dataset.id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => { if (data.success) { loadSliders(); showAlert(data.message, 'success'); } });
            }
        }

        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const page = new URL(e.target.closest('a').href).searchParams.get('page');
            loadSliders(page);
        }
    });

    function loadSliders(page = 1, sortField = 'orden', sortDirection = 'asc') {
        const search = searchInput.value;
        const url = `{{ route('sliders.index') }}?page=${page}&search=${encodeURIComponent(search)}&sortField=${sortField}&sortDirection=${sortDirection}`;
        fetch(url, { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            document.getElementById('slidersTable').innerHTML = data.html;
            document.getElementById('pagination').innerHTML = data.pagination;
        });
    }

    function showAlert(message, type = 'info') {
        const alertContainer = document.getElementById('alertContainer');
        alertContainer.innerHTML = `<div class="${type === 'success' ? 'admin-alert-ok' : 'admin-alert-err'}">${message}</div>`;
        setTimeout(() => alertContainer.innerHTML = '', 4000);
    }
});
</script>
@endsection
