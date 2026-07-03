<?php

namespace App\Livewire\Vistas\Sectores;

use App\Models\Sector;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class SectorDetalle extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $sector = Sector::visible()
            ->where('slug', $this->slug)
            ->firstOrFail();

        return view('livewire.vistas.sectores.sector-detalle', [
            'sector' => $sector,
        ]);
    }
}
