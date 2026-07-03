@section('page-title', 'Inicio / Pertenecemos a')

@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-semibold text-slate-900">Pertenecemos a</h2>
        <a href="{{ route('home.memberships.create') }}" class="inline-flex items-center px-5 py-2.5 bg-[#52A028] text-white rounded-md hover:bg-[#3d7a1e] transition">
            Crear logo
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
                    <th class="px-4 py-3 text-center">Visible</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($memberships as $membership)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-center font-mono uppercase">{{ $membership->orden }}</td>
                        <td class="px-4 py-3 text-center">
                            <img src="{{ Storage::url($membership->image) }}" alt="Logo" class="h-14 max-w-[170px] object-contain mx-auto">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('home.memberships.visible', $membership->id) }}" method="POST">
                                @csrf
                                <button class="text-xl">{{ $membership->visible ? 'Si' : 'No' }}</button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('home.memberships.edit', $membership->id) }}" class="text-[#52A028] hover:underline">Editar</a>
                                <form action="{{ route('home.memberships.destroy', $membership->id) }}" method="POST" onsubmit="return confirm('Eliminar logo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">No hay logos cargados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $memberships->links() }}
</div>
@endsection
