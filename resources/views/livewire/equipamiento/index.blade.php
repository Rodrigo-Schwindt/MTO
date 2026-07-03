@section('page-title', 'Equipamiento')

@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-semibold text-slate-900">Equipamiento (<span>{{ $products->total() }}</span>)</h2>
        <div class="flex gap-3">
            <a href="{{ route('equipment.categories.index') }}" class="inline-flex items-center px-5 py-2.5 border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition">
                Categorías
            </a>
            <a href="{{ route('equipment.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#52A028] text-white rounded-md hover:bg-[#3d7a1e] transition">
                Crear producto
            </a>
        </div>
    </div>

    @if(session('toast'))
        <div class="px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('toast.message') }}
        </div>
    @endif

    <section class="border border-slate-200 rounded-md p-5 bg-slate-50">
        <div class="flex flex-col lg:flex-row gap-5 lg:items-center lg:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Banner de Equipamiento</h3>
                <p class="text-sm text-slate-500">Se usa en la cabecera publica de Equipamiento industrial.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <input id="bannerInput" type="file" accept=".jpg,.jpeg,.png,.webp,.svg" class="border border-slate-300 rounded-md px-3 py-2 text-sm bg-white file:bg-[#52A028] file:text-white file:px-4 file:py-2 file:rounded-md file:border-0 file:cursor-pointer cursor-pointer">
                <button id="saveBanner" type="button" class="px-4 py-2 bg-[#52A028] text-white rounded-md text-sm hover:bg-[#3d7a1e] transition hidden">Guardar banner</button>
                @if($pageData->image_banner)
                    <button id="removeBanner" type="button" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 transition">Eliminar</button>
                @endif
            </div>
        </div>

        <div class="mt-4 h-44 rounded-md border border-slate-200 bg-white overflow-hidden flex items-center justify-center">
            @if($pageData->image_banner)
                <img id="bannerPreview" src="{{ Storage::url($pageData->image_banner) }}" class="w-full h-full object-cover" alt="Banner equipamiento">
                <span id="bannerPlaceholder" class="hidden text-slate-400 text-sm">Sin banner</span>
            @else
                <img id="bannerPreview" src="" class="hidden w-full h-full object-cover" alt="Banner equipamiento">
                <span id="bannerPlaceholder" class="text-slate-400 text-sm">Sin banner</span>
            @endif
        </div>
        <p id="bannerError" class="hidden mt-2 text-sm text-red-600"></p>
    </section>

    <form method="GET" action="{{ route('equipment.index') }}" class="bg-white border border-slate-200 rounded-md p-4 shadow-sm">
        <div class="flex gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por titulo, descripcion u orden..." class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
            <button class="px-4 py-2 bg-slate-900 text-white rounded-md text-sm">Buscar</button>
        </div>
    </form>

    <div class="overflow-x-auto border border-slate-200 rounded-md bg-white shadow-sm">
        <table class="w-full text-sm text-slate-700">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-center">Orden</th>
                    <th class="px-4 py-3 text-center">Imagen</th>
                    <th class="px-4 py-3 text-left">Titulo</th>
                    <th class="px-4 py-3 text-center">Visible</th>
                    <th class="px-4 py-3 text-center">Ficha</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($products as $product)
                    @php $cover = $product->mainImage; @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-center font-mono uppercase">{{ $product->orden }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($cover)
                                <img src="{{ Storage::url($cover->image) }}" alt="{{ $product->title }}" class="h-14 max-w-[120px] object-contain mx-auto">
                            @else
                                <span class="text-slate-400">Sin imagen</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $product->title }}</td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('equipment.visible', $product->id) }}" method="POST">
                                @csrf
                                <button class="text-xl">{{ $product->visible ? 'Si' : 'No' }}</button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-center">{{ $product->technical_sheet ? 'Si' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('equipment.edit', $product->id) }}" class="text-[#52A028] hover:underline">Editar</a>
                                <form action="{{ route('equipment.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Eliminar producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">No hay productos cargados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bannerInput = document.getElementById('bannerInput');
    const saveBanner = document.getElementById('saveBanner');
    const removeBanner = document.getElementById('removeBanner');
    const bannerPreview = document.getElementById('bannerPreview');
    const bannerPlaceholder = document.getElementById('bannerPlaceholder');
    const bannerError = document.getElementById('bannerError');

    if (bannerInput) {
        bannerInput.addEventListener('change', function() {
            bannerError.classList.add('hidden');
            const file = this.files[0];
            saveBanner.classList.toggle('hidden', !file);
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                bannerPreview.src = event.target.result;
                bannerPreview.classList.remove('hidden');
                bannerPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    }

    if (saveBanner) {
        saveBanner.addEventListener('click', function() {
            const file = bannerInput.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image_banner', file);

            fetch('{{ route('equipment.banner.save') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    bannerPreview.src = data.url;
                    bannerPreview.classList.remove('hidden');
                    bannerPlaceholder.classList.add('hidden');
                    bannerInput.value = '';
                    saveBanner.classList.add('hidden');
                } else {
                    bannerError.textContent = data.message || data.error || 'Error al guardar el banner';
                    bannerError.classList.remove('hidden');
                }
            });
        });
    }

    if (removeBanner) {
        removeBanner.addEventListener('click', function() {
            if (!confirm('Eliminar banner?')) return;

            fetch('{{ route('equipment.banner.remove') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bannerPreview.src = '';
                    bannerPreview.classList.add('hidden');
                    bannerPlaceholder.classList.remove('hidden');
                    removeBanner.remove();
                }
            });
        });
    }
});
</script>
@endsection
