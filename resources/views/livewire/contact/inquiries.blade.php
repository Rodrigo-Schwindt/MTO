@section('page-title', 'Contacto / Consultas')

@extends('layouts.admin')

@section('content')
<div class="space-y-6 bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Consultas de contacto</h2>
            <p class="mt-1 text-sm text-slate-500">
                Mensajes enviados desde el formulario publico.
                @if($unreadCount > 0)
                    <span class="font-semibold text-green-700">{{ $unreadCount }} sin leer</span>
                @endif
            </p>
        </div>

        <a href="{{ route('admin.contacto') }}"
           class="inline-flex items-center justify-center px-5 py-2.5 border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition">
            Configurar contacto
        </a>
    </div>

    @if(session('toast'))
        <div class="px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('toast.message') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.contacto.consultas') }}" class="flex flex-col sm:flex-row gap-3">
        <input type="text"
               name="search"
               value="{{ $search }}"
               placeholder="Buscar por nombre, email, telefono o mensaje..."
               class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
        <button class="px-5 py-2 bg-slate-900 text-white rounded-md hover:bg-slate-800 transition">
            Buscar
        </button>
        @if($search !== '')
            <a href="{{ route('admin.contacto.consultas') }}"
               class="px-5 py-2 border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition text-center">
                Limpiar
            </a>
        @endif
    </form>

    <div class="space-y-4">
        @forelse($inquiries as $inquiry)
            <article class="border rounded-md p-5 {{ $inquiry->read_at ? 'border-slate-200 bg-white' : 'border-green-200 bg-green-50/40' }}">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-lg font-semibold text-slate-900">
                                {{ trim($inquiry->name.' '.$inquiry->lastname) }}
                            </h3>
                            @if(!$inquiry->read_at)
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Nueva</span>
                            @endif
                        </div>

                        <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-600">
                            <a href="mailto:{{ $inquiry->email }}" class="hover:text-green-700">{{ $inquiry->email }}</a>
                            @if($inquiry->phone)
                                <a href="tel:{{ $inquiry->phone }}" class="hover:text-green-700">{{ $inquiry->phone }}</a>
                            @endif
                            <span>{{ $inquiry->created_at?->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if(!$inquiry->read_at)
                            <form method="POST" action="{{ route('admin.contacto.consultas.read', $inquiry) }}">
                                @csrf
                                <button class="px-3 py-2 rounded-md border border-green-200 text-green-700 text-sm hover:bg-green-50">
                                    Marcar leida
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.contacto.consultas.destroy', $inquiry) }}" onsubmit="return confirm('Eliminar esta consulta?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-2 rounded-md border border-red-200 text-red-600 text-sm hover:bg-red-50">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-4 text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $inquiry->message }}</div>
            </article>
        @empty
            <div class="border border-dashed border-slate-300 rounded-md p-10 text-center text-slate-500">
                No hay consultas para mostrar.
            </div>
        @endforelse
    </div>

    @if($inquiries->hasPages())
        <div>
            {{ $inquiries->links() }}
        </div>
    @endif
</div>
@endsection
