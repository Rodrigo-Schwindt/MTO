<?php

namespace App\Livewire\Vistas\Servicios;

use App\Models\ServiceCategory;
use App\Models\ServicePage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public2')]
class ServiciosPublic extends Component
{
    public function render()
    {
        return view('livewire.vistas.servicios.servicios-public', [
            'banner' => ServicePage::first(),
            'services' => ServiceCategory::visible()->ordered()->get(),
        ]);
    }
}
