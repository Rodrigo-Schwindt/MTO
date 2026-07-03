<?php

namespace App\Livewire\Vistas\Servicios;

use App\Models\ServiceCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class ServicioDetalle extends Component
{
    public ServiceCategory $service;

    public function mount(string $slug): void
    {
        $this->service = ServiceCategory::visible()
            ->with('images')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.vistas.servicios.servicio-detalle')
            ->with('service', $this->service);
    }
}
