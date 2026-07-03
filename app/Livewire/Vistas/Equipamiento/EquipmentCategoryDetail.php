<?php

namespace App\Livewire\Vistas\Equipamiento;

use App\Models\EquipmentCategory;
use App\Models\EquipmentPage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public2')]
class EquipmentCategoryDetail extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $category = EquipmentCategory::where('slug', $this->slug)
            ->where('visible', true)
            ->firstOrFail();

        $products = $category->products()
            ->with(['mainImage', 'images'])
            ->visible()
            ->get();

        return view('livewire.vistas.equipamiento.equipamiento-categoria', [
            'pageData' => EquipmentPage::first(),
            'category' => $category,
            'products' => $products,
        ]);
    }
}
