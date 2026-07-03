<?php

namespace App\Http\Controllers;

use App\Models\HomeRepresentative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeRepresentativesController extends Controller
{
    public function index()
    {
        $representatives = HomeRepresentative::first();
        $logos = collect($representatives?->logos ?? [])->filter()->values();

        return view('livewire.home-representatives', [
            'representatives' => $representatives,
            'logos' => $logos,
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'new_logos'   => 'nullable|array',
            'new_logos.*' => 'file|mimes:jpg,jpeg,png,webp,gif,svg|max:4096',
        ]);

        $representatives = HomeRepresentative::first() ?? new HomeRepresentative();
        $representatives->title = $request->input('title');

        $currentLogos = $representatives->logos ?? [];
        $removeLogos  = $request->input('remove_logos', []);

        foreach ($removeLogos as $path) {
            Storage::disk('public')->delete($path);
        }

        $keepLogos = array_values(
            array_filter($currentLogos, fn ($p) => !in_array($p, $removeLogos))
        );

        if ($request->hasFile('new_logos')) {
            foreach ($request->file('new_logos') as $file) {
                $keepLogos[] = $file->store('home-representatives', 'public');
            }
        }

        $representatives->logos = $keepLogos;
        $representatives->save();

        return redirect()
            ->route('home.representatives.index')
            ->with('success', 'Representantes guardados correctamente');
    }
}
