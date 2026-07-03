@section('page-title', 'Equipamiento / Categorías')

@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('equipment.index') }}" class="text-sm text-slate-500 hover:text-slate-800">← Productos</a>
            <h2 class="text-2xl font-semibold text-slate-900">Categorías de equipamiento (<span>{{ $categories->count() }}</span>)</h2>
        </div>
        <a href="{{ route('equipment.categories.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#52A028] text-white rounded-md hover:bg-[#3d7a1e] transition">
            Crear categoría
        </a>
    </div>

    @if(session('toast'))
        <div class="px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('toast.message') }}
        </div>
    @endif

    <div class="overflow-x-auto border border-slate-200 rounded-md bg-white shadow-sm">
        <table class="w-full text-sm text-slate-700">
            <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-center">Orden</th>
                    <th class="px-4 py-3 text-center">Imagen</th>
                    <th class="px-4 py-3 text-left">Título</th>
                    <th class="px-4 py-3 text-center">Productos</th>
                    <th class="px-4 py-3 text-center">Visible</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($categories as $category)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-center font-mono uppercase">{{ $category->orden }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($category->imagen)
                                <img src="{{ Storage::url($category->imagen) }}" alt="{{ $category->title }}" class="h-14 max-w-[120px] object-contain mx-auto">
                            @else
                                <span class="text-slate-400">Sin imagen</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $category->title }}</td>
                        <td class="px-4 py-3 text-center text-slate-600">{{ $category->products_count }}</td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('equipment.categories.visible', $category->id) }}" method="POST">
                                @csrf
                                <button class="text-xl">{{ $category->visible ? 'Si' : 'No' }}</button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('equipment.categories.edit', $category->id) }}" class="text-[#52A028] hover:underline">Editar</a>
                                <form action="{{ route('equipment.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Eliminar categoría? Los productos quedarán sin categoría.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">No hay categorías creadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
