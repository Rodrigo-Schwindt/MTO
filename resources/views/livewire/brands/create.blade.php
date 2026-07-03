@section('page-title', 'Clientes / Marcas / Crear')

@extends('layouts.admin')

@section('content')
<div class="max-w-3xl animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-semibold text-slate-900">Nueva marca</h2>
        <a href="{{ route('brands.index') }}" class="text-sm text-slate-500 hover:text-slate-800">Volver</a>
    </div>

    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Orden</label>
            <input type="text" name="orden" value="{{ old('orden', $nextOrder) }}" class="w-full border border-slate-300 rounded-md px-3 py-2 uppercase">
            @error('orden') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Categoria</label>
            <select name="brand_category_id" class="w-full border border-slate-300 rounded-md px-3 py-2">
                <option value="">Sin categoria</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('brand_category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->orden }} - {{ $category->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Logo</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.svg" class="w-full border border-slate-300 rounded-md px-3 py-2" required>
            @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-6">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="visible" value="1" checked>
                <span class="text-sm text-slate-700">Visible</span>
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="destacado" value="1">
                <span class="text-sm text-slate-700">Destacado</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('brands.index') }}" class="px-5 py-2.5 rounded-md border border-slate-300 text-slate-700">Cancelar</a>
            <button class="px-5 py-2.5 rounded-md bg-[#52A028] text-white hover:bg-[#3d7a1e]">Guardar</button>
        </div>
    </form>
</div>
@endsection
