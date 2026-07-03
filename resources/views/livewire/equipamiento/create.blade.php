@section('page-title', 'Equipamiento / Crear')

@extends('layouts.admin')

@section('content')
<div class="animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-semibold text-slate-900">Nuevo producto</h2>
        <a href="{{ route('equipment.index') }}" class="text-sm text-slate-500 hover:text-slate-800">Volver</a>
    </div>

    <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
        @include('livewire.equipamiento.form')
    </form>
</div>
@endsection
