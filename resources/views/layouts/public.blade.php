<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $currentRoute = optional(request()->route())->getName();
            $metadata = null;
            $pageTitle = $pageTitleOverride ?? 'MTO Servicios HVAC';

            if ($currentRoute === 'nosotros') {
                $metadata = \App\Models\Metadata::getForSection('nosotros');
                $pageTitle = 'Nosotros | MTO Servicios HVAC';
            } elseif ($currentRoute === 'productos') {
                $metadata = \App\Models\Metadata::getForSection('productos');
                $pageTitle = 'Equipamiento | MTO Servicios HVAC';
            } elseif ($currentRoute === 'productos.detalle') {
                $metadata = \App\Models\Metadata::getForSection('productos');
                $pageTitle = 'Equipamiento | MTO Servicios HVAC';
            } elseif ($currentRoute === 'novedades.public') {
                $metadata = \App\Models\Metadata::getForSection('novedades');
                $pageTitle = 'Novedades | MTO Servicios HVAC';
            } elseif ($currentRoute === 'novedad.detalle') {
                $metadata = \App\Models\Metadata::getForNovedad(request()->route('id'));
                $pageTitle = 'Novedades | MTO Servicios HVAC';
            } elseif ($currentRoute === 'contacto') {
                $metadata = \App\Models\Metadata::getForSection('contacto');
                $pageTitle = 'Contacto | MTO Servicios HVAC';
            } elseif ($currentRoute === 'presupuesto.public') {
                $metadata = \App\Models\Metadata::getForSection('contacto');
                $pageTitle = 'Presupuesto | MTO Servicios HVAC';
            } elseif ($currentRoute === 'catalogos') {
                $metadata = \App\Models\Metadata::getForSection('catalogos');
                $pageTitle = 'Catálogos | MTO Servicios HVAC';
            } elseif (in_array($currentRoute, ['servicios.public', 'servicios.detalle'])) {
                $metadata = \App\Models\Metadata::getForSection('servicios');
                $pageTitle = 'Servicios | MTO Servicios HVAC';
            } elseif ($currentRoute === 'sectores.public') {
                $metadata = \App\Models\Metadata::getForSection('sectores');
                $pageTitle = 'Sectores | MTO Servicios HVAC';
            } elseif ($currentRoute === 'calidad.public') {
                $metadata = \App\Models\Metadata::getForSection('calidad');
                $pageTitle = 'Calidad | MTO Servicios HVAC';
            } elseif (in_array($currentRoute, ['proyectos.public', 'proyectos.detalle'])) {
                $metadata = \App\Models\Metadata::getForSection('proyectos');
                $pageTitle = 'Proyectos | MTO Servicios HVAC';
            } elseif ($currentRoute === 'clientes.public') {
                $metadata = \App\Models\Metadata::getForSection('clientes');
                $pageTitle = 'Clientes | MTO Servicios HVAC';
            }

            $metaDescription = $metaDescriptionOverride ?? ($metadata?->description ?? 'MTO Servicios HVAC - Autopartes');
            $metaKeywords = $metaKeywordsOverride ?? ($metadata?->keywords ?? 'autopartes, ralux');
        @endphp
        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="keywords" content="{{ $metaKeywords }}">
        @isset($robots)
            <meta name="robots" content="{{ $robots }}">
        @endisset

        <meta name="app-layout" content="public" data-navigate-track>
        <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">
        
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        
        <style data-navigate-track>
            *{
                font-family: "Raleway", sans-serif;
            }
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
            
            .menu-open {
                animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }
            
            .menu-close {
                animation: slideOutRight 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }
            
            .backdrop-open {
                animation: fadeIn 0.3s ease-out forwards;
            }
            
            .backdrop-close {
                animation: fadeOut 0.2s ease-out forwards;
            }
            
            .menu-item {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .menu-item:hover {
                transform: translateX(8px);
                background-color: rgba(253, 39, 46, 0.1);
            }
            
            .hamburger-line {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .hamburger-open .line-1 {
                transform: rotate(45deg) translateY(8px);
            }
            
            .hamburger-open .line-2 {
                opacity: 0;
                transform: translateX(20px);
            }
            
            .hamburger-open .line-3 {
                transform: rotate(-45deg) translateY(-8px);
            }
            
            .nav-link {
                font-weight: 400;
                text-transform: uppercase;
                transition: all 0.2s ease;
            }

            .nav-link.active {
                font-weight: 700;
                color:#111;
            }
        </style>
    </head>
    <body class="bg-white text-[#1b1b18]" data-layout="public">
        <div 
    x-data="{ 
        show: false, 
        message: '',
        type: 'success',
        progress: 100,
        duration: 5000,
        progressInterval: null,
        init() {
            Livewire.on('show-toast', (data) => {
                const payload = Array.isArray(data) ? data[0] : data;
                this.lanzarToast(
                    payload.message || 'Operación exitosa', 
                    payload.type || 'success',
                    payload.duration || 5000
                );
            });
        },
        lanzarToast(msg, tipo, dur) {
            this.message = msg;
            this.type = tipo;
            this.duration = dur;
            this.show = true;
            this.animarProgreso();
        },
        animarProgreso() {
            this.progress = 100;
            if (this.progressInterval) clearInterval(this.progressInterval);
            
            const step = 100 / (this.duration / 100);
            this.progressInterval = setInterval(() => {
                this.progress -= step;
                if (this.progress <= 0) {
                    clearInterval(this.progressInterval);
                }
            }, 100);
            
            setTimeout(() => { 
                this.show = false;
                clearInterval(this.progressInterval);
            }, this.duration);
        }
    }"
    x-cloak
    x-show="show"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="translate-y-[-100%] opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-[-100%] opacity-0"
    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[9999]"
    style="display: none;"
>
    <div class="relative bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.15)] border border-white/50 backdrop-blur-xl overflow-hidden min-w-[320px] sm:min-w-[380px] max-w-[480px]">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-gray-200 to-gray-300">
            <div 
                class="h-full transition-all duration-100 ease-linear rounded-r-full"
                :class="{
                    'bg-gradient-to-r from-[#00C853] via-[#00E676] to-[#69F0AE]': type === 'success',
                    'bg-gradient-to-r from-[#E40044] via-[#FF1744] to-[#F50057]': type === 'error',
                    'bg-gradient-to-r from-[#2196F3] via-[#42A5F5] to-[#64B5F6]': type === 'info'
                }"
                :style="`width: ${progress}%`"
            ></div>
        </div>
        
        <div class="px-4 sm:px-5 py-4 flex items-center gap-3 sm:gap-4">
            <div class="relative flex-shrink-0">
                <div 
                    class="absolute inset-0 rounded-2xl blur-md opacity-50 animate-pulse"
                    :class="{
                        'bg-gradient-to-br from-[#00C853] to-[#00A344]': type === 'success',
                        'bg-gradient-to-br from-[#E40044] to-[#B30034]': type === 'error',
                        'bg-gradient-to-br from-[#2196F3] to-[#1976D2]': type === 'info'
                    }"
                ></div>
                <div 
                    class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-105 transition-transform"
                    :class="{
                        'bg-gradient-to-br from-[#00C853] via-[#00E676] to-[#69F0AE]': type === 'success',
                        'bg-gradient-to-br from-[#E40044] via-[#FF1744] to-[#F50057]': type === 'error',
                        'bg-gradient-to-br from-[#2196F3] via-[#42A5F5] to-[#64B5F6]': type === 'info'
                    }"
                >
                    <svg x-show="type === 'success'" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-md">
                        <path d="M20 6L9 17L4 12" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
                    <svg x-show="type === 'error'" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-md">
                        <path d="M18 6L6 18M6 6L18 18" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
                    <svg x-show="type === 'info'" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-md">
                        <path d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            
            <div class="flex-1 pr-2 min-w-0">
                <p 
                    class="font-inter text-[10px] sm:text-[11px] font-semibold uppercase tracking-wider mb-0.5"
                    :class="{
                        'text-[#00C853]': type === 'success',
                        'text-[#E40044]': type === 'error',
                        'text-[#2196F3]': type === 'info'
                    }"
                    x-text="type === 'success' ? 'Éxito' : (type === 'error' ? 'Error' : 'Información')"
                ></p>
                <p class="text-gray-900 font-inter text-[13px] sm:text-[15px] font-semibold leading-tight" x-text="message"></p>
            </div>
            
            <div class="flex-shrink-0 relative hidden sm:block">
                <div 
                    class="absolute inset-0 rounded-xl blur-sm"
                    :class="{
                        'bg-[#00C853]/10': type === 'success',
                        'bg-[#E40044]/10': type === 'error',
                        'bg-[#2196F3]/10': type === 'info'
                    }"
                ></div>
                <div 
                    class="relative w-11 h-11 rounded-xl flex items-center justify-center shadow-md transform hover:scale-110 transition-transform cursor-pointer"
                    :class="{
                        'bg-gradient-to-br from-[#00C853] to-[#00A344]/20': type === 'success',
                        'bg-gradient-to-br from-[#E40044] to-[#B30034]/20': type === 'error',
                        'bg-gradient-to-br from-[#2196F3] to-[#1976D2]/20': type === 'info'
                    }"
                    @click="show = false"
                >
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div 
            class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent to-transparent"
            :class="{
                'via-[#00C853]/30': type === 'success',
                'via-[#E40044]/30': type === 'error',
                'via-[#2196F3]/30': type === 'info'
            }"
        ></div>
    </div>
</div>
        @php
            $contactData = App\Models\Contact::first();
            $hasSocialMedia = $contactData && ($contactData->facebook || $contactData->insta || $contactData->linkedin || $contactData->youtube);
            $hasClienteGuard = array_key_exists('cliente', config('auth.guards', []));
            $clienteUser = $hasClienteGuard ? \Illuminate\Support\Facades\Auth::guard('cliente')->user() : null;
            $hasClienteProductRoute = \Illuminate\Support\Facades\Route::has('cliente.productos');
            $hasLoginModal = class_exists(\App\Livewire\Auth\LoginModal::class);
        @endphp

<header
    x-data="{
        open: false,
        scrolled: false,
        init() { this.scrolled = window.scrollY > 40; }
    }"
    @scroll.window="scrolled = window.scrollY > 40"
    class="w-full bg-white sticky top-0 z-50 transition-all duration-300"
    :class="scrolled ? 'shadow-[0_8px_32px_rgba(0,0,0,0.13)]' : 'shadow-[0_2px_8px_rgba(0,0,0,0.06)]'"
    style="margin-bottom: 10px;">

    {{-- Top info bar (desktop) --}}
    @if($contactData && ($contactData->phone_amd || $contactData->mail_adm))
    <div class="hidden lg:block overflow-hidden transition-all duration-300 ease-in-out"
         :style="scrolled ? 'max-height:0;opacity:0' : 'max-height:48px;opacity:1'">
        <div class="max-w-[1224px] mx-auto px-4 lg:px-0 flex justify-end items-center h-[32px] gap-6">
            @if($contactData->phone_amd)
            <a href="tel:{{ $contactData->phone_amd }}" class="flex items-center gap-1.5 text-[13px] text-[#555] hover:text-[#52A028] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M6.54 5C6.6 5.89 6.75 6.76 6.99 7.59L5.79 8.79C5.38 7.59 5.12 6.32 5.03 5H6.54ZM16.4 17.02C17.25 17.26 18.12 17.41 19 17.47V18.96C17.68 18.87 16.41 18.61 15.2 18.21L16.4 17.02ZM7.5 3H4C3.45 3 3 3.45 3 4C3 13.39 10.61 21 20 21C20.55 21 21 20.55 21 20V16.51C21 15.96 20.55 15.51 20 15.51C18.76 15.51 17.55 15.31 16.43 14.94C16.331 14.903 16.2256 14.886 16.12 14.89C15.86 14.89 15.61 14.99 15.41 15.18L13.21 17.38C10.3755 15.9303 8.06966 13.6245 6.62 10.79L8.82 8.59C9.1 8.31 9.18 7.92 9.07 7.57C8.69132 6.41789 8.4989 5.21274 8.5 4C8.5 3.45 8.05 3 7.5 3Z" fill="#52A028"/>
</svg>
                {{ $contactData->phone_amd }}
            </a>
            @endif
            @if($contactData->mail_adm)
            <a href="mailto:{{ $contactData->mail_adm }}" class="flex items-center gap-1.5 text-[13px] text-[#555] hover:text-[#52A028] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path d="M16.667 3.33331H3.33366C2.41318 3.33331 1.66699 4.07951 1.66699 4.99998V15C1.66699 15.9205 2.41318 16.6666 3.33366 16.6666H16.667C17.5875 16.6666 18.3337 15.9205 18.3337 15V4.99998C18.3337 4.07951 17.5875 3.33331 16.667 3.33331Z" stroke="#0B982C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M18.3337 5.83331L10.8587 10.5833C10.6014 10.7445 10.3039 10.83 10.0003 10.83C9.69673 10.83 9.39927 10.7445 9.14199 10.5833L1.66699 5.83331" stroke="#0B982C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                {{ $contactData->mail_adm }}
            </a>
            @endif
            @if($contactData->link_externo)
            <a href="{{ $contactData->link_externo }}" target="_blank" rel="noopener" class="text-[#555] hover:text-[#52A028] transition-colors" aria-label="Acceso externo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M9.175 10.825C8.39167 10.0417 8 9.1 8 8C8 6.9 8.39167 5.95833 9.175 5.175C9.95833 4.39167 10.9 4 12 4C13.1 4 14.0417 4.39167 14.825 5.175C15.6083 5.95833 16 6.9 16 8C16 9.1 15.6083 10.0417 14.825 10.825C14.0417 11.6083 13.1 12 12 12C10.9 12 9.95833 11.6083 9.175 10.825ZM4 18V17.2C4 16.6333 4.146 16.1127 4.438 15.638C4.73 15.1633 5.11733 14.8007 5.6 14.55C6.63333 14.0333 7.68333 13.646 8.75 13.388C9.81667 13.13 10.9 13.0007 12 13C13.1 12.9993 14.1833 13.1287 15.25 13.388C16.3167 13.6473 17.3667 14.0347 18.4 14.55C18.8833 14.8 19.271 15.1627 19.563 15.638C19.855 16.1133 20.0007 16.634 20 17.2V18C20 18.55 19.8043 19.021 19.413 19.413C19.0217 19.805 18.5507 20.0007 18 20H6C5.45 20 4.97933 19.8043 4.588 19.413C4.19667 19.0217 4.00067 18.5507 4 18ZM6 18H18V17.2C18 17.0167 17.9543 16.85 17.863 16.7C17.7717 16.55 17.6507 16.4333 17.5 16.35C16.6 15.9 15.6917 15.5627 14.775 15.338C13.8583 15.1133 12.9333 15.0007 12 15C11.0667 14.9993 10.1417 15.112 9.225 15.338C8.30833 15.564 7.4 15.9013 6.5 16.35C6.35 16.4333 6.229 16.55 6.137 16.7C6.045 16.85 5.99933 17.0167 6 17.2V18ZM13.413 9.413C13.8043 9.021 14 8.55 14 8C14 7.45 13.8043 6.97933 13.413 6.588C13.0217 6.19667 12.5507 6.00067 12 6C11.4493 5.99933 10.9787 6.19533 10.588 6.588C10.1973 6.98067 10.0013 7.45133 10 8C9.99867 8.54867 10.1947 9.01967 10.588 9.413C10.9813 9.80633 11.452 10.002 12 10C12.548 9.998 13.019 9.80233 13.413 9.413Z" fill="#52A028"/>
</svg>
            </a>
            @endif
        </div>
    </div>
    @endif

    
    <div class="max-w-[1224px] mx-auto px-4 lg:px-0 flex justify-between items-center h-[68px]">

        @if($contactData && $contactData->icono_2)
        <a wire:navigate href="{{ url('/') }}" class="flex-shrink-0 lg:relative transition-all duration-300"
           :class="scrolled ? 'lg:top-0' : 'lg:-top-[14px]'">
            <img src="{{ Storage::url($contactData->icono_2) }}" alt="MTO Servicios HVAC" class="h-[56px] w-auto object-contain">
        </a>
        @endif

        <nav class="hidden lg:flex items-center">
            <div class="flex items-center gap-[16px]">
                <a wire:navigate href="/nosotros" class="nav-link {{ request()->is('nosotros') ? 'active' : '' }} text-[#404041] text-[14px] font-normal leading-normal">Nosotros</a>
                <a wire:navigate href="/sectores" class="nav-link {{ request()->is('sectores*') ? 'active' : '' }} text-[#404041] text-[14px] font-normal leading-normal">Sectores</a>
                <a wire:navigate href="/servicios" class="nav-link {{ request()->is('servicios*') ? 'active' : '' }} text-[#404041] text-[14px] font-normal leading-normal">Servicios</a>
                <a wire:navigate href="/proyectos" class="nav-link {{ request()->is('proyectos*') ? 'active' : '' }} text-[#404041] text-[14px] font-normal leading-normal">Proyectos</a>
                <a wire:navigate href="/productos" class="nav-link {{ request()->is('productos*') ? 'active' : '' }} text-[#404041] text-[14px] font-normal leading-normal">Equipamiento</a>
                <a wire:navigate href="/calidad" class="nav-link {{ request()->is('calidad*') ? 'active' : '' }} text-[#404041] text-[14px] font-normal leading-normal">Calidad</a>
                <a wire:navigate href="/clientes" class="nav-link {{ request()->is('clientes*') ? 'active' : '' }} text-[#404041] text-[14px] font-normal leading-normal">Clientes</a>
                <a wire:navigate href="/novedades" class="nav-link {{ request()->is('novedades*') ? 'active' : '' }} text-[#404041] text-[14px] font-normal leading-normal">Novedades</a>
                <a wire:navigate href="/contacto" class="nav-link {{ request()->is('contacto*') ? 'active' : '' }} text-[#404041] text-[14px] font-normal leading-normal">Contacto</a>
            </div>
        </nav>

        <div class="flex items-center gap-3">
            <a wire:navigate href="{{ route('presupuesto.public') }}"
               class="hidden lg:flex justify-center items-center w-[159px] h-[41px] bg-[#52A028] text-white text-[14px] font-semibold uppercase hover:bg-[#439e33] transition-colors">
                Presupuesto
            </a>
            @if($hasClienteGuard && $clienteUser && $hasClienteProductRoute)
            <a href="{{ route('cliente.productos') }}"
               class="nav-link text-white cursor-pointer font-inter text-[14px]
                      w-[164px] h-[44px] border border-[#52A028] bg-[#52A028]
                      hidden lg:flex justify-center items-center text-center gap-2
                      hover:bg-white hover:text-[#52A028] transition-colors">
                {{ $clienteUser->nombre }}
            </a>
            @elseif($hasClienteGuard && $hasLoginModal)
            <livewire:auth.login-modal />
            @endif
            <button @click="open = true" aria-label="Abrir menú" class="lg:hidden flex flex-col justify-center items-end gap-[6px] w-[38px] h-[38px]">
                <span class="block w-8 h-[3px] bg-[#52A028] rounded"></span>
                <span class="block w-6 h-[3px] bg-[#52A028] rounded"></span>
                <span class="block w-8 h-[3px] bg-[#52A028] rounded"></span>
            </button>
        </div>
    </div>

<div 
    x-show="open"
    x-transition.opacity
    @click="open = false"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[98] lg:hidden">
</div>

<aside
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-full opacity-0"
    class="fixed top-0 right-0 h-full w-[85%] max-w-[320px] bg-white z-[99] shadow-xl lg:hidden flex flex-col">

    <div class="flex items-center justify-between px-5 h-[64px] border-b border-gray-100 flex-shrink-0">
        @if($contactData && $contactData->icono_2)
        <a wire:navigate href="{{ url('/') }}" @click="open = false">
            <img src="{{ Storage::url($contactData->icono_2) }}" alt="MTO Servicios HVAC" class="h-9 w-auto object-contain">
        </a>
        @endif
        <button @click="open = false" aria-label="Cerrar menú"
                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-[#52A028] transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto divide-y divide-gray-50">
        <a href="/nosotros" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] transition-colors {{ request()->is('nosotros') ? 'text-[#52A028] font-semibold' : 'text-[#404041] hover:text-[#52A028]' }}">
            Nosotros @if(request()->is('nosotros'))<span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>@endif
        </a>
        <a href="/sectores" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] transition-colors {{ request()->is('sectores*') ? 'text-[#52A028] font-semibold' : 'text-[#404041] hover:text-[#52A028]' }}">
            Sectores @if(request()->is('sectores*'))<span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>@endif
        </a>
        <a href="/servicios" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] transition-colors {{ request()->is('servicios*') ? 'text-[#52A028] font-semibold' : 'text-[#404041] hover:text-[#52A028]' }}">
            Servicios @if(request()->is('servicios*'))<span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>@endif
        </a>
        <a href="/proyectos" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] transition-colors {{ request()->is('proyectos*') ? 'text-[#52A028] font-semibold' : 'text-[#404041] hover:text-[#52A028]' }}">
            Proyectos @if(request()->is('proyectos*'))<span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>@endif
        </a>
        <a href="/productos" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] transition-colors {{ request()->is('productos*') ? 'text-[#52A028] font-semibold' : 'text-[#404041] hover:text-[#52A028]' }}">
            Equipamiento @if(request()->is('productos*'))<span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>@endif
        </a>
        <a href="/calidad" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] transition-colors {{ request()->is('calidad*') ? 'text-[#52A028] font-semibold' : 'text-[#404041] hover:text-[#52A028]' }}">
            Calidad @if(request()->is('calidad*'))<span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>@endif
        </a>
        <a href="/clientes" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] transition-colors {{ request()->is('clientes*') ? 'text-[#52A028] font-semibold' : 'text-[#404041] hover:text-[#52A028]' }}">
            Clientes @if(request()->is('clientes*'))<span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>@endif
        </a>
        <a href="/novedades" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] transition-colors {{ request()->is('novedades*') ? 'text-[#52A028] font-semibold' : 'text-[#404041] hover:text-[#52A028]' }}">
            Novedades @if(request()->is('novedades*'))<span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>@endif
        </a>
        <a href="/contacto" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] transition-colors {{ request()->is('contacto*') ? 'text-[#52A028] font-semibold' : 'text-[#404041] hover:text-[#52A028]' }}">
            Contacto @if(request()->is('contacto*'))<span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>@endif
        </a>
        @if($hasClienteGuard && !$clienteUser && $hasLoginModal)
        <button type="button" @click="open = false; window.dispatchEvent(new CustomEvent('open-login-modal'))"
                class="flex items-center justify-between px-5 py-4 w-full text-left text-[15px] text-[#404041] hover:text-[#52A028] transition-colors">
            Zona Privada
        </button>
        @endif
        @if($hasClienteGuard && $clienteUser && $hasClienteProductRoute)
        <a href="{{ route('cliente.productos') }}" @click="open = false"
           class="flex items-center justify-between px-5 py-4 text-[15px] text-[#52A028] font-semibold transition-colors">
            {{ $clienteUser->nombre }}
            <span class="w-1.5 h-1.5 rounded-full bg-[#52A028] flex-shrink-0"></span>
        </a>
        @endif
    </nav>

    <div class="flex-shrink-0 px-5 py-5 border-t border-gray-100 space-y-3">
        <a wire:navigate href="{{ route('presupuesto.public') }}" @click="open = false"
           class="flex items-center justify-center w-full h-10 bg-[#52A028] text-white text-[13px] font-semibold uppercase tracking-wide hover:bg-[#3f8620] transition-colors">
            Solicitar presupuesto
        </a>
        @if($contactData && $contactData->phone_amd)
        <a href="tel:{{ $contactData->phone_amd }}"
           class="flex items-center gap-2 text-[13px] text-[#555] hover:text-[#52A028] transition-colors">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="flex-shrink-0">
                <path d="M6.54 5C6.6 5.89 6.75 6.76 6.99 7.59L5.79 8.79C5.38 7.59 5.12 6.32 5.03 5H6.54ZM16.4 17.02C17.25 17.26 18.12 17.41 19 17.47V18.96C17.68 18.87 16.41 18.61 15.2 18.21L16.4 17.02ZM7.5 3H4C3.45 3 3 3.45 3 4C3 13.39 10.61 21 20 21C20.55 21 21 20.55 21 20V16.51C21 15.96 20.55 15.51 20 15.51C18.76 15.51 17.55 15.31 16.43 14.94C16.12 14.89 15.86 14.89 15.41 15.18L13.21 17.38C10.38 15.93 8.07 13.62 6.62 10.79L8.82 8.59C9.1 8.31 9.18 7.92 9.07 7.57C8.69 6.42 8.5 5.21 8.5 4C8.5 3.45 8.05 3 7.5 3Z" fill="#52A028"/>
            </svg>
            {{ $contactData->phone_amd }}
        </a>
        @endif
        @if($contactData && $contactData->mail_adm)
        <a href="mailto:{{ $contactData->mail_adm }}"
           class="flex items-center gap-2 text-[13px] text-[#555] hover:text-[#52A028] transition-colors">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="flex-shrink-0">
                <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="#52A028" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M22 6L12 13L2 6" stroke="#52A028" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ $contactData->mail_adm }}
        </a>
        @endif
        @if($contactData && $contactData->link_externo)
        <a href="{{ $contactData->link_externo }}" target="_blank" rel="noopener"
           class="flex items-center gap-2 text-[13px] text-[#555] hover:text-[#52A028] transition-colors">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="flex-shrink-0">
                <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="#52A028"/>
            </svg>
            Acceso externo
        </a>
        @endif
    </div>

</aside>

</header>


        <main class="min-h-screen">
            {{ $slot }}
        </main>
            @if($contactData && $contactData->wssp)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactData->wssp) }}" 
           target="_blank"
           style="position: fixed; bottom: 24px; right: 24px; z-index: 9999;"
           title="Contactar por WhatsApp">
            <div style="position: relative; width: 64px; height: 64px;">
                <div style="width: 64px; height: 64px; background-color: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4); cursor: pointer; transition: all 0.3s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M23.3317 19.176C22.9325 18.9774 20.9751 18.02 20.6107 17.8867C20.2463 17.7547 19.981 17.6894 19.7144 18.0867C19.4491 18.4814 18.6868 19.3747 18.4551 19.6387C18.2219 19.904 17.9902 19.936 17.5923 19.7387C17.1943 19.5387 15.9109 19.1214 14.3903 17.772C13.2073 16.7214 12.4074 15.424 12.1756 15.0267C11.9439 14.6307 12.1502 14.416 12.3498 14.2187C12.5293 14.0414 12.7477 13.756 12.9473 13.5254C13.147 13.2934 13.2126 13.128 13.3452 12.8627C13.4792 12.5987 13.4122 12.368 13.3118 12.1694C13.2126 11.9707 12.4168 10.02 12.0845 9.22671C11.7617 8.45471 11.4334 8.56004 11.1896 8.54671C10.9565 8.53604 10.6912 8.53337 10.4259 8.53337C10.1607 8.53337 9.72926 8.63204 9.36485 9.02937C8.9991 9.42537 7.97151 10.384 7.97151 12.3347C7.97151 14.284 9.39701 16.168 9.59663 16.4334C9.79625 16.6974 12.4034 20.7 16.3972 22.416C17.3484 22.824 18.0893 23.068 18.6667 23.2493C19.6206 23.552 20.4888 23.5093 21.1747 23.4067C21.9384 23.2933 23.53 22.448 23.8623 21.5227C24.1932 20.5974 24.1932 19.804 24.0941 19.6387C23.9949 19.4734 23.7296 19.3747 23.3304 19.176H23.3317ZM16.0676 29.0467H16.0623C13.6901 29.0471 11.3616 28.4125 9.32064 27.2093L8.83833 26.924L3.82499 28.2333L5.1634 23.3693L4.84855 22.8707C3.52239 20.7698 2.82057 18.3384 2.82419 15.8574C2.82687 8.59071 8.76732 2.67872 16.073 2.67872C19.6099 2.67872 22.9352 4.05205 25.4352 6.54271C26.6682 7.76482 27.6456 9.21813 28.3106 10.8186C28.9757 12.419 29.3153 14.1348 29.3097 15.8667C29.307 23.1333 23.3666 29.0467 16.0676 29.0467ZM27.3376 4.65071C25.8614 3.17195 24.1051 1.99943 22.1703 1.20112C20.2355 0.402806 18.1608 -0.00543499 16.0663 5.46366e-05C7.28556 5.46366e-05 0.136654 7.11338 0.133975 15.856C0.129906 18.6384 0.863301 21.3725 2.26016 23.7827L0 32L8.44578 29.7947C10.7821 31.0615 13.4003 31.7253 16.0609 31.7253H16.0676C24.8483 31.7253 31.9972 24.612 31.9999 15.868C32.0064 13.7844 31.5977 11.7202 30.7974 9.79475C29.9971 7.86933 28.8212 6.12094 27.3376 4.65071Z" fill="white"/>
                    </svg>
                </div>
                
                <div style="position: absolute; top: 0; left: 0; width: 64px; height: 64px; background-color: #25D366; border-radius: 50%; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; opacity: 0.2; pointer-events: none;"></div>
            </div>
        </a>

        <style>
            @keyframes pulse {
                0%, 100% {
                    opacity: 0.2;
                    transform: scale(1);
                }
                50% {
                    opacity: 0;
                    transform: scale(1.5);
                }
            }
            
            a[title="Contactar por WhatsApp"]:hover div > div {
                transform: scale(1.1);
                box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
            }
        </style>
        @endif

     @livewire('footer')  

     <div 
     x-data="{ show: false, message: '', type: '' }"
     @show-toast.window="
         message = $event.detail.message;
         type = $event.detail.type ?? 'success';
         show = true;
         setTimeout(() => show = false, 4000);
     "
     x-show="show"
     x-transition
     class="fixed top-0 right-0 px-4 py-3 rounded-lg shadow-lg text-white z-[9999]"
     :class="type === 'success' ? 'bg-green-600' : 'bg-red-600'"
 >
     <span x-text="message"></span>
 </div>
 


        <script>
            (function () {
                if (window.__layoutNavGuard) return;
                window.__layoutNavGuard = true;

                const resolved = new Map();
                const inflight = new Map();

                resolved.set(location.pathname, document.body.dataset.layout);

                function layoutOf(href) {
                    const path = new URL(href, location.href).pathname;
                    if (resolved.has(path)) return Promise.resolve(resolved.get(path));
                    if (inflight.has(path)) return inflight.get(path);
                    const p = fetch(href)
                        .then(r => r.text())
                        .then(html => {
                            const m = html.match(/name="app-layout"\s+content="([^"]+)"/);
                            const v = m ? m[1] : null;
                            resolved.set(path, v);
                            inflight.delete(path);
                            return v;
                        })
                        .catch(() => { inflight.delete(path); return null; });
                    inflight.set(path, p);
                    return p;
                }

                // Prefetch al hacer hover
                document.addEventListener('pointerover', e => {
                    const a = e.target.closest('a[wire\\:navigate]');
                    if (a?.href) layoutOf(a.href);
                });

                // Interceptar history.pushState: punto donde Livewire agrega la entrada
                // al historial justo antes del swap del DOM
                const origPushState = history.pushState.bind(history);
                history.pushState = function (state, title, url) {
                    if (url) {
                        const path = new URL(url, location.href).pathname;
                        const destLayout = resolved.get(path);
                        const currentLayout = document.body.dataset.layout;
                        if (destLayout !== undefined && destLayout !== currentLayout) {
                            // Layout diferente: cancelar SPA de Livewire, hacer full load
                            location.href = url;
                            return;
                        }
                    }
                    return origPushState(state, title, url);
                };

                // Botón atrás/adelante: siempre reload (un solo press)
                window.addEventListener('popstate', function (e) {
                    e.stopImmediatePropagation();
                    location.reload();
                });
            }());

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    Livewire.dispatch('close-search');
                    closeMobileMenu();
                }
            });

            function toggleMobileMenu() {
                const menu = document.getElementById('mobile-menu');
                const backdrop = document.getElementById('menu-backdrop');
                const hamburger = document.getElementById('hamburger-btn');
                
                const isHidden = menu.classList.contains('hidden');
                
                if (isHidden) {
                    menu.classList.remove('hidden');
                    backdrop.classList.remove('hidden');
                    
                    void menu.offsetWidth;
                    void backdrop.offsetWidth;
                    
                    menu.classList.add('menu-open');
                    backdrop.classList.add('backdrop-open');
                    hamburger.classList.add('hamburger-open');
                    document.body.style.overflow = 'hidden';
                } else {
                    closeMobileMenu();
                }
            }

            function closeMobileMenu() {
                const menu = document.getElementById('mobile-menu');
                const backdrop = document.getElementById('menu-backdrop');
                const hamburger = document.getElementById('hamburger-btn');
                
                if (!menu.classList.contains('hidden')) {
                    menu.classList.remove('menu-open');
                    menu.classList.add('menu-close');
                    backdrop.classList.remove('backdrop-open');
                    backdrop.classList.add('backdrop-close');
                    hamburger.classList.remove('hamburger-open');
                    
                    setTimeout(() => {
                        menu.classList.add('hidden');
                        backdrop.classList.add('hidden');
                        menu.classList.remove('menu-close');
                        backdrop.classList.remove('backdrop-close');
                        document.body.style.overflow = 'auto';
                    }, 300);
                }
            }

            document.getElementById('menu-backdrop')?.addEventListener('click', closeMobileMenu);

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeMobileMenu();
                }
            });

            document.getElementById('mobile-menu')?.addEventListener('wheel', function(e) {
                e.stopPropagation();
            }, { passive: true });
        </script>
    </body>
</html>
