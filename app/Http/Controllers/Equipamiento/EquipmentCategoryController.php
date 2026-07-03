<?php

namespace App\Http\Controllers\Equipamiento;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EquipmentCategoryController extends Controller
{
    public function index()
    {
        $categories = EquipmentCategory::withCount('products')->ordered()->get();

        return view('livewire.equipamiento.categorias.index', compact('categories'));
    }

    public function create()
    {
        return view('livewire.equipamiento.categorias.create', [
            'category' => null,
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);
        $validated['visible'] = $request->has('visible');
        $validated['slug'] = $this->makeUniqueSlug($validated['title']);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('equipamiento/categorias', 'public');
        }

        EquipmentCategory::create($validated);

        return redirect()->route('equipment.categories.index')->with('toast', [
            'message' => 'Categoría creada correctamente',
            'type' => 'success',
        ]);
    }

    public function edit($id)
    {
        $category = EquipmentCategory::findOrFail($id);

        return view('livewire.equipamiento.categorias.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = EquipmentCategory::findOrFail($id);
        $validated = $this->validateCategory($request, $category->id);
        $validated['visible'] = $request->has('visible');

        if ($category->title !== $validated['title']) {
            $validated['slug'] = $this->makeUniqueSlug($validated['title'], $category->id);
        }

        if ($request->hasFile('imagen')) {
            if ($category->imagen && Storage::disk('public')->exists($category->imagen)) {
                Storage::disk('public')->delete($category->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('equipamiento/categorias', 'public');
        }

        if ($request->has('remove_imagen')) {
            if ($category->imagen && Storage::disk('public')->exists($category->imagen)) {
                Storage::disk('public')->delete($category->imagen);
            }
            $validated['imagen'] = null;
        }

        $category->update($validated);

        return redirect()->route('equipment.categories.index')->with('toast', [
            'message' => 'Categoría actualizada correctamente',
            'type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $category = EquipmentCategory::findOrFail($id);

        if ($category->imagen && Storage::disk('public')->exists($category->imagen)) {
            Storage::disk('public')->delete($category->imagen);
        }

        // Los productos quedan sin categoría (nullOnDelete en la FK)
        $category->delete();

        return redirect()->route('equipment.categories.index')->with('toast', [
            'message' => 'Categoría eliminada correctamente',
            'type' => 'success',
        ]);
    }

    public function toggleVisible($id)
    {
        $category = EquipmentCategory::findOrFail($id);
        $category->update(['visible' => !$category->visible]);

        return redirect()->route('equipment.categories.index')->with('toast', [
            'message' => 'Visibilidad actualizada',
            'type' => 'success',
        ]);
    }

    protected function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'orden' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('equipment_categories', 'orden')->ignore($ignoreId),
            ],
            'imagen' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'visible' => 'nullable',
            'remove_imagen' => 'nullable',
        ]);
    }

    protected function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'categoria';
        $slug = $base;
        $counter = 2;

        while (EquipmentCategory::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function getNextAvailableOrder(): string
    {
        $existing = EquipmentCategory::orderBy('orden')
            ->pluck('orden')
            ->map(fn ($o) => strtolower((string) $o))
            ->filter()
            ->toArray();

        $current = 'aa';

        while (in_array($current, $existing, true)) {
            $current = $this->incrementOrder($current);
        }

        return strtoupper($current);
    }

    protected function incrementOrder(string $order): string
    {
        $chars = str_split(strtolower($order));

        for ($i = count($chars) - 1; $i >= 0; $i--) {
            if ($chars[$i] !== 'z') {
                $chars[$i] = chr(ord($chars[$i]) + 1);
                return implode('', $chars);
            }
            $chars[$i] = 'a';
        }

        return str_repeat('a', count($chars) + 1);
    }
}
