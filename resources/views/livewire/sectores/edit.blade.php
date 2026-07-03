@section('page-title', 'Sectores / Editar')

@extends('layouts.admin')

@section('content')
<div class="animate-fadeIn bg-white border border-slate-200 rounded-md shadow-sm p-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-semibold text-slate-900">Editar sector</h2>
        <a href="{{ route('sectors.index') }}" class="text-sm text-slate-500 hover:text-slate-800">Volver</a>
    </div>

    <form action="{{ route('sectors.update', $sector->id) }}" method="POST" enctype="multipart/form-data">
        @include('livewire.sectores.form')
    </form>
</div>
@endsection
