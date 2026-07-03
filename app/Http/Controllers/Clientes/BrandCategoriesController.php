<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Models\BrandCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandCategoriesController extends Controller
{
    public function index()
    {
        return view('livewire.brand-categories.index', [
            'categories' => BrandCategory::withCount('brands')->ordered()->paginate(12),
        ]);
    }

    public function create()
    {
        return view('livewire.brand-categories.create', [
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);
        $validated['visible'] = $request->has('visible');

        BrandCategory::create($validated);

        return redirect()->route('brand-categories.index')->with('toast', [
            'message' => 'Categoria creada correctamente',
            'type' => 'success',
        ]);
    }

    public function edit($id)
    {
        return view('livewire.brand-categories.edit', [
            'category' => BrandCategory::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = BrandCategory::findOrFail($id);
        $validated = $this->validateCategory($request, $category->id);
        $validated['visible'] = $request->has('visible');

        $category->update($validated);

        return redirect()->route('brand-categories.index')->with('toast', [
            'message' => 'Categoria actualizada correctamente',
            'type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        BrandCategory::findOrFail($id)->delete();

        return redirect()->route('brand-categories.index')->with('toast', [
            'message' => 'Categoria eliminada correctamente',
            'type' => 'success',
        ]);
    }

    public function toggleVisible($id)
    {
        $category = BrandCategory::findOrFail($id);
        $category->update(['visible' => !$category->visible]);

        return redirect()->route('brand-categories.index')->with('toast', [
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
                Rule::unique('brand_categories', 'orden')->ignore($ignoreId),
            ],
            'visible' => 'nullable',
        ]);
    }

    protected function getNextAvailableOrder(): string
    {
        $existingOrders = BrandCategory::orderBy('orden')
            ->pluck('orden')
            ->map(fn ($orden) => strtolower((string) $orden))
            ->filter()
            ->toArray();

        $currentOrder = 'aa';

        while (in_array($currentOrder, $existingOrders, true)) {
            $currentOrder = $this->incrementOrder($currentOrder);
        }

        return strtoupper($currentOrder);
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
