<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-slate-900 mb-2">Titulo *</label>
        <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
        @error('title') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-900 mb-2">Orden</label>
        <input type="text" name="orden" value="{{ old('orden', $project->orden ?? $nextOrder) }}" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm font-mono uppercase">
        @error('orden') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">Descripcion</label>
    <textarea id="description" name="description" rows="8" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">{{ old('description', $project->description ?? '') }}</textarea>
    @error('description') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <label class="flex items-center gap-3 border border-slate-200 rounded-md p-4 cursor-pointer">
        <input type="checkbox" name="visible" value="1" class="rounded border-slate-300 text-[#52A028]" {{ old('visible', $project->visible ?? true) ? 'checked' : '' }}>
        <span class="text-sm font-semibold text-slate-900">Visible</span>
    </label>

    <label class="flex items-center gap-3 border border-slate-200 rounded-md p-4 cursor-pointer">
        <input type="checkbox" name="destacado" value="1" class="rounded border-slate-300 text-[#52A028]" {{ old('destacado', $project->destacado ?? false) ? 'checked' : '' }}>
        <span class="text-sm font-semibold text-slate-900">Destacado</span>
    </label>
</div>

<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">Imagen principal {{ $project ? '' : '*' }}</label>
    <input type="file" name="main_image" accept="image/*" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm bg-white">
    @error('main_image') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-900 mb-2">Galeria de imagenes</label>
    <input type="file" name="gallery_images[]" accept="image/*" multiple class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm bg-white">
    @error('gallery_images.*') <p class="mt-1 text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

@if($project && $project->images->isNotEmpty())
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-slate-900">Imagenes actuales</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($project->images as $image)
                <div class="border border-slate-200 rounded-md p-3 space-y-3">
                    <img src="{{ Storage::url($image->image) }}" alt="{{ $project->title }}" class="w-full h-28 object-cover rounded-md">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" name="main_image_id" value="{{ $image->id }}" {{ $image->is_main ? 'checked' : '' }}>
                        Principal
                    </label>
                    <label class="flex items-center gap-2 text-sm text-red-600">
                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                        Eliminar
                    </label>
                </div>
            @endforeach
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('description') && window.CKEDITOR) {
        CKEDITOR.replace('description');
    }
});
</script>
