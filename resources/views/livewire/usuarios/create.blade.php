@extends('layouts.admin')

@section('content')

<div class="max-w-xl animate-fadeIn">

    <div class="bg-white border border-slate-200 rounded-md shadow-sm">

        <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-4">
            <a href="{{ route('usuarios.index') }}" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Nuevo usuario</h1>
                <p class="text-sm text-slate-500 mt-0.5">Creá un nuevo acceso al panel admin.</p>
            </div>
        </div>

        <form action="{{ route('usuarios.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
                       placeholder="Nombre completo">
                @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
                       placeholder="correo@ejemplo.com">
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Rol *</label>
                <select name="role"
                        class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition">
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña *</label>
                <input type="password" name="password"
                       class="w-full text-sm border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
                       placeholder="Mínimo 6 caracteres">
                @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('usuarios.index') }}"
                   class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-md hover:bg-slate-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition cursor-pointer">
                    Crear usuario
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
