@csrf
@if($product)
    @method('PUT')
@endif

<div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Orden</label>
            <input type="text" name="orden" value="{{ old('orden', $product?->orden ?? $nextOrder ?? '') }}" class="w-full border border-slate-300 rounded-md px-3 py-2 uppercase">
            @error('orden') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Titulo</label>
            <input type="text" name="title" value="{{ old('title', $product?->title) }}" class="w-full border border-slate-300 rounded-md px-3 py-2" required>
            @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Descripcion</label>
            <textarea id="description" name="description" rows="8" class="w-full border border-slate-300 rounded-md px-3 py-2" placeholder="Una caracteristica por linea para que se vea con checks en el detalle.">{{ old('description', html_entity_decode($product?->description ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')) }}</textarea>
            @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Ficha tecnica</label>
            @if($product?->technical_sheet)
                <a href="{{ Storage::url($product->technical_sheet) }}" target="_blank" class="inline-flex mb-2 text-sm text-[#52A028] hover:underline">Ver ficha actual</a>
                <label class="inline-flex items-center gap-2 ml-4 text-sm">
                    <input type="checkbox" name="remove_technical_sheet" value="1">
                    Eliminar ficha
                </label>
            @endif
            <input type="file" name="technical_sheet" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" class="w-full border border-slate-300 rounded-md px-3 py-2">
            @error('technical_sheet') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="border border-slate-200 rounded-md p-4 bg-slate-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                <div>
                    <label for="galleryImages" class="block text-sm font-semibold text-slate-800">Galeria de imagenes</label>
                    <p class="text-xs text-slate-500 mt-1">Podes seleccionar varias imagenes a la vez. En editar, las nuevas se agregan a la galeria actual.</p>
                </div>
            </div>

            <label for="galleryImages" class="flex min-h-[116px] cursor-pointer flex-col items-center justify-center rounded-md border-2 border-dashed border-slate-300 bg-white px-4 py-6 text-center hover:border-[#52A028] hover:bg-[#f2faea]/60 transition">
                <span class="text-sm font-semibold text-slate-700">Agregar imagenes</span>
                <span class="mt-1 text-xs text-slate-500">Podes agregarlas de a una o seleccionar varias juntas.</span>
                <input id="galleryImages" type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp,.svg" class="sr-only" {{ $product ? '' : 'required' }}>
            </label>

            <div id="galleryPreview" class="hidden mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>

            @error('images') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            @error('images.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        @if($product && $product->images->isNotEmpty())
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-3">Galeria actual</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                        <div class="border border-slate-200 rounded-md p-3">
                            <div class="h-24 flex items-center justify-center mb-3">
                                <img src="{{ Storage::url($image->image) }}" class="max-h-24 max-w-full object-contain" alt="Imagen producto">
                            </div>
                            <label class="flex items-center gap-2 text-sm mb-2">
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
    </div>

    <aside class="space-y-5">
        <div class="border border-slate-200 rounded-md p-4">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="visible" value="1" {{ old('visible', $product?->visible ?? true) ? 'checked' : '' }}>
                <span class="text-sm text-slate-700">Visible</span>
            </label>
        </div>

        <div class="border border-slate-200 rounded-md p-4">
            <label class="block text-sm font-medium text-slate-700 mb-2">Categoría</label>
            <select name="equipment_category_id" class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
                <option value="">Sin categoría</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('equipment_category_id', $product?->equipment_category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->orden ? $cat->orden . ' - ' : '' }}{{ $cat->title }}
                    </option>
                @endforeach
            </select>
            @error('equipment_category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="border border-slate-200 rounded-md p-4">
            <label class="block text-sm font-medium text-slate-700 mb-1">Productos relacionados</label>
            <p class="text-xs text-slate-400 mb-3">Si no se selecciona ninguno, se mostrarán automáticamente productos de la misma categoría bajo el título <em>"Más de esta categoría"</em>.</p>
            <div class="max-h-[360px] overflow-y-auto space-y-2">
                @php $selectedRelated = collect(old('related_ids', $product?->relatedProducts?->pluck('id')->all() ?? []))->map(fn($id) => (int) $id)->all(); @endphp
                @foreach($products as $related)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="related_ids[]" value="{{ $related->id }}" {{ in_array($related->id, $selectedRelated, true) ? 'checked' : '' }}>
                        <span>{{ $related->orden }} - {{ $related->title }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </aside>
</div>

<div class="flex justify-end gap-3 pt-8">
    <a href="{{ route('equipment.index') }}" class="px-5 py-2.5 rounded-md border border-slate-300 text-slate-700">Cancelar</a>
    <button class="px-5 py-2.5 rounded-md bg-[#52A028] text-white hover:bg-[#3d7a1e]">Guardar</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('description') && window.CKEDITOR) {
        CKEDITOR.replace('description');
    }

    const galleryInput = document.getElementById('galleryImages');
    const galleryPreview = document.getElementById('galleryPreview');

    if (galleryInput && galleryPreview) {
        const selectedFiles = new Map();

        const syncInputFiles = () => {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach((file) => dataTransfer.items.add(file));
            galleryInput.files = dataTransfer.files;
        };

        const renderPreview = () => {
            galleryPreview.innerHTML = '';
            const files = Array.from(selectedFiles.values());
            galleryPreview.classList.toggle('hidden', files.length === 0);

            files.forEach((file, index) => {
                const reader = new FileReader();
                const item = document.createElement('div');
                item.className = 'relative rounded-md border border-slate-200 bg-white p-3';

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'absolute right-2 top-2 h-7 w-7 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-red-600 hover:border-red-300 shadow-sm';
                removeButton.textContent = 'x';
                removeButton.setAttribute('aria-label', 'Quitar imagen');
                removeButton.addEventListener('click', () => {
                    selectedFiles.delete(`${file.name}-${file.size}-${file.lastModified}`);
                    syncInputFiles();
                    renderPreview();
                });

                const imageWrap = document.createElement('div');
                imageWrap.className = 'h-24 flex items-center justify-center mb-2';

                const img = document.createElement('img');
                img.className = 'max-h-24 max-w-full object-contain';
                img.alt = file.name;

                const name = document.createElement('p');
                name.className = 'truncate text-xs text-slate-500';
                name.textContent = `${index + 1}. ${file.name}`;

                imageWrap.appendChild(img);
                item.appendChild(removeButton);
                item.appendChild(imageWrap);
                item.appendChild(name);
                galleryPreview.appendChild(item);

                reader.onload = (event) => {
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
        };

        galleryInput.addEventListener('change', function() {
            Array.from(this.files || []).forEach((file) => {
                selectedFiles.set(`${file.name}-${file.size}-${file.lastModified}`, file);
            });

            syncInputFiles();
            renderPreview();
        });
    }
});
</script>
