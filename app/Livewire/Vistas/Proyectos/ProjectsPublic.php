<?php

namespace App\Livewire\Vistas\Proyectos;

use App\Models\Project;
use App\Models\ProjectPage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public2')]
class ProjectsPublic extends Component
{
    public function render()
    {
        $projects = Project::with('mainImage')->visible()->ordered()->get();
        $this->ensureSlugs($projects);

        return view('livewire.vistas.proyectos.proyectos-public', [
            'banner' => ProjectPage::first(),
            'projects' => $projects,
        ]);
    }

    protected function ensureSlugs($projects): void
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
