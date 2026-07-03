<?php

namespace App\Http\Controllers\Calidad;

use App\Http\Controllers\Controller;
use App\Models\QualityDownload;
use App\Models\QualityPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QualityController extends Controller
{
    public function index()
    {
        $pageData = QualityPage::firstOrCreate([], [
            'title' => 'Politicas de calidad',
        ]);

        return view('livewire.calidad.index', [
            'pageData' => $pageData,
            'downloads' => QualityDownload::ordered()->get(),
            'nextOrder' => $this->getNextAvailableOrder(),
        ]);
    }

    public function save(Request $request)
    {
        $pageData = QualityPage::firstOrCreate([]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'image_banner' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'downloads' => 'nullable|array',
            'downloads.*.title' => 'required_with:downloads|string|max:255',
            'downloads.*.orden' => 'nullable|string|max:10',
            'download_files' => 'nullable|array',
            'download_files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp|max:10240',
            'download_images' => 'nullable|array',
            'download_images.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'delete_downloads' => 'nullable|array',
            'delete_downloads.*' => 'integer|exists:quality_downloads,id',
            'new_downloads' => 'nullable|array',
            'new_downloads.*.title' => 'nullable|string|max:255',
            'new_downloads.*.orden' => 'nullable|string|max:10',
            'new_download_files' => 'nullable|array',
            'new_download_files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp|max:10240',
            'new_download_images' => 'nullable|array',
            'new_download_images.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ]);

        $orderValues = collect($request->input('downloads', []))
            ->reject(fn ($data, $id) => in_array((int) $id, array_map('intval', $request->input('delete_downloads', [])), true))
            ->pluck('orden')
            ->merge(collect($request->input('new_downloads', []))->pluck('orden'))
            ->map(fn ($order) => strtoupper(trim((string) $order)))
            ->filter()
            ->values();

        if ($orderValues->duplicates()->isNotEmpty()) {
            return back()
                ->withErrors(['downloads' => 'No puede haber descargas con el mismo orden.'])
                ->withInput();
        }

        if ($request->hasFile('image')) {
            $this->deleteIfExists($pageData->image);
            $validated['image'] = $request->file('image')->store('calidad/imagenes', 'public');
        }

        if ($request->hasFile('image_banner')) {
            $this->deleteIfExists($pageData->image_banner);
            $validated['image_banner'] = $request->file('image_banner')->store('calidad/banners', 'public');
        }

        $pageData->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? $pageData->image,
            'image_banner' => $validated['image_banner'] ?? $pageData->image_banner,
        ]);

        foreach ($request->input('delete_downloads', []) as $downloadId) {
            $download = QualityDownload::find($downloadId);
            if ($download) {
                $this->deleteIfExists($download->file);
                $this->deleteIfExists($download->image);
                $download->delete();
            }
        }

        $existingDownloadIds = collect(array_keys($request->input('downloads', [])))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        if ($existingDownloadIds->isNotEmpty()) {
            QualityDownload::whereIn('id', $existingDownloadIds)->update(['orden' => null]);
        }

        foreach ($request->input('downloads', []) as $downloadId => $data) {
            $download = QualityDownload::find($downloadId);
            if (!$download) {
                continue;
            }

            $payload = [
                'title' => $data['title'],
                'orden' => $data['orden'] ?? null,
            ];

            if ($request->hasFile("download_files.{$downloadId}")) {
                $file = $request->file("download_files.{$downloadId}");
                $this->deleteIfExists($download->file);
                $payload = array_merge($payload, $this->filePayload($file));
            }

            if ($request->hasFile("download_images.{$downloadId}")) {
                $image = $request->file("download_images.{$downloadId}");
                $this->deleteIfExists($download->image);
                $payload['image'] = $image->store('calidad/descargas/imagenes', 'public');
            }

            $download->update($payload);
        }

        foreach ($request->input('new_downloads', []) as $index => $data) {
            $file = $request->file("new_download_files.{$index}");
            $title = trim((string) ($data['title'] ?? ''));

            if (!$file && $title === '') {
                continue;
            }

            if (!$file || $title === '') {
                return back()
                    ->withErrors(['new_downloads' => 'Cada descarga nueva necesita titulo y archivo.'])
                    ->withInput();
            }

            $payload = array_merge([
                'title' => $title,
                'orden' => $data['orden'] ?? null,
            ], $this->filePayload($file));

            if ($request->hasFile("new_download_images.{$index}")) {
                $payload['image'] = $request->file("new_download_images.{$index}")
                    ->store('calidad/descargas/imagenes', 'public');
            }

            QualityDownload::create($payload);
        }

        return redirect()->route('quality.index')->with('toast', [
            'message' => 'Calidad actualizada correctamente',
            'type' => 'success',
        ]);
    }

    public function removeBanner()
    {
        $pageData = QualityPage::first();

        if (!$pageData || !$pageData->image_banner) {
            return redirect()->route('quality.index');
        }

        $this->deleteIfExists($pageData->image_banner);
        $pageData->update(['image_banner' => null]);

        return redirect()->route('quality.index')->with('toast', [
            'message' => 'Banner eliminado',
            'type' => 'success',
        ]);
    }

    public function removeImage()
    {
        $pageData = QualityPage::first();

        if (!$pageData || !$pageData->image) {
            return redirect()->route('quality.index');
        }

        $this->deleteIfExists($pageData->image);
        $pageData->update(['image' => null]);

        return redirect()->route('quality.index')->with('toast', [
            'message' => 'Imagen eliminada',
            'type' => 'success',
        ]);
    }

    protected function filePayload($file): array
    {
        return [
            'file' => $file->store('calidad/descargas', 'public'),
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
        ];
    }

    protected function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function getNextAvailableOrder(): string
    {
        $existingOrders = QualityDownload::orderBy('orden')
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
