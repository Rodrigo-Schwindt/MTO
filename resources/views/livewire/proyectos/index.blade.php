@section('page-title', 'Proyectos')

@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-semibold text-slate-900">Proyectos ({{ $projects->total() }})</h2>
        <a href="{{ route('proyectos.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#52A028] text-white rounded-md hover:bg-[#3d7a1e] transition">
            Crear Proyecto
        </a>
    </div>

    @if(session('toast'))
        <div class="px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('toast.message') }}
        </div>
    @endif

    <section class="border border-slate-200 rounded-md p-5 bg-slate-50">
        <h3 class="text-lg font-semibold text-slate-900 mb-3">Banner de la seccion</h3>
        <div class="h-44 rounded-md border border-slate-200 bg-white overflow-hidden flex items-center justify-center mb-4">
            @if($pageData->banner_image)
                <img src="{{ Storage::url($pageData->banner_image) }}" class="w-full h-full object-cover" alt="Banner proyectos">
            @else
                <span class="text-slate-400 text-sm">Sin banner</span>
            @endif
        </div>
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('proyectos.banner.save') }}" enctype="multipart/form-data" class="flex flex-wrap gap-3">
                @csrf
                <input type="file" name="banner_image" accept="image/*" class="border border-slate-300 rounded-md px-3 py-2 text-sm bg-white">
                <button class="px-4 py-2 bg-[#52A028] text-white rounded-md text-sm hover:bg-[#3d7a1e]">Guardar banner</button>
            </form>

            @if($pageData->banner_image)
                <form method="POST" action="{{ route('proyectos.banner.remove') }}">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">Eliminar banner</button>
                </form>
            @endif
        </div>
    </section>

    <form method="GET" action="{{ route('proyectos.index') }}">
        <input type="text"
               name="search"
               value="{{ $search }}"
               placeholder="Buscar por titulo, descripcion u orden..."
               class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#52A028]">
    </form>

    <div class="overflow-x-auto border border-slate-200 rounded-md bg-white shadow-sm">
        <table class="w-full text-sm text-slate-700">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-center font-medium">Orden</th>
                    <th class="px-4 py-3 font-medium">Imagen</th>
                    <th class="px-4 py-3 text-start font-medium">Titulo</th>
                    <th class="px-4 py-3 text-center font-medium">Visible</th>
                    <th class="px-4 py-3 text-center font-medium">Destacado</th>
                    <th class="px-4 py-3 text-center font-medium">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($projects as $project)
                    @php $cover = $project->mainImage; @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-4 text-center font-mono uppercase">{{ $project->orden }}</td>
                        <td class="px-4 py-4">
                            <div class="w-24 h-16 mx-auto rounded-md overflow-hidden bg-slate-200">
                                @if($cover)
                                    <img src="{{ Storage::url($cover->image) }}" class="w-full h-full object-cover" alt="{{ $project->title }}">
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <p class="font-medium text-slate-900">{{ $project->title }}</p>
                            <p class="text-xs text-slate-400">/proyectos/{{ $project->slug }}</p>
                        </td>
                        <td class="px-4 py-4 text-center">{{ $project->visible ? 'Si' : 'No' }}</td>
                        <td class="px-4 py-4 text-center">{{ $project->destacado ? 'Si' : 'No' }}</td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('proyectos.edit', $project->id) }}" class="text-[#52A028]">Editar</a>
                                <form method="POST" action="{{ route('proyectos.destroy', $project->id) }}" onsubmit="return confirm('Eliminar proyecto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">No hay proyectos disponibles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $projects->links() }}
</div>
@endsection
