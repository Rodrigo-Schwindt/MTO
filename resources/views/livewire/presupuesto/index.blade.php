@section('page-title', 'Presupuesto / Config.')

@extends('layouts.admin')

@section('content')
<div class="space-y-8 bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Presupuesto</h2>
            <p class="mt-1 text-sm text-slate-500">Administra el banner y los tipos de sistema del formulario.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('presupuesto.public') }}" target="_blank" class="px-5 py-2.5 bg-slate-900 text-white rounded-md hover:bg-slate-800 transition">
                Ver pagina
            </a>
            <a href="{{ route('presupuesto.requests.index') }}" class="px-5 py-2.5 border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition">
                Ver solicitudes
            </a>
        </div>
    </div>

    @if(session('toast'))
        <div class="px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('toast.message') }}
        </div>
    @endif

    @if($errors->any())
        <div class="px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
            Revisa los campos marcados antes de guardar.
        </div>
    @endif

    <section class="border border-slate-200 rounded-md p-5 bg-slate-50">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Banner de seccion</h3>

        @if($pageData->image_banner)
            <div class="mb-4 h-44 rounded-md border border-slate-200 bg-white overflow-hidden">
                <img src="{{ Storage::url($pageData->image_banner) }}" class="w-full h-full object-cover" alt="Banner presupuesto">
            </div>

            <form action="{{ route('presupuesto.admin.banner.remove') }}" method="POST" onsubmit="return confirm('Eliminar banner?')" class="mb-4">
                @csrf
                @method('DELETE')
                <button class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 transition">Eliminar banner</button>
            </form>
        @endif

        <form action="{{ route('presupuesto.admin.banner.save') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="file" name="image_banner" accept=".jpg,.jpeg,.png,.webp,.svg" class="w-full border border-slate-300 rounded-md px-3 py-2 bg-white">
            <button class="px-5 py-2 bg-[#52A028] text-white rounded-md hover:bg-[#3d7a1e] transition">Guardar banner</button>
        </form>
    </section>

    <section class="border border-slate-200 rounded-md p-5">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Agregar tipo de sistema</h3>

        <form action="{{ route('presupuesto.system-types.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-[110px_1fr_140px_140px] gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Orden</label>
                <input type="text" name="orden" value="{{ old('orden', $nextOrder) }}" class="w-full border border-slate-300 rounded-md px-3 py-2 uppercase">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Titulo</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
            </div>
            <label class="inline-flex items-center gap-2 pb-2 text-sm text-slate-700">
                <input type="checkbox" name="visible" checked>
                Visible
            </label>
            <button class="px-4 py-2 bg-[#52A028] text-white rounded-md hover:bg-[#3d7a1e] transition">Agregar</button>
        </form>
    </section>

    <section class="space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <h3 class="text-lg font-semibold text-slate-900">Tipos de sistema</h3>

            <form method="GET" action="{{ route('presupuesto.admin.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar..." class="w-full sm:w-72 border border-slate-300 rounded-md px-3 py-2 text-sm">
                <button class="px-4 py-2 bg-slate-900 text-white rounded-md hover:bg-slate-800 transition">Buscar</button>
                @if($search !== '')
                    <a href="{{ route('presupuesto.admin.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition text-center">Limpiar</a>
                @endif
            </form>
        </div>

        <div class="space-y-3">
            @forelse($systemTypes as $type)
                <form action="{{ route('presupuesto.system-types.update', $type) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-[110px_1fr_120px_120px_110px] gap-3 items-end border border-slate-200 rounded-md p-4 bg-slate-50">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Orden</label>
                        <input type="text" name="orden" value="{{ $type->orden }}" class="w-full border border-slate-300 rounded-md px-3 py-2 uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Titulo</label>
                        <input type="text" name="title" value="{{ $type->title }}" class="w-full border border-slate-300 rounded-md px-3 py-2">
                    </div>
                    <label class="inline-flex items-center gap-2 pb-2 text-sm text-slate-700">
                        <input type="checkbox" name="visible" @checked($type->visible)>
                        Visible
                    </label>
                    <button class="px-4 py-2 bg-[#52A028] text-white rounded-md hover:bg-[#3d7a1e] transition">Guardar</button>
                    <button form="deleteType{{ $type->id }}" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition" onclick="return confirm('Eliminar tipo de sistema?')">Eliminar</button>
                </form>

                <form id="deleteType{{ $type->id }}" action="{{ route('presupuesto.system-types.destroy', $type) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @empty
                <div class="border border-dashed border-slate-300 rounded-md p-8 text-center text-slate-500">
                    No hay tipos de sistema cargados.
                </div>
            @endforelse
        </div>

        @if($systemTypes->hasPages())
            {{ $systemTypes->links() }}
        @endif
    </section>
</div>
@endsection
