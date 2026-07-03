<?php

namespace App\Http\Controllers\Equipamiento;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategory;
use App\Models\EquipmentPage;
use App\Models\EquipmentProduct;
use App\Models\EquipmentProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $pageData = EquipmentPage::firstOrCreate([]);

        $products = EquipmentProduct::with('mainImage')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('orden', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->ordered()
            ->paginate(12);

        return view('livewire.equipamiento.index', compact('products', 'pageData', 'search'));
    }

    public function create()
    {
        return view('livewire.equipamiento.create', [
            'product' => null,
            'products' => EquipmentProduct::ordered()->get(),
            'categories' => EquipmentCategory::ordered()->get(),
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);
        $validated['visible'] = $request->has('visible');
        $validated['slug'] = $this->makeUniqueSlug($validated['title']);

        if ($request->hasFile('technical_sheet')) {
            $validated['technical_sheet'] = $request->file('technical_sheet')->store('equipamiento/fichas', 'public');
        }

        $product = EquipmentProduct::create($validated);

        $this->storeImages($request, $product);
        $this->syncMutualRelatedProducts($product, $request->input('related_ids', []));

        return redirect()->route('equipment.index')->with('toast', [
            'message' => 'Producto creado correctamente',
            'type' => 'success',
        ]);
    }

    public function edit($id)
    {
        $product = EquipmentProduct::with(['images', 'relatedProducts'])->findOrFail($id);

        return view('livewire.equipamiento.edit', [
            'product' => $product,
            'products' => EquipmentProduct::where('id', '!=', $product->id)->ordered()->get(),
            'categories' => EquipmentCategory::ordered()->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = EquipmentProduct::with('images')->findOrFail($id);
        $validated = $this->validateProduct($request, $product->id);
        $validated['visible'] = $request->has('visible');

        if ($product->title !== $validated['title']) {
            $validated['slug'] = $this->makeUniqueSlug($validated['title'], $product->id);
        }

        if ($request->hasFile('technical_sheet')) {
            if ($product->technical_sheet && Storage::disk('public')->exists($product->technical_sheet)) {
                Storage::disk('public')->delete($product->technical_sheet);
            }

            $validated['technical_sheet'] = $request->file('technical_sheet')->store('equipamiento/fichas', 'public');
        }

        if ($request->has('remove_technical_sheet')) {
            if ($product->technical_sheet && Storage::disk('public')->exists($product->technical_sheet)) {
                Storage::disk('public')->delete($product->technical_sheet);
            }

            $validated['technical_sheet'] = null;
        }

        $product->update($validated);

        foreach ($request->input('delete_images', []) as $imageId) {
            $image = $product->images()->where('id', $imageId)->first();
            if ($image) {
                Storage::disk('public')->delete($image->image);
                $image->delete();
            }
        }

        $this->storeImages($request, $product);

        if ($request->filled('main_image_id')) {
            $product->images()->update(['is_main' => false]);
            $product->images()->where('id', $request->integer('main_image_id'))->update(['is_main' => true]);
        } elseif (!$product->images()->where('is_main', true)->exists() && $product->images()->exists()) {
            $product->images()->oldest('id')->first()?->update(['is_main' => true]);
        }

        $this->syncMutualRelatedProducts($product, $request->input('related_ids', []));

        return redirect()->route('equipment.index')->with('toast', [
            'message' => 'Producto actualizado correctamente',
            'type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $product = EquipmentProduct::with('images')->findOrFail($id);

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        if ($product->technical_sheet) {
            Storage::disk('public')->delete($product->technical_sheet);
        }

        $product->delete();

        return redirect()->route('equipment.index')->with('toast', [
            'message' => 'Producto eliminado correctamente',
            'type' => 'success',
        ]);
    }

    public function toggleVisible($id)
    {
        $product = EquipmentProduct::findOrFail($id);
        $product->update(['visible' => !$product->visible]);

        return redirect()->route('equipment.index')->with('toast', [
            'message' => 'Visibilidad actualizada',
            'type' => 'success',
        ]);
    }

    public function saveBanner(Request $request)
    {
        $request->validate([
            'image_banner' => 'required|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        $pageData = EquipmentPage::firstOrCreate([]);

        if ($pageData->image_banner && Storage::disk('public')->exists($pageData->image_banner)) {
            Storage::disk('public')->delete($pageData->image_banner);
        }

        $pageData->update([
            'image_banner' => $request->file('image_banner')->store('equipamiento/banners', 'public'),
        ]);

        return response()->json([
            'success' => 'Banner actualizado',
            'url' => Storage::url($pageData->image_banner),
        ]);
    }

    public function removeBanner()
    {
        $pageData = EquipmentPage::first();

        if (!$pageData || !$pageData->image_banner) {
            return response()->json(['error' => 'No hay banner'], 404);
        }

        if (Storage::disk('public')->exists($pageData->image_banner)) {
            Storage::disk('public')->delete($pageData->image_banner);
        }

        $pageData->update(['image_banner' => null]);

        return response()->json(['success' => 'Banner eliminado']);
    }

    protected function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'equipment_category_id' => 'nullable|exists:equipment_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'orden' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('equipment_products', 'orden')->ignore($ignoreId),
            ],
            'visible' => 'nullable',
            'technical_sheet' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp|max:30240',
            'images' => ($ignoreId ? 'nullable' : 'required') . '|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp,svg|max:35120',
            'related_ids' => 'nullable|array',
            'related_ids.*' => 'exists:equipment_products,id',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
            'main_image_id' => 'nullable|integer',
            'remove_technical_sheet' => 'nullable',
        ]);
    }

    protected function storeImages(Request $request, EquipmentProduct $product): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $hasMain = $product->images()->where('is_main', true)->exists();

        foreach ($request->file('images') as $index => $file) {
            EquipmentProductImage::create([
                'equipment_product_id' => $product->id,
                'image' => $file->store('equipamiento/productos', 'public'),
                'is_main' => !$hasMain && $index === 0,
                'orden' => strtoupper(chr(65 + min($index, 25)) . chr(65 + min($index, 25))),
            ]);
        }
    }

    protected function syncMutualRelatedProducts(EquipmentProduct $product, array $relatedIds): void
    {
        $relatedIds = collect($relatedIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0 && $id !== $product->id)
            ->unique()
            ->values();

        $existingOutgoing = DB::table('equipment_product_related')
            ->where('equipment_product_id', $product->id)
            ->pluck('related_equipment_product_id');

        $existingIncoming = DB::table('equipment_product_related')
            ->where('related_equipment_product_id', $product->id)
            ->pluck('equipment_product_id');

        $previousIds = $existingOutgoing
            ->merge($existingIncoming)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($previousIds->isNotEmpty()) {
            DB::table('equipment_product_related')
                ->where('equipment_product_id', $product->id)
                ->whereIn('related_equipment_product_id', $previousIds)
                ->delete();

            DB::table('equipment_product_related')
                ->where('related_equipment_product_id', $product->id)
                ->whereIn('equipment_product_id', $previousIds)
                ->delete();
        }

        $rows = $relatedIds->flatMap(fn ($relatedId) => [
            [
                'equipment_product_id' => $product->id,
                'related_equipment_product_id' => $relatedId,
            ],
            [
                'equipment_product_id' => $relatedId,
                'related_equipment_product_id' => $product->id,
            ],
        ])->all();

        if ($rows) {
            DB::table('equipment_product_related')->insertOrIgnore($rows);
        }
    }

    protected function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'producto';
        $slug = $base;
        $counter = 2;

        while (EquipmentProduct::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function getNextAvailableOrder(): string
    {
        $existingOrders = EquipmentProduct::orderBy('orden')
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
