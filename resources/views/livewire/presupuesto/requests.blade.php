@section('page-title', 'Presupuesto / Solicitudes')

@extends('layouts.admin')

@section('content')
<div class="space-y-6 bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Solicitudes de presupuesto</h2>
            <p class="mt-1 text-sm text-slate-500">
                Solicitudes enviadas desde el formulario publico.
                @if($unreadCount > 0)
                    <span class="font-semibold text-green-700">{{ $unreadCount }} sin leer</span>
                @endif
            </p>
        </div>

        <a href="{{ route('presupuesto.admin.index') }}"
           class="inline-flex items-center justify-center px-5 py-2.5 border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition">
            Configurar formulario
        </a>
    </div>

    @if(session('toast'))
        <div class="px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('toast.message') }}
        </div>
    @endif

    <form method="GET" action="{{ route('presupuesto.requests.index') }}" class="flex flex-col sm:flex-row gap-3">
        <input type="text"
               name="search"
               value="{{ $search }}"
               placeholder="Buscar por contacto, servicio, equipamiento o mensaje..."
               class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
        <button class="px-5 py-2 bg-slate-900 text-white rounded-md hover:bg-slate-800 transition">Buscar</button>
        @if($search !== '')
            <a href="{{ route('presupuesto.requests.index') }}" class="px-5 py-2 border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition text-center">Limpiar</a>
        @endif
    </form>

    <div class="space-y-4">
        @forelse($requests as $requestItem)
            <article class="border rounded-md p-5 {{ $requestItem->read_at ? 'border-slate-200 bg-white' : 'border-green-200 bg-green-50/40' }}">
                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $requestItem->name }}</h3>
                            @if(!$requestItem->read_at)
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Nueva</span>
                            @endif
                        </div>

                        <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-600">
                            <a href="mailto:{{ $requestItem->email }}" class="hover:text-green-700">{{ $requestItem->email }}</a>
                            <a href="tel:{{ $requestItem->phone }}" class="hover:text-green-700">{{ $requestItem->phone }}</a>
                            <a href="tel:{{ $requestItem->mobile }}" class="hover:text-green-700">{{ $requestItem->mobile }}</a>
                            <span>{{ $requestItem->province }} / {{ $requestItem->locality }}</span>
                            <span>{{ $requestItem->created_at?->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if(!$requestItem->read_at)
                            <form method="POST" action="{{ route('presupuesto.requests.read', $requestItem) }}">
                                @csrf
                                <button class="px-3 py-2 rounded-md border border-green-200 text-green-700 text-sm hover:bg-green-50">
                                    Marcar leida
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('presupuesto.requests.destroy', $requestItem) }}" onsubmit="return confirm('Eliminar esta solicitud?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-2 rounded-md border border-red-200 text-red-600 text-sm hover:bg-red-50">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                    <div class="rounded-md bg-slate-50 border border-slate-200 p-3">
                        <span class="block text-xs font-semibold text-slate-500 uppercase">Servicio</span>
                        <span class="text-slate-800">{{ $requestItem->service ?: '-' }}</span>
                    </div>
                    <div class="rounded-md bg-slate-50 border border-slate-200 p-3">
                        <span class="block text-xs font-semibold text-slate-500 uppercase">Equipamiento</span>
                        <span class="text-slate-800">{{ $requestItem->equipment ?: '-' }}</span>
                    </div>
                    <div class="rounded-md bg-slate-50 border border-slate-200 p-3">
                        <span class="block text-xs font-semibold text-slate-500 uppercase">Tipo de sistema</span>
                        <span class="text-slate-800">{{ $requestItem->system_type ?: '-' }}</span>
                    </div>
                </div>

                @if($requestItem->message)
                    <div class="mt-4 text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $requestItem->message }}</div>
                @endif

                @if($requestItem->attachment)
                    <a href="{{ Storage::url($requestItem->attachment) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-sm text-green-700 hover:underline">
                        Descargar adjunto
                        <span class="text-slate-500">
                            {{ $requestItem->attachment_original_name }}
                            @if($requestItem->attachment_size)
                                ({{ max(1, round($requestItem->attachment_size / 1024)) }}kb)
                            @endif
                        </span>
                    </a>
                @endif
            </article>
        @empty
            <div class="border border-dashed border-slate-300 rounded-md p-10 text-center text-slate-500">
                No hay solicitudes para mostrar.
            </div>
        @endforelse
    </div>

    @if($requests->hasPages())
        {{ $requests->links() }}
    @endif
</div>
@endsection
