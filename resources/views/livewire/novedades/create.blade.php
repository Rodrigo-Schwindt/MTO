@section('page-title', 'Novedades / Crear')

@extends('layouts.admin')

@section('content')
<div class="max-w-3xl space-y-6 admin-fade">

    <div class="flex items-center justify-end">
        <a href="{{ route('novedades.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
    </div>

    <form action="{{ route('novedades.store') }}" method="POST" enctype="multipart/form-data" class="admin-card space-y-6">
        @csrf

        <div>
            <label for="title" class="block text-sm font-medium text-slate-800 mb-2">Título <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" class="admin-input" required>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-800 mb-2">Descripción</label>
            <textarea name="description" id="description" class="admin-input resize-none h-28"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="orden" class="block text-sm font-medium text-slate-800 mb-2">Orden</label>
                <input type="text" id="orden" name="orden" class="admin-input font-mono uppercase">
                <p class="text-xs text-slate-400 mt-1">Orden alfabético: AA, AB, AC…</p>
            </div>
            <div class="flex items-start pt-7">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="destacado" class="w-4 h-4 rounded">
                    <span class="text-sm font-medium text-slate-800">Destacado</span>
                </label>
            </div>
        </div>

        {{-- Categorías --}}
        <div>
            <label class="block text-sm font-medium text-slate-800 mb-2">Categorías <span class="text-red-500">*</span></label>
            <div class="border border-slate-200 rounded-lg bg-white max-h-44 overflow-y-auto p-2 space-y-0.5">
                @foreach($categories as $cat)
                    <label class="flex items-center gap-2.5 px-2.5 py-1.5 cursor-pointer hover:bg-[#f2faea] rounded-md transition">
                        <input type="checkbox" name="selectedCategories[]" value="{{ $cat->id }}" class="w-4 h-4 rounded">
                        <span class="text-sm text-slate-700">{{ $cat->title }}</span>
                    </label>
                @endforeach
            </div>
            @error('selectedCategories') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Imagen --}}
        <div>
            <label class="block text-sm font-medium text-slate-800 mb-2">Imagen principal <span class="text-red-500">*</span></label>

            <div class="flex items-start gap-4">
                <div id="imageContainer">
                    <div id="imagePlaceholder" class="w-48 h-48 border-2 border-dashed border-slate-300 rounded-lg flex flex-col items-center justify-center bg-slate-50 text-slate-400">
                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-xs">Vista previa</p>
                    </div>
                    <div id="imagePreview" class="hidden">
                        <img id="preview-image" src="" alt="Vista previa" class="w-48 h-48 object-cover rounded-lg border border-slate-200">
                    </div>
                </div>

                <div class="space-y-2">
                    <input type="file" name="image" required class="hidden" id="img1" accept="image/*" onchange="previewImage(event)">
                    <button type="button" onclick="document.getElementById('img1').click()" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Seleccionar imagen
                    </button>
                    <div class="upload-hint">
                        <strong>Formato:</strong> JPG, PNG, WebP<br>
                        <strong>Tamaño:</strong> 800×800px (1:1)<br>
                        <strong>Peso:</strong> máx. 2 MB
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
            <a href="{{ route('novedades.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Crear novedad</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('description') && window.CKEDITOR) CKEDITOR.replace('description');
});

function previewImage(event) {
    const placeholder = document.getElementById('imagePlaceholder');
    const preview     = document.getElementById('imagePreview');
    const img         = document.getElementById('preview-image');
    if (event.target.files && event.target.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; placeholder.classList.add('hidden'); preview.classList.remove('hidden'); };
        reader.readAsDataURL(event.target.files[0]);
    } else {
        placeholder.classList.remove('hidden'); preview.classList.add('hidden');
    }
}
</script>
@endsection
