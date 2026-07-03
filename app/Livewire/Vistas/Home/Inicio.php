<?php

namespace App\Livewire\Vistas\Home;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Brand;
use App\Models\HomeMembership;
use App\Models\HomeRepresentative;
use App\Models\Project;
use App\Models\ServiceCategory;
use App\Models\Sliders;
use Illuminate\Support\Str;

#[Layout('layouts.public2')]
class Inicio extends Component
{
    public function render()
    {
        $sliders = Sliders::orderBy('orden')->get();
        $representatives = HomeRepresentative::first();
        $featuredServices = ServiceCategory::visible()
            ->where('destacado', true)
            ->ordered()
            ->take(4)
            ->get();
        $featuredProjects = Project::with(['mainImage', 'images'])
            ->visible()
            ->where('destacado', true)
            ->ordered()
            ->get();
        $featuredBrands = Brand::with('category')
            ->visible()
            ->where('destacado', true)
            ->where(function ($query) {
                $query->whereNull('brand_category_id')
                    ->orWhereHas('category', fn ($category) => $category->where('visible', true));
            })
            ->ordered()
            ->get();
        $homeMemberships = HomeMembership::visible()->ordered()->get();

        $this->ensureProjectSlugs($featuredProjects);

        return view('livewire.vistas.home.inicio', [
            'sliders' => $sliders,
            'representatives' => $representatives,
            'featuredServices' => $featuredServices,
            'featuredProjects' => $featuredProjects,
            'featuredBrands' => $featuredBrands,
            'homeMemberships' => $homeMemberships,
        ]);
    }

    protected function ensureProjectSlugs($projects): void
    {
        foreach ($projects as $project) {
            if ($project->slug) {
                continue;
            }

            $base = Str::slug($project->title) ?: 'proyecto-' . $project->id;
            $slug = $base;
            $counter = 2;

            while (Project::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                $slug = "{$base}-{$counter}";
                $counter++;
            }

            $project->forceFill(['slug' => $slug])->save();
        }
    }
}
