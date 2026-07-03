<?php

namespace App\Livewire\Vistas\Sectores;

use App\Models\Sector;
use App\Models\SectorPage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public2')]
class SectoresPublic extends Component
{
    public function render()
    {
        return view('livewire.vistas.sectores.sectores-public', [
            'pageData' => SectorPage::first(),
            'sectors' => Sector::visible()->ordered()->get(),
        ]);
    }
}
