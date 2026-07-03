@php
    use App\Models\Contact;
    $contact = Contact::first();
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <title>{{ $title ?? 'Panel Administrativo' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScriptConfig

    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>window.CKEDITOR_BASEPATH = 'https://cdn.ckeditor.com/4.22.1/standard/';</script>
    <script>document.addEventListener('DOMContentLoaded', function() { if (window.CKEDITOR) CKEDITOR.config.versionCheck = false; });</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* =============================================
           VARIABLES DE COLOR (blanco · #52A028 · #B4CB19)
           ============================================= */
        :root {
            --p:  #52A028;
            --ph: #3d7a1e;
            --pl: #eef7e8;
            --a:  #B4CB19;
            --ah: #97ab14;
            --sb: #e3f0db;   /* sidebar border */
            --nh: #f2faea;   /* nav hover bg */
        }

        /* ---- Scrollbar ---- */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: var(--sb); border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: var(--p); }

        /* ---- Nav items ---- */
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            font-size: 0.875rem; font-weight: 500;
            color: #4b5563; text-decoration: none; width: 100%;
            transition: background 0.15s, color 0.15s;
            border: none; background: none; cursor: pointer;
        }
        .nav-item:hover { background: var(--nh); color: var(--p); }
        .nav-item.active-link {
            background: var(--p); color: #fff !important;
            box-shadow: 0 2px 8px rgba(82,160,40,0.25);
        }
        .nav-item.active-link svg { color: #fff; }

        /* ---- Submenu items ---- */
        .sub-item {
            display: flex; align-items: center; gap: 8px;
            padding: 7px 12px 7px 42px; border-radius: 7px;
            font-size: 0.82rem; font-weight: 400;
            color: #6b7280; text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }
        .sub-item::before {
            content: ''; width: 5px; height: 5px; border-radius: 50%;
            background: currentColor; opacity: 0.4; flex-shrink: 0;
        }
        .sub-item:hover { background: var(--nh); color: var(--p); }
        .sub-item.sub-active { color: var(--p); font-weight: 600; background: var(--pl); }

        /* ---- Divider ---- */
        .nav-divider { border-top: 1px solid var(--sb); margin: 8px 0; }

        /* ---- Fade animation ---- */
        @keyframes adminFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .admin-fade { animation: adminFadeIn 0.28s ease; }
        /* backward compat for existing views */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.3s ease; }

        /* =============================================
           COMPONENTES GLOBALES DEL ADMIN
           ============================================= */

        /* Botones */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 18px; background: var(--p); color: #fff;
            border-radius: 8px; font-size: 0.875rem; font-weight: 500;
            border: none; cursor: pointer; text-decoration: none;
            transition: background 0.15s, transform 0.1s;
        }
        .btn-primary:hover { background: var(--ph); }
        .btn-primary:active { transform: scale(0.98); }
        .btn-primary:disabled { opacity: 0.55; cursor: not-allowed; }

        .btn-secondary {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 18px; background: #fff; color: #374151;
            border-radius: 8px; font-size: 0.875rem; font-weight: 500;
            border: 1px solid #d1d5db; cursor: pointer; text-decoration: none;
            transition: background 0.15s;
        }
        .btn-secondary:hover { background: #f9fafb; }

        .btn-danger {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 18px; background: #dc2626; color: #fff;
            border-radius: 8px; font-size: 0.875rem; font-weight: 500;
            border: none; cursor: pointer; text-decoration: none;
            transition: background 0.15s;
        }
        .btn-danger:hover { background: #b91c1c; }

        /* Cards */
        .admin-card {
            background: #fff; border: 1px solid #e5e7eb;
            border-radius: 10px; padding: 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        /* Inputs */
        .admin-input {
            width: 100%; border: 1px solid #d1d5db;
            border-radius: 8px; padding: 8px 12px;
            font-size: 0.875rem; background: #fff; color: #1f2937;
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .admin-input:focus {
            border-color: var(--p);
            box-shadow: 0 0 0 3px rgba(82,160,40,0.12);
        }

        /* Hint de carga de archivo */
        .upload-hint {
            margin-top: 7px; font-size: 0.72rem; color: #6b7280;
            line-height: 1.6; padding: 7px 11px;
            background: #f9fafb; border-radius: 6px;
            border: 1px solid #f3f4f6;
        }
        .upload-hint strong { color: #374151; }

        /* Tabla */
        .admin-table thead tr { background: #f9fafb; }
        .admin-table th {
            font-size: 0.71rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.06em; color: #6b7280; padding: 11px 16px;
        }
        .admin-table td { padding: 12px 16px; font-size: 0.875rem; color: #374151; }
        .admin-table tbody tr { border-bottom: 1px solid #f3f4f6; }
        .admin-table tbody tr:last-child { border-bottom: none; }
        .admin-table tbody tr:hover td { background: #fafaf8; }

        /* Badges */
        .badge-on  { display:inline-flex; padding:2px 9px; border-radius:20px; font-size:0.72rem; font-weight:600; background:#dcfce7; color:#15803d; }
        .badge-off { display:inline-flex; padding:2px 9px; border-radius:20px; font-size:0.72rem; font-weight:600; background:#f3f4f6; color:#6b7280; }
        .badge-accent { display:inline-flex; padding:2px 9px; border-radius:20px; font-size:0.72rem; font-weight:600; background:#fef9c3; color:#854d0e; }

        /* Input[type=checkbox] accent */
        input[type="checkbox"] { accent-color: var(--p); }

        /* Alert */
        .admin-alert-ok  { padding:11px 15px; border-radius:8px; background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; font-size:0.875rem; }
        .admin-alert-err { padding:11px 15px; border-radius:8px; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; font-size:0.875rem; }
    </style>
</head>

<body class="bg-[#f7faf4]" x-data="{ sidebarOpen: true }">

    <!-- ========== SIDEBAR ========== -->
    <aside
        class="w-72 bg-white flex flex-col fixed inset-y-0 left-0 z-40 transition-transform duration-300"
        style="border-right: 1px solid var(--sb);"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-72'"
    >
        <!-- Logo -->
        <div class="flex items-center justify-center py-4 px-5" style="border-bottom: 1px solid var(--sb);">
            @if($contact && $contact->icono_2)
                <a href="{{ url('/') }}" target="_blank" class="flex items-center justify-center">
                    <img src="{{ Storage::url($contact->icono_2) }}" class="h-[68px] w-auto object-contain" alt="Logo">
                </a>
            @else
                <div class="h-11 w-40 rounded-lg flex items-center justify-center text-white text-sm font-bold tracking-widest"
                     style="background: linear-gradient(135deg, var(--p), var(--a));">
                    ADMIN
                </div>
            @endif
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-3 overflow-y-auto custom-scroll space-y-0.5">

            {{-- INICIO --}}
            <div x-data="{ open: {{ request()->routeIs('sliders.*', 'home.representatives.*', 'nosotros.home.*', 'home.memberships.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="nav-item justify-between" :class="open ? 'text-[#52A028]' : ''">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Inicio</span>
                    </div>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-0.5">
                    <a href="{{ route('sliders.index') }}" class="sub-item {{ request()->routeIs('sliders.*') ? 'sub-active' : '' }}">Sliders</a>
                    <a href="{{ route('home.representatives.index') }}" class="sub-item {{ request()->routeIs('home.representatives.*') ? 'sub-active' : '' }}">Representantes</a>
                    <a href="{{ route('nosotros.home.index') }}" class="sub-item {{ request()->routeIs('nosotros.home.*') ? 'sub-active' : '' }}">Nosotros Home</a>
                    <a href="{{ route('home.memberships.index') }}" class="sub-item {{ request()->routeIs('home.memberships.*') ? 'sub-active' : '' }}">Pertenecemos a</a>
                </div>
            </div>

            {{-- NOSOTROS --}}
            <a href="{{ route('nosotros.index') }}" class="nav-item {{ request()->routeIs('nosotros.index') ? 'active-link' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Nosotros
            </a>

            {{-- EQUIPAMIENTO --}}
            <div x-data="{ open: {{ request()->routeIs('equipment.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="nav-item justify-between" :class="open ? 'text-[#52A028]' : ''">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Equipamiento</span>
                    </div>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-0.5">
                    <a href="{{ route('equipment.index') }}" class="sub-item {{ request()->routeIs('equipment.index', 'equipment.create', 'equipment.edit', 'equipment.store', 'equipment.update', 'equipment.destroy', 'equipment.visible', 'equipment.banner.*') ? 'sub-active' : '' }}">Equipamiento</a>
                    <a href="{{ route('equipment.categories.index') }}" class="sub-item {{ request()->routeIs('equipment.categories.*') ? 'sub-active' : '' }}">Categorías</a>
                </div>
            </div>

            {{-- SERVICIOS --}}
            <a href="{{ route('servicios.index') }}" class="nav-item {{ request()->routeIs('servicios.*') ? 'active-link' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Servicios
            </a>

            {{-- SECTORES --}}
            <a href="{{ route('sectors.index') }}" class="nav-item {{ request()->routeIs('sectors.*') ? 'active-link' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M4 18h16M6 18V9m4 9V5m4 13v-7m4 7V3"/>
                </svg>
                Sectores
            </a>

      

            {{-- PROYECTOS --}}
            <a href="{{ route('proyectos.index') }}" class="nav-item {{ request()->routeIs('proyectos.*') ? 'active-link' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Proyectos
            </a>

            {{-- CALIDAD --}}
            <a href="{{ route('quality.index') }}" class="nav-item {{ request()->routeIs('quality.*') ? 'active-link' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z"/>
                </svg>
                Calidad
            </a>

            {{-- CLIENTES (expandable) --}}
            <div x-data="{ open: {{ request()->routeIs('brands.*', 'brand-categories.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="nav-item justify-between" :class="open ? 'text-[#52A028]' : ''">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Clientes</span>
                    </div>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-0.5">
                    <a href="{{ route('brand-categories.index') }}" class="sub-item {{ request()->routeIs('brand-categories.*') ? 'sub-active' : '' }}">Cat. Marcas</a>
                    <a href="{{ route('brands.index') }}" class="sub-item {{ request()->routeIs('brands.*') ? 'sub-active' : '' }}">Marcas</a>
                </div>
            </div>

            {{-- NOVEDADES (expandable) --}}
            <div x-data="{ open: {{ request()->routeIs('novedades.*', 'novcategories.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="nav-item justify-between" :class="open ? 'text-[#52A028]' : ''">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <span>Novedades</span>
                    </div>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-0.5">
                    <a href="{{ route('novcategories.index') }}" class="sub-item {{ request()->routeIs('novcategories.*') ? 'sub-active' : '' }}">Categorías</a>
                    <a href="{{ route('novedades.index') }}" class="sub-item {{ request()->routeIs('novedades.*') ? 'sub-active' : '' }}">Ver novedades</a>
                </div>
            </div>

            {{-- CONTACTO (expandable) --}}
            <div x-data="{ open: {{ request()->routeIs('admin.contacto*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="nav-item justify-between" :class="open ? 'text-[#52A028]' : ''">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Contacto</span>
                    </div>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-0.5">
                    <a href="{{ route('admin.contacto') }}" class="sub-item {{ request()->routeIs('admin.contacto') && !request()->routeIs('admin.contacto.consultas*') ? 'sub-active' : '' }}">Configuración</a>
                    <a href="{{ route('admin.contacto.consultas') }}" class="sub-item {{ request()->routeIs('admin.contacto.consultas*') ? 'sub-active' : '' }}">Consultas</a>
                </div>
            </div>

            {{-- PRESUPUESTO (expandable) --}}
            <div x-data="{ open: {{ request()->routeIs('presupuesto.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="nav-item justify-between" :class="open ? 'text-[#52A028]' : ''">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14h6M9 10h6M7 4h10a2 2 0 012 2v14l-4-2-3 2-3-2-4 2V6a2 2 0 012-2z"/>
                        </svg>
                        <span>Presupuesto</span>
                    </div>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="space-y-0.5 mt-0.5">
                    <a href="{{ route('presupuesto.admin.index') }}" class="sub-item {{ request()->routeIs('presupuesto.admin.*', 'presupuesto.system-types.*') ? 'sub-active' : '' }}">Configuración</a>
                    <a href="{{ route('presupuesto.requests.index') }}" class="sub-item {{ request()->routeIs('presupuesto.requests.*') ? 'sub-active' : '' }}">Solicitudes</a>
                </div>
            </div>

            <div class="nav-divider"></div>

            {{-- NEWSLETTER --}}
            <a href="{{ route('admin.newsletter') }}" class="nav-item {{ request()->routeIs('admin.newsletter*') ? 'active-link' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                </svg>
                Newsletter
            </a>

            {{-- USUARIOS --}}
            <a href="{{ route('usuarios.index') }}" class="nav-item {{ request()->routeIs('usuarios.*') ? 'active-link' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Usuarios
            </a>

            {{-- METADATA SEO --}}
            <a href="{{ route('admin.metadata') }}" class="nav-item {{ request()->routeIs('admin.metadata') ? 'active-link' : '' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Metadata SEO
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-3" style="border-top: 1px solid var(--sb);">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-lg text-white text-sm font-semibold cursor-pointer transition-opacity hover:opacity-90"
                    style="background: linear-gradient(135deg, var(--p) 0%, var(--a) 100%);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- ========== ÁREA PRINCIPAL ========== -->
    <div class="min-h-screen flex flex-col transition-all duration-300" :class="sidebarOpen ? 'ml-72' : 'ml-0'">

        <!-- Topbar -->
        <header class="sticky top-0 z-30 bg-white shadow-sm flex items-center justify-between px-6 h-14" style="border-bottom: 1px solid #e5e7eb;">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                @hasSection('page-title')
                    <nav class="flex items-center gap-1.5 text-sm">
                        <a href="{{ url('/admin') }}" class="text-slate-400 hover:text-[#52A028] transition">Panel</a>
                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="font-medium text-slate-700">@yield('page-title')</span>
                    </nav>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" target="_blank"
                   class="flex items-center gap-1.5 text-xs text-slate-400 hover:text-[#52A028] transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Ver sitio
                </a>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                     style="background: var(--p);">A</div>
            </div>
        </header>

        <!-- Contenido -->
        <main class="flex-1 p-6 lg:p-8">
            <div class="admin-fade">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
