<?php

namespace App\Livewire\Vistas\Equipamiento;

use App\Models\EquipmentCategory;
use App\Models\EquipmentPage;
use App\Models\EquipmentProduct;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.public2')]
class EquipmentPublic extends Component
{
    #[Url(except: '')]
    public string $search = '';

    public int $perPage = 16;
    public int $page = 1;

    public function updatingSearch(): void
    {
        $this->page = 1;
    }

    public function setPerPage(int $val): void
    {
        $this->perPage = in_array($val, [8, 16]) ? $val : 16;
        $this->page = 1;
    }

    public function gotoPage(int $p): void
    {
        $this->page = $p;
    }

    public function nextPage(): void
    {
        $this->page++;
    }

    public function previousPage(): void
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function render()
    {
        $search = trim($this->search);

        $categories = EquipmentCategory::visible()->ordered()
            ->when($search !== '', fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->get()
            ->map(fn ($cat) => ['type' => 'category', 'orden' => $cat->orden ?? 'ZZ', 'item' => $cat]);

        $uncategorized = EquipmentProduct::with(['mainImage', 'images'])
            ->visible()
            ->whereNull('equipment_category_id')
            ->when($search !== '', fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->ordered()
            ->get()
            ->map(fn ($p) => ['type' => 'product', 'orden' => $p->orden ?? 'ZZ', 'item' => $p]);

        $allItems = $categories->concat($uncategorized)
            ->sortBy([['orden', 'asc'], ['item.title', 'asc']])
            ->values();

        $total   = $allItems->count();
        $perPage = $this->perPage;
        $page    = max(1, min($this->page, max(1, (int) ceil($total / $perPage))));

        $paginated = new LengthAwarePaginator(
            $allItems->slice(($page - 1) * $perPage, $perPage)->values(),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('livewire.vistas.equipamiento.equipamiento-public', [
            'pageData' => EquipmentPage::first(),
            'items'    => $paginated,
            'search'   => $search,
        ]);
    }
}
