<?php

namespace App\Livewire\Vistas\Equipamiento;

use App\Models\EquipmentProduct;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class EquipmentDetail extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $product = EquipmentProduct::with(['images', 'relatedProducts.mainImage', 'relatedProducts.images'])
            ->visible()
            ->where('slug', $this->slug)
            ->firstOrFail();

        $isAutoRelated = false;
        $relatedProducts = $product->relatedProducts;

        if ($relatedProducts->isEmpty() && $product->equipment_category_id) {
            $relatedProducts = EquipmentProduct::with(['mainImage', 'images'])
                ->visible()
                ->where('equipment_category_id', $product->equipment_category_id)
                ->where('id', '!=', $product->id)
                ->ordered()
                ->limit(8)
                ->get();
            $isAutoRelated = $relatedProducts->isNotEmpty();
        }

        return view('livewire.vistas.equipamiento.equipamiento-detalle', [
            'product'         => $product,
            'relatedProducts' => $relatedProducts,
            'isAutoRelated'   => $isAutoRelated,
        ]);
    }
}
