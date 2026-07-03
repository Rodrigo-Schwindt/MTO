<?php

namespace App\Livewire\Vistas\Proyectos;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class ProjectDetail extends Component
{
    public Project $project;

    public function mount(string $slug): void
    {
        $this->project = Project::with('images')
            ->visible()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.vistas.proyectos.proyecto-detalle')
            ->with('project', $this->project);
    }
}
