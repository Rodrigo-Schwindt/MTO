@csrf
@if($category)
    @method('PUT')
@endif

<div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Orden</label>
            <input type="text" name="orden" value="{{ old('orden', $category?->orden ?? $nextOrder ?? '') }}" class="w-full border border-slate-300 rounded-md px-3 py-2 uppercase">
            @error('orden') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Título</label>
            <input type="text" name="title" value="{{ old('title', $category?->title) }}" class="w-full border border-slate-300 rounded-md px-3 py-2" required>
            @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="border border-slate-200 rounded-md p-4 bg-slate-50">
            <label class="block text-sm font-semibold text-slate-800 mb-1">Imagen de la categoría</label>
            <p class="text-xs text-slate-500 mb-3">Se muestra en la card de la categoría en la vista pública.</p>

            @if($category?->imagen)
                <div class="mb-3 flex items-center gap-4">
                    <img src="{{ Storage::url($category->imagen) }}" class="h-24 max-w-[180px] object-contain border border-slate-200 rounded-md bg-white p-1" alt="Imagen actual">
                    <label class="flex items-center gap-2 text-sm text-red-600">
                        <input type="checkbox" name="remove_imagen" value="1">
                        Eliminar imagen
                    </label>
                </div>
            @endif

            <label for="catImageInput" class="flex min-h-[96px] cursor-pointer flex-col items-center justify-center rounded-md border-2 border-dashed border-slate-300 bg-white px-4 py-6 text-center hover:border-[#52A028] hover:bg-[#f2faea]/60 transition">
                <span class="text-sm font-semibold text-slate-700">{{ $category?->imagen ? 'Reemplazar imagen' : 'Seleccionar imagen' }}</span>
                <span class="mt-1 text-xs text-slate-500">JPG, PNG, WebP o SVG — máx 5 MB</span>
                <input id="catImageInput" type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp,.svg" class="sr-only">
            </label>

            <div id="catImagePreview" class="hidden mt-3">
                <img id="catImagePreviewImg" src="" alt="Preview" class="h-28 max-w-full object-contain rounded-md border border-slate-200 bg-white p-1">
            </div>

            @error('imagen') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <aside class="space-y-5">
        <div class="border border-slate-200 rounded-md p-4">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="visible" value="1" {{ old('visible', $category?->visible ?? true) ? 'checked' : '' }}>
                <span class="text-sm text-slate-700">Visible</span>
            </label>
        </div>
    </aside>
</div>

<div class="flex justify-end gap-3 pt-8">
    <a href="{{ route('equipment.categories.index') }}" class="px-5 py-2.5 rounded-md border border-slate-300 text-slate-700">Cancelar</a>
    <button class="px-5 py-2.5 rounded-md bg-[#52A028] text-white hover:bg-[#3d7a1e]">Guardar</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('catImageInput');
    const preview = document.getElementById('catImagePreview');
    const previewImg = document.getElementById('catImagePreviewImg');

    if (input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
