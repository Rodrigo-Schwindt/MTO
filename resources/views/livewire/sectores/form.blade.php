@csrf
@if($sector)
    @method('PUT')
@endif

<div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Orden</label>
            <input type="text" name="orden" value="{{ old('orden', $sector?->orden ?? $nextOrder ?? '') }}" class="w-full border border-slate-300 rounded-md px-3 py-2 uppercase">
            @error('orden') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Titulo</label>
            <input type="text" name="title" value="{{ old('title', $sector?->title) }}" class="w-full border border-slate-300 rounded-md px-3 py-2" required>
            @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
            <textarea id="description" name="description" rows="8" class="w-full border border-slate-300 rounded-md px-3 py-2 resize-none">{{ old('description', $sector?->description) }}</textarea>
            @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Imagen</label>
            @if($sector?->image)
                <div class="mb-3 h-40 w-full max-w-md border border-slate-200 rounded-md overflow-hidden bg-slate-100">
                    <img src="{{ Storage::url($sector->image) }}" alt="{{ $sector->title }}" class="w-full h-full object-cover">
                </div>
            @endif
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.svg" class="w-full border border-slate-300 rounded-md px-3 py-2" {{ $sector ? '' : 'required' }}>
            <p class="mt-1 text-xs text-slate-500">En desktop se ve en blanco y negro hasta hover; en responsive se ve siempre a color.</p>
            @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <aside class="space-y-5">
        <div class="border border-slate-200 rounded-md p-4">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="visible" value="1" {{ old('visible', $sector?->visible ?? true) ? 'checked' : '' }}>
                <span class="text-sm text-slate-700">Visible</span>
            </label>
        </div>
    </aside>
</div>

<div class="flex justify-end gap-3 pt-8">
    <a href="{{ route('sectors.index') }}" class="px-5 py-2.5 rounded-md border border-slate-300 text-slate-700">Cancelar</a>
    <button class="px-5 py-2.5 rounded-md bg-[#52A028] text-white hover:bg-[#3d7a1e]">Guardar</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('description') && window.CKEDITOR) {
        CKEDITOR.replace('description');
    }
});
</script>
