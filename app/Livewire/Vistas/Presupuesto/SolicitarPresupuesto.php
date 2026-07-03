<?php

namespace App\Livewire\Vistas\Presupuesto;

use App\Models\EquipmentProduct;
use App\Models\PresupuestoPage;
use App\Models\PresupuestoRequest;
use App\Models\PresupuestoSystemType;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.public2')]
class SolicitarPresupuesto extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $mobile = '';
    public string $province = '';
    public string $locality = '';
    #[Url(as: 'service')]
    public string $service = '';
    public string $customService = '';

    #[Url(as: 'equipment')]
    public string $equipment = '';
    public string $customEquipment = '';
    public string $systemType = '';
    public string $customSystemType = '';
    public string $message = '';
    public $attachment;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:80',
            'mobile' => 'required|string|max:80',
            'province' => 'required|string|max:120',
            'locality' => 'required|string|max:120',
            'service' => 'nullable|string|max:255',
            'customService' => 'required_if:service,__custom|nullable|string|max:255',
            'equipment' => 'nullable|string|max:255',
            'customEquipment' => 'required_if:equipment,__custom|nullable|string|max:255',
            'systemType' => 'nullable|string|max:255',
            'customSystemType' => 'required_if:systemType,__custom|nullable|string|max:255',
            'message' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp,zip,rar|max:10240',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $attachmentPath = null;
        $attachmentName = null;
        $attachmentSize = null;

        if ($this->attachment) {
            $attachmentName = $this->attachment->getClientOriginalName();
            $attachmentSize = $this->attachment->getSize();
            $attachmentPath = $this->attachment->store('presupuesto/adjuntos', 'public');
        }

        $service = $this->service === '__custom' ? $this->customService : $this->service;
        $equipment = $this->equipment === '__custom' ? $this->customEquipment : $this->equipment;
        $systemType = $this->systemType === '__custom' ? $this->customSystemType : $this->systemType;

        $budgetRequest = PresupuestoRequest::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'province' => $this->province,
            'locality' => $this->locality,
            'service' => $service,
            'equipment' => $equipment,
            'system_type' => $systemType,
            'message' => $this->message,
            'attachment' => $attachmentPath,
            'attachment_original_name' => $attachmentName,
            'attachment_size' => $attachmentSize,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        try {
            $contact = \App\Models\Contact::first();
            if ($contact?->mail_adm) {
                Mail::raw(
                    "Nueva solicitud de presupuesto:\n\n"
                    . "Nombre: {$budgetRequest->name}\n"
                    . "Email: {$budgetRequest->email}\n"
                    . "Telefono: {$budgetRequest->phone}\n"
                    . "Celular: {$budgetRequest->mobile}\n"
                    . "Provincia: {$budgetRequest->province}\n"
                    . "Localidad: {$budgetRequest->locality}\n"
                    . "Servicio: {$budgetRequest->service}\n"
                    . "Equipamiento: {$budgetRequest->equipment}\n"
                    . "Tipo de sistema: {$budgetRequest->system_type}\n\n"
                    . "Mensaje:\n{$budgetRequest->message}",
                    function ($m) use ($contact) {
                        $m->to($contact->mail_adm);
                        $m->subject('Nueva solicitud de presupuesto');
                    }
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Presupuesto request mail error: ' . $e->getMessage());
        }

        $this->reset([
            'name',
            'email',
            'phone',
            'mobile',
            'province',
            'locality',
            'service',
            'customService',
            'equipment',
            'customEquipment',
            'systemType',
            'customSystemType',
            'message',
            'attachment',
        ]);

        $this->dispatch(
            'toast',
            title: 'Solicitud enviada',
            message: 'Tu solicitud fue enviada correctamente.',
            type: 'success'
        );
    }

    public function render()
    {
        return view('livewire.vistas.presupuesto.solicitar-presupuesto', [
            'pageData' => PresupuestoPage::first(),
            'services' => ServiceCategory::visible()->ordered()->get(),
            'equipmentProducts' => EquipmentProduct::visible()->ordered()->get(),
            'systemTypes' => PresupuestoSystemType::visible()->ordered()->get(),
        ]);
    }
}
