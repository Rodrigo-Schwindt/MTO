<?php

namespace App\Http\Controllers\Sectores;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use App\Models\SectorPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SectorsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $pageData = SectorPage::firstOrCreate([]);

        $sectors = Sector::when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('orden', 'like', "%{$search}%");
                });
            })
            ->ordered()
            ->paginate(12);

        return view('livewire.sectores.index', compact('sectors', 'pageData', 'search'));
    }

    public function create()
    {
        return view('livewire.sectores.create', [
            'sector' => null,
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateSector($request);
        $validated['visible'] = $request->has('visible');
        $validated['image'] = $request->file('image')->store('sectores/cards', 'public');
        $validated['slug'] = $this->makeUniqueSlug($validated['title']);

        Sector::create($validated);

        return redirect()->route('sectors.index')->with('toast', [
            'message' => 'Sector creado correctamente',
            'type' => 'success',
        ]);
    }

    public function edit($id)
    {
        return view('livewire.sectores.edit', [
            'sector' => Sector::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $sector = Sector::findOrFail($id);
        $validated = $this->validateSector($request, $sector->id);
        $validated['visible'] = $request->has('visible');

        if ($request->hasFile('image')) {
            if ($sector->image && Storage::disk('public')->exists($sector->image)) {
                Storage::disk('public')->delete($sector->image);
            }

            $validated['image'] = $request->file('image')->store('sectores/cards', 'public');
        }

        if ($sector->title !== $validated['title']) {
            $validated['slug'] = $this->makeUniqueSlug($validated['title'], $sector->id);
        }

        $sector->update($validated);

        return redirect()->route('sectors.index')->with('toast', [
            'message' => 'Sector actualizado correctamente',
            'type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $sector = Sector::findOrFail($id);

        if ($sector->image && Storage::disk('public')->exists($sector->image)) {
            Storage::disk('public')->delete($sector->image);
        }

        $sector->delete();

        return redirect()->route('sectors.index')->with('toast', [
            'message' => 'Sector eliminado correctamente',
            'type' => 'success',
        ]);
    }

    public function toggleVisible($id)
    {
        $sector = Sector::findOrFail($id);
        $sector->update(['visible' => !$sector->visible]);

        return redirect()->route('sectors.index')->with('toast', [
            'message' => 'Visibilidad actualizada',
            'type' => 'success',
        ]);
    }

    public function saveBanner(Request $request)
    {
        $request->validate([
            'image_banner' => 'required|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        $pageData = SectorPage::firstOrCreate([]);

        if ($pageData->image_banner && Storage::disk('public')->exists($pageData->image_banner)) {
            Storage::disk('public')->delete($pageData->image_banner);
        }

        $pageData->update([
            'image_banner' => $request->file('image_banner')->store('sectores/banners', 'public'),
        ]);

        return response()->json([
            'success' => 'Banner actualizado',
            'url' => Storage::url($pageData->image_banner),
        ]);
    }

    public function removeBanner()
    {
        $pageData = SectorPage::first();

        if (!$pageData || !$pageData->image_banner) {
            return response()->json(['error' => 'No hay banner'], 404);
        }

        if (Storage::disk('public')->exists($pageData->image_banner)) {
            Storage::disk('public')->delete($pageData->image_banner);
        }

        $pageData->update(['image_banner' => null]);

        return response()->json(['success' => 'Banner eliminado']);
    }

    protected function validateSector(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'orden' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('sectors', 'orden')->ignore($ignoreId),
            ],
            'visible' => 'nullable',
            'image' => ($ignoreId ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);
    }

    protected function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (Sector::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function getNextAvailableOrder(): string
    {
        $existingOrders = Sector::orderBy('orden')
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
