@section('page-title', 'Inicio / Representantes')

@extends('layouts.admin')

@section('content')
<div class="max-w-4xl space-y-8 animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Representantes</h2>
            <p class="text-sm text-slate-500 mt-1">Se muestran en la home debajo del slider. Con 4 o más logos se activa el carrusel automático.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm">
            Revisá los campos marcados.
        </div>
    @endif

    <form action="{{ route('home.representatives.save') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div>
            <label for="rep-title" class="block text-sm font-medium text-slate-700 mb-1">Título</label>
            <input type="text"
                   id="rep-title"
                   name="title"
                   value="{{ old('title', $representatives?->title) }}"
                   class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#52A028]">
            @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        @if($logos->isNotEmpty())
        <div>
            <p class="block text-sm font-medium text-slate-700 mb-3">
                Logos actuales
                <span class="font-normal text-slate-400">({{ $logos->count() }} {{ $logos->count() === 1 ? 'logo' : 'logos' }})</span>
            </p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($logos as $i => $logo)
                <div class="border border-slate-200 rounded-md p-3">
                    <div class="h-24 rounded bg-slate-50 flex items-center justify-center mb-3">
                        <img src="{{ Storage::url($logo) }}" alt="Logo {{ $i + 1 }}" class="max-h-20 max-w-full object-contain">
                    </div>
                    <label class="inline-flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remove_logos[]" value="{{ $logo }}"
                               class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                        <span class="text-sm text-red-600 group-hover:text-red-700">Eliminar</span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="border border-slate-200 rounded-md p-4">
            <label for="new-logos" class="block text-sm font-medium text-slate-700 mb-1">Agregar logos</label>
            <p class="text-xs text-slate-400 mb-3">Podés subir varios a la vez. Formatos aceptados: JPG, PNG, WEBP, GIF, SVG. Máx. 4 MB por archivo.</p>
            <input type="file"
                   id="new-logos"
                   name="new_logos[]"
                   multiple
                   accept=".jpg,.jpeg,.png,.webp,.gif,.svg"
                   class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm">
            @error('new_logos.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('sliders.index') }}" class="px-5 py-2.5 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">
                Cancelar
            </a>
            <button class="px-5 py-2.5 rounded-md bg-[#52A028] text-white hover:bg-[#3d7a1e]">
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
