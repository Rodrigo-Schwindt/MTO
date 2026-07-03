<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactInquiriesController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $inquiries = ContactInquiry::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('livewire.contact.inquiries', [
            'inquiries' => $inquiries,
            'search' => $search,
            'unreadCount' => ContactInquiry::whereNull('read_at')->count(),
        ]);
    }

    public function markRead(ContactInquiry $inquiry): RedirectResponse
    {
        $inquiry->update(['read_at' => now()]);

        return back()->with('toast', [
            'message' => 'Consulta marcada como leida',
            'type' => 'success',
        ]);
    }

    public function destroy(ContactInquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return back()->with('toast', [
            'message' => 'Consulta eliminada',
            'type' => 'success',
        ]);
    }
}
