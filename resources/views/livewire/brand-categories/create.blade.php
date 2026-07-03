@section('page-title', 'Clientes / Cat. Marcas / Crear')

@extends('layouts.admin')

@section('content')
<div class="max-w-3xl animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-semibold text-slate-900">Nueva categoria de marcas</h2>
        <a href="{{ route('brand-categories.index') }}" class="text-sm text-slate-500 hover:text-slate-800">Volver</a>
    </div>

    <form action="{{ route('brand-categories.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Orden</label>
            <input type="text" name="orden" value="{{ old('orden', $nextOrder) }}" class="w-full border border-slate-300 rounded-md px-3 py-2 uppercase">
            @error('orden') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Titulo</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-slate-300 rounded-md px-3 py-2" required>
            @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="visible" value="1" checked>
            <span class="text-sm text-slate-700">Visible</span>
        </label>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('brand-categories.index') }}" class="px-5 py-2.5 rounded-md border border-slate-300 text-slate-700">Cancelar</a>
            <button class="px-5 py-2.5 rounded-md bg-[#52A028] text-white hover:bg-[#3d7a1e]">Guardar</button>
        </div>
    </form>
</div>
@endsection
