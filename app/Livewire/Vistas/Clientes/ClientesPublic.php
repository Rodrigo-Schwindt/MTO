<?php

namespace App\Livewire\Vistas\Clientes;

use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\BrandPage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public2')]
class ClientesPublic extends Component
{
    public function render()
    {
        $categories = BrandCategory::visible()->ordered()->get();

        $brands = Brand::with('category')
            ->visible()
            ->where(function ($query) {
                $query->whereNull('brand_category_id')
                    ->orWhereHas('category', fn ($category) => $category->where('visible', true));
            })
            ->ordered()
            ->get();

        return view('livewire.vistas.clientes.clientes-public', [
            'pageData' => BrandPage::first(),
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}
