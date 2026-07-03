@section('page-title', 'Proyectos / Crear')

@extends('layouts.admin')

@section('content')
<div class="mx-auto space-y-8 animate-fadeIn">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-slate-900">Crear Proyecto</h2>
        <a href="{{ route('proyectos.index') }}" class="px-4 py-2 border border-slate-300 rounded-md text-sm">Volver</a>
    </div>

    <form method="POST" action="{{ route('proyectos.store') }}" enctype="multipart/form-data" class="bg-white rounded-md border border-slate-200 p-6 space-y-6 shadow-sm">
        @csrf

        @include('livewire.proyectos.form', ['project' => null, 'nextOrder' => $nextOrder])

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
            <button type="submit" class="px-6 py-2 bg-[#52A028] text-white rounded-md text-sm hover:bg-[#3d7a1e]">Crear Proyecto</button>
        </div>
    </form>
</div>
@endsection
