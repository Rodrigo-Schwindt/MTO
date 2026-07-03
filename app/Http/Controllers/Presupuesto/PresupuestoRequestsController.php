<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\PresupuestoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresupuestoRequestsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $requests = PresupuestoRequest::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhere('province', 'like', "%{$search}%")
                        ->orWhere('locality', 'like', "%{$search}%")
                        ->orWhere('service', 'like', "%{$search}%")
                        ->orWhere('equipment', 'like', "%{$search}%")
                        ->orWhere('system_type', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('livewire.presupuesto.requests', [
            'requests' => $requests,
            'search' => $search,
            'unreadCount' => PresupuestoRequest::whereNull('read_at')->count(),
        ]);
    }

    public function markRead(PresupuestoRequest $requestModel): RedirectResponse
    {
        $requestModel->update(['read_at' => now()]);

        return back()->with('toast', [
            'message' => 'Solicitud marcada como leida',
            'type' => 'success',
        ]);
    }

    public function destroy(PresupuestoRequest $requestModel): RedirectResponse
    {
        if ($requestModel->attachment) {
            Storage::disk('public')->delete($requestModel->attachment);
        }

        $requestModel->delete();

        return back()->with('toast', [
            'message' => 'Solicitud eliminada',
            'type' => 'success',
        ]);
    }
}
