<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\PresupuestoPage;
use App\Models\PresupuestoSystemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PresupuestoAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $pageData = PresupuestoPage::firstOrCreate([]);

        $systemTypes = PresupuestoSystemType::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('orden', 'like', "%{$search}%");
                });
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('livewire.presupuesto.index', [
            'pageData' => $pageData,
            'systemTypes' => $systemTypes,
            'search' => $search,
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function storeSystemType(Request $request)
    {
        $validated = $this->validateSystemType($request);
        $validated['visible'] = $request->has('visible');

        PresupuestoSystemType::create($validated);

        return redirect()->route('presupuesto.admin.index')->with('toast', [
            'message' => 'Tipo de sistema creado correctamente',
            'type' => 'success',
        ]);
    }

    public function updateSystemType(Request $request, PresupuestoSystemType $systemType)
    {
        $validated = $this->validateSystemType($request, $systemType->id);
        $validated['visible'] = $request->has('visible');

        $systemType->update($validated);

        return redirect()->route('presupuesto.admin.index')->with('toast', [
            'message' => 'Tipo de sistema actualizado',
            'type' => 'success',
        ]);
    }

    public function destroySystemType(PresupuestoSystemType $systemType)
    {
        $systemType->delete();

        return redirect()->route('presupuesto.admin.index')->with('toast', [
            'message' => 'Tipo de sistema eliminado',
            'type' => 'success',
        ]);
    }

    public function toggleSystemType(PresupuestoSystemType $systemType)
    {
        $systemType->update(['visible' => !$systemType->visible]);

        return redirect()->route('presupuesto.admin.index')->with('toast', [
            'message' => 'Visibilidad actualizada',
            'type' => 'success',
        ]);
    }

    public function saveBanner(Request $request)
    {
        $request->validate([
            'image_banner' => 'required|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        $pageData = PresupuestoPage::firstOrCreate([]);

        if ($pageData->image_banner) {
            Storage::disk('public')->delete($pageData->image_banner);
        }

        $pageData->update([
            'image_banner' => $request->file('image_banner')->store('presupuesto/banners', 'public'),
        ]);

        return redirect()->route('presupuesto.admin.index')->with('toast', [
            'message' => 'Banner actualizado',
            'type' => 'success',
        ]);
    }

    public function removeBanner()
    {
        $pageData = PresupuestoPage::first();

        if ($pageData?->image_banner) {
            Storage::disk('public')->delete($pageData->image_banner);
            $pageData->update(['image_banner' => null]);
        }

        return redirect()->route('presupuesto.admin.index')->with('toast', [
            'message' => 'Banner eliminado',
            'type' => 'success',
        ]);
    }

    protected function validateSystemType(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'orden' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('presupuesto_system_types', 'orden')->ignore($ignoreId),
            ],
            'visible' => 'nullable',
        ]);
    }

    protected function getNextAvailableOrder(): string
    {
        $existingOrders = PresupuestoSystemType::orderBy('orden')
            ->pluck('orden')
            ->map(fn ($orden) => strtolower((string) $orden))
            ->filter()
            ->toArray();

        $currentOrder = 'aa';

        while (in_array($currentOrder, $existingOrders, true)) {
            $currentOrder = $this->incrementOrder($currentOrder);
        }

        return strtoupper($currentOrder);
    }

    protected function incrementOrder(string $order): string
    {
        $chars = str_split(strtolower($order));

        for ($i = count($chars) - 1; $i >= 0; $i--) {
            if ($chars[$i] !== 'z') {
                $chars[$i] = chr(ord($chars[$i]) + 1);
                return implode('', $chars);
            }

            $chars[$i] = 'a';
        }

        return $order . 'a';
    }
}
