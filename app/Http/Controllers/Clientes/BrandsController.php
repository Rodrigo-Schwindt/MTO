<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\BrandPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BrandsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $pageData = BrandPage::firstOrCreate([]);

        $brands = Brand::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('orden', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($q) => $q->where('title', 'like', "%{$search}%"));
            })
            ->ordered()
            ->paginate(12);

        return view('livewire.brands.index', compact('brands', 'pageData', 'search'));
    }

    public function create()
    {
        return view('livewire.brands.create', [
            'categories' => BrandCategory::ordered()->get(),
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateBrand($request);
        $validated['image'] = $request->file('image')->store('marcas', 'public');
        $validated['visible'] = $request->has('visible');
        $validated['destacado'] = $request->has('destacado');

        Brand::create($validated);

        return redirect()->route('brands.index')->with('toast', [
            'message' => 'Marca creada correctamente',
            'type' => 'success',
        ]);
    }

    public function edit($id)
    {
        return view('livewire.brands.edit', [
            'brand' => Brand::findOrFail($id),
            'categories' => BrandCategory::ordered()->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $validated = $this->validateBrand($request, $brand->id);

        if ($request->hasFile('image')) {
            if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                Storage::disk('public')->delete($brand->image);
            }

            $validated['image'] = $request->file('image')->store('marcas', 'public');
        }

        $validated['visible'] = $request->has('visible');
        $validated['destacado'] = $request->has('destacado');

        $brand->update($validated);

        return redirect()->route('brands.index')->with('toast', [
            'message' => 'Marca actualizada correctamente',
            'type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image && Storage::disk('public')->exists($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return redirect()->route('brands.index')->with('toast', [
            'message' => 'Marca eliminada correctamente',
            'type' => 'success',
        ]);
    }

    public function toggleVisible($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update(['visible' => !$brand->visible]);

        return redirect()->route('brands.index')->with('toast', [
            'message' => 'Visibilidad actualizada',
            'type' => 'success',
        ]);
    }

    public function toggleDestacado($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update(['destacado' => !$brand->destacado]);

        return redirect()->route('brands.index')->with('toast', [
            'message' => 'Destacado actualizado',
            'type' => 'success',
        ]);
    }

    public function saveBanner(Request $request)
    {
        $request->validate([
            'image_banner' => 'required|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        $pageData = BrandPage::firstOrCreate([]);

        if ($pageData->image_banner && Storage::disk('public')->exists($pageData->image_banner)) {
            Storage::disk('public')->delete($pageData->image_banner);
        }

        $pageData->update([
            'image_banner' => $request->file('image_banner')->store('marcas/banners', 'public'),
        ]);

        return response()->json([
            'success' => 'Banner actualizado',
            'url' => Storage::url($pageData->image_banner),
        ]);
    }

    public function removeBanner()
    {
        $pageData = BrandPage::first();

        if (!$pageData || !$pageData->image_banner) {
            return response()->json(['error' => 'No hay banner'], 404);
        }

        if (Storage::disk('public')->exists($pageData->image_banner)) {
            Storage::disk('public')->delete($pageData->image_banner);
        }

        $pageData->update(['image_banner' => null]);

        return response()->json(['success' => 'Banner eliminado']);
    }

    protected function validateBrand(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'brand_category_id' => 'nullable|exists:brand_categories,id',
            'orden' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('brands', 'orden')->ignore($ignoreId),
            ],
            'image' => ($ignoreId ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'visible' => 'nullable',
            'destacado' => 'nullable',
        ]);
    }

    protected function getNextAvailableOrder(): string
    {
        $existingOrders = Brand::orderBy('orden')
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
