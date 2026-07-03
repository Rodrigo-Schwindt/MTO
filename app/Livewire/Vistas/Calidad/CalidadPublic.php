<?php

namespace App\Livewire\Vistas\Calidad;

use App\Models\QualityDownload;
use App\Models\QualityPage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public2')]
class CalidadPublic extends Component
{
    public function render()
    {
        return view('livewire.vistas.calidad.calidad-public', [
            'pageData' => QualityPage::first(),
            'downloads' => QualityDownload::ordered()->get(),
        ]);
    }
}
