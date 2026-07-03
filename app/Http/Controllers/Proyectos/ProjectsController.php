<?php

namespace App\Http\Controllers\Proyectos;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\ProjectPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $pageData = ProjectPage::firstOrCreate([]);

        $projects = Project::with('mainImage')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('orden', 'like', "%{$search}%");
                });
            })
            ->ordered()
            ->paginate(12);

        return view('livewire.proyectos.index', compact('projects', 'pageData', 'search'));
    }

    public function create()
    {
        return view('livewire.proyectos.create', [
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProject($request);

        $project = Project::create([
            'title' => $validated['title'],
            'slug' => $this->makeUniqueSlug($validated['title']),
            'description' => $validated['description'] ?? null,
            'orden' => $validated['orden'] ?? null,
            'visible' => $request->has('visible'),
            'destacado' => $request->has('destacado'),
        ]);

        $this->storeImages($request, $project);

        return redirect()->route('proyectos.index')->with('toast', [
            'message' => 'Proyecto creado correctamente',
            'type' => 'success',
        ]);
    }

    public function edit($id)
    {
        return view('livewire.proyectos.edit', [
            'project' => Project::with('images')->findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::with('images')->findOrFail($id);
        $validated = $this->validateProject($request, $project->id);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'orden' => $validated['orden'] ?? null,
            'visible' => $request->has('visible'),
            'destacado' => $request->has('destacado'),
        ];

        if ($project->title !== $validated['title']) {
            $data['slug'] = $this->makeUniqueSlug($validated['title'], $project->id);
        }

        $project->update($data);

        foreach ((array) $request->input('delete_images', []) as $imageId) {
            $image = $project->images()->find($imageId);
            if ($image) {
                Storage::disk('public')->delete($image->image);
                $image->delete();
            }
        }

        if ($request->filled('main_image_id')) {
            $project->images()->update(['is_main' => false]);
            $project->images()->where('id', $request->input('main_image_id'))->update(['is_main' => true]);
        }

        $this->storeImages($request, $project, !$project->images()->where('is_main', true)->exists());

        if (!$project->images()->where('is_main', true)->exists()) {
            $firstImage = $project->images()->orderBy('id')->first();
            $firstImage?->update(['is_main' => true]);
        }

        return redirect()->route('proyectos.index')->with('toast', [
            'message' => 'Proyecto actualizado correctamente',
            'type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $project = Project::with('images')->findOrFail($id);

        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $project->delete();

        return redirect()->route('proyectos.index')->with('toast', [
            'message' => 'Proyecto eliminado correctamente',
            'type' => 'success',
        ]);
    }

    public function saveBanner(Request $request)
    {
        $request->validate([
            'banner_image' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        $pageData = ProjectPage::firstOrCreate([]);

        if ($pageData->banner_image) {
            Storage::disk('public')->delete($pageData->banner_image);
        }

        $pageData->update([
            'banner_image' => $request->file('banner_image')->store('proyectos/banners', 'public'),
        ]);

        return redirect()->route('proyectos.index')->with('toast', [
            'message' => 'Banner actualizado',
            'type' => 'success',
        ]);
    }

    public function removeBanner()
    {
        $pageData = ProjectPage::first();

        if ($pageData?->banner_image) {
            Storage::disk('public')->delete($pageData->banner_image);
            $pageData->update(['banner_image' => null]);
        }

        return redirect()->route('proyectos.index')->with('toast', [
            'message' => 'Banner eliminado',
            'type' => 'success',
        ]);
    }

    protected function validateProject(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'orden' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('projects', 'orden')->ignore($ignoreId),
            ],
            'visible' => 'nullable',
            'destacado' => 'nullable',
            'main_image' => ($ignoreId ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'main_image_id' => 'nullable|integer',
            'delete_images' => 'nullable|array',
        ]);
    }

    protected function storeImages(Request $request, Project $project, bool $markFirstAsMain = true): void
    {
        $files = [];

        if ($request->hasFile('main_image')) {
            $files[] = ['file' => $request->file('main_image'), 'is_main' => true];
            $project->images()->update(['is_main' => false]);
        }

        foreach ($request->file('gallery_images', []) as $file) {
            $files[] = ['file' => $file, 'is_main' => false];
        }

        foreach ($files as $index => $fileData) {
            ProjectImage::create([
                'project_id' => $project->id,
                'image' => $fileData['file']->store('proyectos', 'public'),
                'is_main' => $fileData['is_main'] || ($markFirstAsMain && $index === 0),
                'orden' => $project->images()->count() + $index,
            ]);
        }
    }

    protected function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (Project::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function getNextAvailableOrder(): string
    {
        $existingOrders = Project::orderBy('orden')
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
