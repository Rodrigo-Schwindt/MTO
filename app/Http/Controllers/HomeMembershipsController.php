<?php

namespace App\Http\Controllers;

use App\Models\HomeMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HomeMembershipsController extends Controller
{
    public function index()
    {
        return view('livewire.home-memberships.index', [
            'memberships' => HomeMembership::ordered()->paginate(12),
        ]);
    }

    public function create()
    {
        return view('livewire.home-memberships.create', [
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateMembership($request);
        $validated['image'] = $request->file('image')->store('home-memberships', 'public');
        $validated['visible'] = $request->has('visible');

        HomeMembership::create($validated);

        return redirect()->route('home.memberships.index')->with('toast', [
            'message' => 'Logo creado correctamente',
            'type' => 'success',
        ]);
    }

    public function edit($id)
    {
        return view('livewire.home-memberships.edit', [
            'membership' => HomeMembership::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $membership = HomeMembership::findOrFail($id);
        $validated = $this->validateMembership($request, $membership->id);

        if ($request->hasFile('image')) {
            if ($membership->image && Storage::disk('public')->exists($membership->image)) {
                Storage::disk('public')->delete($membership->image);
            }

            $validated['image'] = $request->file('image')->store('home-memberships', 'public');
        }

        $validated['visible'] = $request->has('visible');

        $membership->update($validated);

        return redirect()->route('home.memberships.index')->with('toast', [
            'message' => 'Logo actualizado correctamente',
            'type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $membership = HomeMembership::findOrFail($id);

        if ($membership->image && Storage::disk('public')->exists($membership->image)) {
            Storage::disk('public')->delete($membership->image);
        }

        $membership->delete();

        return redirect()->route('home.memberships.index')->with('toast', [
            'message' => 'Logo eliminado correctamente',
            'type' => 'success',
        ]);
    }

    public function toggleVisible($id)
    {
        $membership = HomeMembership::findOrFail($id);
        $membership->update(['visible' => !$membership->visible]);

        return redirect()->route('home.memberships.index')->with('toast', [
            'message' => 'Visibilidad actualizada',
            'type' => 'success',
        ]);
    }

    protected function validateMembership(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'orden' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('home_memberships', 'orden')->ignore($ignoreId),
            ],
            'image' => ($ignoreId ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'visible' => 'nullable',
        ]);
    }

    protected function getNextAvailableOrder(): string
    {
        $existingOrders = HomeMembership::orderBy('orden')
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

        return str_repeat('a', count($chars) + 1);
    }
}
