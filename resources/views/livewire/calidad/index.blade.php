@section('page-title', 'Calidad')

@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-semibold text-slate-900">Calidad</h2>
        <a href="{{ route('calidad.public') }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-slate-900 text-white rounded-md hover:bg-slate-800 transition">
            Ver pagina
        </a>
    </div>

    @if(session('toast'))
        <div class="px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('toast.message') }}
        </div>
    @endif

    @if($errors->any())
        <div class="px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
            Revisá los campos marcados antes de guardar.
        </div>
    @endif

    <form action="{{ route('quality.save') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <section class="border border-slate-200 rounded-md p-5 bg-slate-50">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Banner de seccion</h3>
            @if($pageData->image_banner)
                <div class="mb-4 h-44 rounded-md border border-slate-200 bg-white overflow-hidden">
                    <img src="{{ Storage::url($pageData->image_banner) }}" class="w-full h-full object-cover" alt="Banner calidad">
                </div>
                <button form="removeBannerForm" type="submit" class="mb-4 px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 transition">Eliminar banner</button>
            @endif
            <input type="file" name="image_banner" accept=".jpg,.jpeg,.png,.webp,.svg" class="w-full border border-slate-300 rounded-md px-3 py-2 bg-white">
            @error('image_banner') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Titulo</label>
                    <input type="text" name="title" value="{{ old('title', $pageData->title) }}" class="w-full border border-slate-300 rounded-md px-3 py-2" required>
                    @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Descripcion</label>
                    <textarea id="description" name="description" rows="10" class="w-full border border-slate-300 rounded-md px-3 py-2">{{ old('description', html_entity_decode($pageData->description ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')) }}</textarea>
                    @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <aside class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Imagen principal</label>
                    @if($pageData->image)
                        <div class="mb-3 h-64 border border-slate-200 rounded-md overflow-hidden bg-slate-100">
                            <img src="{{ Storage::url($pageData->image) }}" class="w-full h-full object-cover" alt="Imagen calidad">
                        </div>
                        <button form="removeImageForm" type="submit" class="mb-3 px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 transition">Eliminar imagen</button>
                    @endif
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.svg" class="w-full border border-slate-300 rounded-md px-3 py-2">
                    @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </aside>
        </section>

        <section class="border border-slate-200 rounded-md p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Descargas</h3>
                    <p class="text-sm text-slate-500">Cada descarga tiene orden alfabetico, titulo y archivo.</p>
                </div>
                <button id="addDownload" type="button" class="px-4 py-2 bg-[#52A028] text-white rounded-md text-sm hover:bg-[#3d7a1e] transition">Agregar descarga</button>
            </div>

            <div class="space-y-4">
                @forelse($downloads as $download)
                    @php $extension = strtoupper(pathinfo($download->original_name ?: $download->file, PATHINFO_EXTENSION)); @endphp
                    <div class="grid grid-cols-1 lg:grid-cols-[90px_1fr_1fr_1fr_120px] gap-3 items-end border border-slate-200 rounded-md p-4 bg-slate-50">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Orden</label>
                            <input type="text" name="downloads[{{ $download->id }}][orden]" value="{{ old("downloads.{$download->id}.orden", $download->orden) }}" class="w-full border border-slate-300 rounded-md px-3 py-2 uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Titulo</label>
                            <input type="text" name="downloads[{{ $download->id }}][title]" value="{{ old("downloads.{$download->id}.title", $download->title) }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                            <p class="mt-1 text-xs text-slate-500">{{ $download->original_name }} @if($extension)({{ $extension }})@endif</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Imagen</label>
                            @if($download->image)
                                <div class="mb-2 h-10 w-14 border border-slate-200 rounded bg-white flex items-center justify-center overflow-hidden">
                                    <img src="{{ Storage::url($download->image) }}" alt="{{ $download->title }}" class="max-h-10 max-w-full object-contain">
                                </div>
                            @endif
                            <input type="file" name="download_images[{{ $download->id }}]" accept=".jpg,.jpeg,.png,.webp,.svg" class="w-full border border-slate-300 rounded-md px-3 py-2 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Reemplazar archivo</label>
                            <input type="file" name="download_files[{{ $download->id }}]" class="w-full border border-slate-300 rounded-md px-3 py-2 bg-white">
                        </div>
                        <label class="inline-flex items-center gap-2 text-sm text-red-600 pb-2">
                            <input type="checkbox" name="delete_downloads[]" value="{{ $download->id }}">
                            Eliminar
                        </label>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Todavia no hay descargas cargadas.</p>
                @endforelse
            </div>

            <div id="newDownloads" class="space-y-4 mt-4"></div>
            @error('new_downloads') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
        </section>

        <div class="flex justify-end">
            <button class="px-5 py-2.5 rounded-md bg-[#52A028] text-white hover:bg-[#3d7a1e]">Guardar cambios</button>
        </div>
    </form>

    <form id="removeBannerForm" action="{{ route('quality.banner.remove') }}" method="POST" onsubmit="return confirm('Eliminar banner?')">
        @csrf
        @method('DELETE')
    </form>

    <form id="removeImageForm" action="{{ route('quality.image.remove') }}" method="POST" onsubmit="return confirm('Eliminar imagen?')">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('description') && window.CKEDITOR) {
        CKEDITOR.replace('description');
    }

    const addButton = document.getElementById('addDownload');
    const wrapper = document.getElementById('newDownloads');
    let downloadIndex = 0;
    let nextOrder = '{{ $nextOrder }}';

    function incrementOrder(order) {
        const chars = order.toLowerCase().split('');
        for (let i = chars.length - 1; i >= 0; i--) {
            if (chars[i] !== 'z') {
                chars[i] = String.fromCharCode(chars[i].charCodeAt(0) + 1);
                return chars.join('').toUpperCase();
            }
            chars[i] = 'a';
        }
        return (order + 'a').toUpperCase();
    }

    addButton?.addEventListener('click', function() {
        const index = downloadIndex++;
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 lg:grid-cols-[90px_1fr_1fr_1fr_90px] gap-3 items-end border border-[#d4ebc7] rounded-md p-4 bg-[#f2faea]/60';
        row.innerHTML = `
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Orden</label>
                <input type="text" name="new_downloads[${index}][orden]" value="${nextOrder}" class="w-full border border-slate-300 rounded-md px-3 py-2 uppercase">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Titulo</label>
                <input type="text" name="new_downloads[${index}][title]" class="w-full border border-slate-300 rounded-md px-3 py-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Imagen</label>
                <input type="file" name="new_download_images[${index}]" accept=".jpg,.jpeg,.png,.webp,.svg" class="w-full border border-slate-300 rounded-md px-3 py-2 bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Archivo</label>
                <input type="file" name="new_download_files[${index}]" class="w-full border border-slate-300 rounded-md px-3 py-2 bg-white">
            </div>
            <button type="button" class="remove-download px-4 py-2 border border-slate-300 rounded-md text-slate-600">Quitar</button>
        `;
        wrapper.appendChild(row);
        nextOrder = incrementOrder(nextOrder);

        row.querySelector('.remove-download').addEventListener('click', function() {
            row.remove();
        });
    });
});
</script>
@endsection
