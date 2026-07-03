<?php

namespace App\Http\Controllers\Servicios;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceCategoryImage;
use App\Models\ServicePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiciosController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $pageData = ServicePage::firstOrCreate([]);

        $services = ServiceCategory::when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('orden', 'like', "%{$search}%");
                });
            })
            ->ordered()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('livewire.servicios.partials.table', compact('services'))->render(),
                'pagination' => view('livewire.servicios.partials.pagination', compact('services'))->render(),
                'total' => $services->total(),
                'currentPage' => $services->currentPage(),
                'lastPage' => $services->lastPage(),
                'firstItem' => $services->firstItem(),
                'lastItem' => $services->lastItem(),
            ]);
        }

        return view('livewire.servicios.index', compact('services', 'pageData', 'search'));
    }

    public function create()
    {
        return view('livewire.servicios.create', [
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateService($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('servicios', 'public');
        }

        $validated['slug'] = $this->makeUniqueSlug($validated['title']);
        $validated['visible'] = $request->has('visible');
        $validated['destacado'] = $request->has('destacado');

        $serviceData = collect($validated)->except(['gallery_images', 'delete_gallery_images'])->all();
        $service = ServiceCategory::create($serviceData);
        $this->storeGalleryImages($request, $service);

        return redirect()->route('servicios.index')->with('toast', [
            'message' => 'Servicio creado correctamente',
            'type' => 'success',
        ]);
    }

    public function edit($id)
    {
        return view('livewire.servicios.edit', [
            'service' => ServiceCategory::with('images')->findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $service = ServiceCategory::with('images')->findOrFail($id);
        $validated = $this->validateService($request, $service->id);

        if ($request->hasFile('image')) {
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $validated['image'] = $request->file('image')->store('servicios', 'public');
        }

        if ($service->title !== $validated['title']) {
            $validated['slug'] = $this->makeUniqueSlug($validated['title'], $service->id);
        }

        $validated['visible'] = $request->has('visible');
        $validated['destacado'] = $request->has('destacado');

        foreach ((array) $request->input('delete_gallery_images', []) as $imageId) {
            $image = $service->images()->find($imageId);

            if ($image) {
                if (Storage::disk('public')->exists($image->image)) {
                    Storage::disk('public')->delete($image->image);
                }

                $image->delete();
            }
        }

        $serviceData = collect($validated)->except(['gallery_images', 'delete_gallery_images'])->all();
        $service->update($serviceData);
        $this->storeGalleryImages($request, $service);

        return redirect()->route('servicios.index')->with('toast', [
            'message' => 'Servicio actualizado correctamente',
            'type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $service = ServiceCategory::with('images')->findOrFail($id);

        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        foreach ($service->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Servicio eliminado correctamente',
        ]);
    }

    public function toggleVisible($id)
    {
        $service = ServiceCategory::findOrFail($id);
        $service->update(['visible' => !$service->visible]);

        return response()->json(['success' => true]);
    }

    public function toggleDestacado($id)
    {
        $service = ServiceCategory::findOrFail($id);
        $service->update(['destacado' => !$service->destacado]);

        return response()->json(['success' => true]);
    }

    public function saveBanner(Request $request)
    {
        $request->validate([
            'image_banner' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        $pageData = ServicePage::firstOrCreate([]);

        if ($pageData->image_banner && Storage::disk('public')->exists($pageData->image_banner)) {
            Storage::disk('public')->delete($pageData->image_banner);
        }

        $pageData->update([
            'image_banner' => $request->file('image_banner')->store('servicios/banners', 'public'),
        ]);

        return response()->json([
            'success' => 'Banner actualizado',
            'url' => Storage::url($pageData->image_banner),
        ]);
    }

    public function removeBanner()
    {
        $pageData = ServicePage::first();

        if (!$pageData || !$pageData->image_banner) {
            return response()->json(['error' => 'No hay banner'], 404);
        }

        if (Storage::disk('public')->exists($pageData->image_banner)) {
            Storage::disk('public')->delete($pageData->image_banner);
        }

        $pageData->update(['image_banner' => null]);

        return response()->json(['success' => 'Banner eliminado']);
    }

    protected function validateService(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'orden' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('service_categories', 'orden')->ignore($ignoreId),
            ],
            'image' => ($ignoreId ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'delete_gallery_images' => 'nullable|array',
            'delete_gallery_images.*' => 'integer',
            'visible' => 'nullable',
            'destacado' => 'nullable',
        ]);
    }

    protected function storeGalleryImages(Request $request, ServiceCategory $service): void
    {
        if (!$request->hasFile('gallery_images')) {
            return;
        }

        $nextOrder = ((int) $service->images()->max('orden')) + 1;

        foreach ($request->file('gallery_images', []) as $index => $file) {
            ServiceCategoryImage::create([
                'service_category_id' => $service->id,
                'image' => $file->store('servicios/galeria', 'public'),
                'orden' => $nextOrder + $index,
            ]);
        }
    }

    protected function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (ServiceCategory::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function getNextAvailableOrder(): string
    {
        $existingOrders = ServiceCategory::orderBy('orden')
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
