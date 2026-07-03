@section('page-title', 'Equipamiento / Categorías / Crear')

@extends('layouts.admin')

@section('content')
<div class="animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-semibold text-slate-900">Nueva categoría</h2>
        <a href="{{ route('equipment.categories.index') }}" class="text-sm text-slate-500 hover:text-slate-800">Volver</a>
    </div>

    <form action="{{ route('equipment.categories.store') }}" method="POST" enctype="multipart/form-data">
        @include('livewire.equipamiento.categorias.form')
    </form>
</div>
@endsection
