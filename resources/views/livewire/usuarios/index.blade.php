@extends('layouts.admin')

@section('content')

<div class="space-y-6 animate-fadeIn">

    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-md px-4 py-3">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-md px-4 py-3">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-md shadow-sm">

        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Usuarios</h1>
                <p class="text-sm text-slate-500 mt-0.5">Gestioná los usuarios con acceso al panel admin.</p>
            </div>
            <a href="{{ route('usuarios.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nuevo usuario
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-slate-700">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Nombre</th>
                        <th class="px-5 py-3 text-left font-medium">Email</th>
                        <th class="px-5 py-3 text-left font-medium">Rol</th>
                        <th class="px-5 py-3 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-3 font-medium text-slate-900">
                            {{ $u->name }}
                            @if($u->id === auth()->id())
                                <span class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Vos</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-500">{{ $u->email }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full
                                {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('usuarios.edit', $u->id) }}"
                                   class="text-slate-500 hover:text-blue-600 transition">
                                    <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </a>

                                @if($u->id !== auth()->id())
                                <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar usuario {{ addslashes($u->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 transition cursor-pointer">
                                        <svg class="w-[26px] h-[26px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-slate-400 text-sm">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection
