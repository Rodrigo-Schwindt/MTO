@forelse($services as $service)
    <tr>
        <td class="text-center font-mono uppercase font-semibold">{{ $service->orden }}</td>

        <td>
            <div class="w-20 h-14 mx-auto rounded-lg overflow-hidden bg-slate-100 flex items-center justify-center">
                @if($service->image)
                    <img src="{{ Storage::url($service->image) }}" class="w-full h-full object-cover" alt="{{ $service->title }}">
                @else
                    <span class="text-slate-400 text-xs">—</span>
                @endif
            </div>
        </td>

        <td>
            <p class="font-medium text-slate-900">{{ $service->title }}</p>
            <p class="text-slate-500 text-xs line-clamp-2 mt-0.5">{{ \Illuminate\Support\Str::limit(strip_tags($service->description), 120) ?: 'Sin descripción' }}</p>
            <p class="text-xs text-slate-300 mt-1">/servicios/{{ $service->slug }}</p>
        </td>

        <td class="text-center">
            <button type="button" data-id="{{ $service->id }}"
                    class="toggle-visible-btn {{ $service->visible ? 'badge-on' : 'badge-off' }} cursor-pointer">
                {{ $service->visible ? 'Sí' : 'No' }}
            </button>
        </td>

        <td class="text-center">
            <button type="button" data-id="{{ $service->id }}"
                    class="toggle-destacado-btn {{ $service->destacado ? 'badge-accent' : 'badge-off' }} cursor-pointer">
                {{ $service->destacado ? 'Sí' : 'No' }}
            </button>
        </td>

        <td>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('servicios.edit', $service->id) }}"
                   class="text-slate-400 hover:text-[#52A028] transition cursor-pointer" title="Editar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </a>
                <button class="delete-btn text-slate-400 hover:text-red-600 transition cursor-pointer"
                        data-id="{{ $service->id }}" title="Eliminar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="py-10 text-center text-slate-400 text-sm">No hay servicios disponibles.</td>
    </tr>
@endforelse
