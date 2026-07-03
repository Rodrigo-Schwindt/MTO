@section('page-title', 'Clientes / Cat. Marcas')

@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-semibold text-slate-900">Categorias de marcas</h2>
        <a href="{{ route('brand-categories.create') }}"
           class="inline-flex items-center px-5 py-2.5 bg-[#52A028] text-white rounded-md hover:bg-[#3d7a1e] transition">
            Crear categoria
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
                    <th class="px-4 py-3 text-left">Titulo</th>
                    <th class="px-4 py-3 text-center">Marcas</th>
                    <th class="px-4 py-3 text-center">Visible</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($categories as $category)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-center font-mono uppercase">{{ $category->orden }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $category->title }}</td>
                        <td class="px-4 py-3 text-center">{{ $category->brands_count }}</td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('brand-categories.visible', $category->id) }}" method="POST">
                                @csrf
                                <button class="text-xl" title="Cambiar visibilidad">{{ $category->visible ? 'Si' : 'No' }}</button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('brand-categories.edit', $category->id) }}" class="text-[#52A028] hover:underline">Editar</a>
                                <form action="{{ route('brand-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Eliminar categoria?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">No hay categorias cargadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $categories->links() }}
</div>
@endsection
