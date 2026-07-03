@section('page-title', 'Servicios / Editar')

@extends('layouts.admin')

@section('content')
<div class="max-w-3xl space-y-6 admin-fade">

    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-400">ID #{{ $service->id }} · Slug: /servicios/{{ $service->slug }}</p>
        <a href="{{ route('servicios.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
    </div>

    <form method="POST" action="{{ route('servicios.update', $service->id) }}" enctype="multipart/form-data" class="admin-card space-y-6">
        @csrf
        @method('PUT')

        {{-- Imagen actual --}}
        <div>
            <p class="text-sm font-medium text-slate-800 mb-2">Imagen actual</p>
            <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-lg bg-slate-50">
                @if($service->image)
                    <img src="{{ Storage::url($service->image) }}" class="h-28 w-44 object-cover rounded-lg border border-slate-200" alt="{{ $service->title }}">
                @else
                    <div class="h-28 w-44 rounded-lg bg-slate-200 flex items-center justify-center text-sm text-slate-400">Sin imagen</div>
                @endif
                <p class="text-xs text-slate-500">Para reemplazarla, seleccioná una nueva imagen abajo.</p>
            </div>
        </div>

        {{-- Nueva imagen --}}
        <div>
            <label for="image" class="block text-sm font-medium text-slate-800 mb-2">Nueva imagen</label>
            <div id="filePreview" class="mb-3 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 hidden">
                <img id="imagePreview" src="" class="w-full h-52 object-cover hidden" alt="">
            </div>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,.svg"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white file:bg-[#52A028] file:text-white file:px-4 file:py-1.5 file:rounded-md file:border-0 file:cursor-pointer file:mr-3 cursor-pointer focus:ring-2 focus:ring-[#52A028] focus:outline-none">
            @error('image') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
            <div class="upload-hint">
                <strong>Formato:</strong> JPG, PNG, WebP, SVG ·
                <strong>Tamaño:</strong> 620×430px ·
                <strong>Peso:</strong> máx. 2 MB
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-800 mb-2">Agregar imagenes a la galeria</label>
            <div id="galleryPreview" class="mb-3 grid grid-cols-2 md:grid-cols-4 gap-3 hidden"></div>
            <input type="file" id="gallery_images" name="gallery_images[]" accept=".jpg,.jpeg,.png,.webp,.svg" multiple
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white file:bg-[#52A028] file:text-white file:px-4 file:py-1.5 file:rounded-md file:border-0 file:cursor-pointer file:mr-3 cursor-pointer focus:ring-2 focus:ring-[#52A028] focus:outline-none">
            @error('gallery_images.*') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
            <div class="upload-hint">
                <strong>Formato:</strong> JPG, PNG, WebP, SVG ·
                <strong>Peso:</strong> max. 5 MB por imagen
            </div>
        </div>

        @if($service->images->isNotEmpty())
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-slate-800">Imagenes actuales de la galeria</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($service->images as $image)
                        <div class="border border-slate-200 rounded-lg p-3 space-y-3 bg-slate-50">
                            <img src="{{ Storage::url($image->image) }}" alt="{{ $service->title }}" class="w-full h-24 object-cover rounded-md border border-slate-200">
                            <label class="flex items-center gap-2 text-sm text-red-600">
                                <input type="checkbox" name="delete_gallery_images[]" value="{{ $image->id }}">
                                Eliminar
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-800 mb-2">Título <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $service->title) }}" class="admin-input">
                @error('title') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="orden" class="block text-sm font-medium text-slate-800 mb-2">Orden</label>
                <input type="text" id="orden" name="orden" value="{{ old('orden', $service->orden) }}"
                       placeholder="AA, AB, AC…" class="admin-input font-mono uppercase">
                @error('orden') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-800 mb-2">Descripción</label>
            <textarea id="description" name="description" rows="8" class="admin-input resize-none">{{ old('description', html_entity_decode($service->description ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')) }}</textarea>
            @error('description') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="flex items-start gap-3 border border-slate-200 rounded-lg p-4 cursor-pointer hover:bg-[#f2faea] transition">
                <input type="checkbox" name="visible" value="1" class="mt-0.5 w-4 h-4 rounded" {{ old('visible', $service->visible) ? 'checked' : '' }}>
                <span>
                    <span class="block text-sm font-semibold text-slate-800">Visible</span>
                    <span class="block text-xs text-slate-400">Aparece en la sección pública.</span>
                </span>
            </label>
            <label class="flex items-start gap-3 border border-slate-200 rounded-lg p-4 cursor-pointer hover:bg-[#f2faea] transition">
                <input type="checkbox" name="destacado" value="1" class="mt-0.5 w-4 h-4 rounded" {{ old('destacado', $service->destacado) ? 'checked' : '' }}>
                <span>
                    <span class="block text-sm font-semibold text-slate-800">Destacado</span>
                    <span class="block text-xs text-slate-400">Prioriza el contenido administrativamente.</span>
                </span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
            <a href="{{ route('servicios.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Guardar cambios</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('description') && window.CKEDITOR) CKEDITOR.replace('description');

    const imageInput   = document.getElementById('image');
    const filePreview  = document.getElementById('filePreview');
    const imagePreview = document.getElementById('imagePreview');
    const galleryInput = document.getElementById('gallery_images');
    const galleryPreview = document.getElementById('galleryPreview');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) { filePreview.classList.add('hidden'); return; }
        const reader = new FileReader();
        reader.onload = e => {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
            filePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    galleryInput.addEventListener('change', function() {
        galleryPreview.innerHTML = '';
        const files = Array.from(this.files || []);

        if (!files.length) {
            galleryPreview.classList.add('hidden');
            return;
        }

        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-24 object-cover rounded-lg border border-slate-200';
                galleryPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });

        galleryPreview.classList.remove('hidden');
    });
});
</script>
@endsection
