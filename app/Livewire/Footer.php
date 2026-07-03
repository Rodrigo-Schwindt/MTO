<?php

namespace App\Livewire;

use App\Models\Contact;
use App\Models\Subscriber;
use Livewire\Component;

class Footer extends Component
{
    public $newsletterEmail;
    public $hasSocialMedia = false;
    public $contactData;

    public function mount(): void
    {
        $this->contactData = Contact::first();
        $this->hasSocialMedia = $this->contactData &&
            ($this->contactData->insta ||
             $this->contactData->facebook ||
             $this->contactData->linkedin ||
             $this->contactData->youtube);
    }

    public function subscribe(): void
    {
        $this->validate([
            'newsletterEmail' => 'required|email|unique:subscribers,email',
        ]);

        Subscriber::create([
            'email' => $this->newsletterEmail,
            'active' => true,
        ]);

        $this->reset('newsletterEmail');

        $this->dispatch(
            'toast',
            title: 'Suscripcion exitosa',
            message: 'Ya quedaste suscripto al newsletter.',
            type: 'success'
        );
    }

    public function render()
    {
        return view('livewire.footer');
    }
}
