@section('page-title', 'Contacto / Configuración')

@extends('layouts.admin')

@section('content')
<div class="space-y-6 admin-fade">

    <form id="contact-form" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Contenido público --}}
        <div class="admin-card space-y-5">
            <p class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Contenido público</p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="image_banner" class="block text-sm font-medium text-slate-700 mb-2">Banner de la sección</label>
                    @if($contact?->image_banner)
                        <div class="mb-3 rounded-lg overflow-hidden border border-slate-200">
                            <img src="{{ Storage::url($contact->image_banner) }}" alt="Banner contacto" class="h-40 w-full object-cover">
                        </div>
                    @endif
                    <input type="file" id="image_banner" name="image_banner" accept="image/*"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white file:bg-[#52A028] file:text-white file:px-4 file:py-1.5 file:rounded-md file:border-0 file:cursor-pointer file:mr-3 cursor-pointer focus:ring-2 focus:ring-[#52A028] focus:outline-none">
                    <div class="upload-hint">
                        <strong>Formato:</strong> JPG, PNG, WebP ·
                        <strong>Tamaño:</strong> 1440×420px ·
                        <strong>Peso:</strong> máx. 3 MB
                    </div>
                    @if($contact?->image_banner)
                        <button type="button" onclick="removeBanner()" class="btn-danger mt-3 text-sm">Eliminar banner</button>
                    @endif
                </div>

                <div>
                    <label for="request_text" class="block text-sm font-medium text-slate-700 mb-2">Texto destacado del formulario</label>
                    <textarea id="request_text" name="request_text" rows="7" class="admin-input resize-none"
                              placeholder="Si desea realizar una solicitud de presupuesto...">{{ old('request_text', html_entity_decode($contact->request_text ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Información de contacto --}}
        <div class="admin-card space-y-5">
            <p class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Información de contacto</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="direction_adm" class="block text-sm font-medium text-slate-700 mb-2">Dirección</label>
                    <input type="text" id="direction_adm" name="direction_adm" value="{{ $contact->direction_adm ?? '' }}" class="admin-input">
                </div>
                <div>
                    <label for="wssp" class="block text-sm font-medium text-slate-700 mb-2">WhatsApp (flotante)</label>
                    <input type="text" id="wssp" name="wssp" value="{{ $contact->wssp ?? '' }}" class="admin-input">
                </div>
                <div>
                    <label for="phone_amd" class="block text-sm font-medium text-slate-700 mb-2">WhatsApp 2</label>
                    <input type="text" id="phone_amd" name="phone_amd" value="{{ $contact->phone_amd ?? '' }}" class="admin-input">
                </div>
                <div>
                    <label for="phone_sale" class="block text-sm font-medium text-slate-700 mb-2">WhatsApp 3</label>
                    <input type="text" id="phone_sale" name="phone_sale" value="{{ $contact->phone_sale ?? '' }}" class="admin-input">
                </div>
                <div>
                    <label for="maps_adm" class="block text-sm font-medium text-slate-700 mb-2">WhatsApp 4</label>
                    <input type="text" id="maps_adm" name="maps_adm" value="{{ $contact->maps_adm ?? '' }}" class="admin-input">
                </div>
                <div>
                    <label for="mail_adm" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input type="email" id="mail_adm" name="mail_adm" value="{{ $contact->mail_adm ?? '' }}" class="admin-input">
                </div>
            </div>
        </div>

        {{-- Redes sociales --}}
        <div class="admin-card space-y-5">
            <p class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Redes sociales</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach(['facebook' => 'Facebook', 'insta' => 'Instagram', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube'] as $key => $label)
                    <div>
                        <label for="{{ $key }}" class="block text-sm font-medium text-slate-700 mb-2">{{ $label }}</label>
                        <input type="url" id="{{ $key }}" name="{{ $key }}" value="{{ $contact->$key ?? '' }}" placeholder="https://…" class="admin-input">
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Acceso externo --}}
        <div class="admin-card space-y-4">
            <div>
                <p class="text-sm font-semibold text-slate-700">Acceso externo (ícono del header)</p>
                <p class="text-xs text-slate-400 mt-1">URL que se abre al hacer clic en el ícono de usuario del encabezado público. Puede ser un portal de clientes, intranet u otro enlace externo.</p>
            </div>
            <div class="max-w-lg">
                <label for="link_externo" class="block text-sm font-medium text-slate-700 mb-2">Link externo</label>
                <input type="url" id="link_externo" name="link_externo" value="{{ $contact->link_externo ?? '' }}" placeholder="https://…" class="admin-input">
            </div>
        </div>

        {{-- Mapa --}}
        <div class="admin-card space-y-4">
            <p class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Configuración del mapa</p>
            <div>
                <label for="frame_adm" class="block text-sm font-medium text-slate-700 mb-2">Embed de Google Maps</label>
                <textarea id="frame_adm" name="frame_adm" rows="6" class="admin-input resize-none font-mono text-xs">{{ $contact->frame_adm ?? '' }}</textarea>
            </div>
            @if($contact?->frame_adm)
                <div class="border border-slate-200 rounded-lg overflow-hidden bg-slate-50 p-3">
                    {!! $contact->frame_adm !!}
                </div>
            @endif
        </div>

        {{-- Íconos --}}
        <div class="admin-card space-y-4">
            <p class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Íconos / Logos</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $iconTitles = [1 => 'Ícono Home', 2 => 'Ícono con Banner', 3 => 'Ícono Footer'];
                @endphp
                @foreach([1, 2, 3] as $i)
                    @php $icon = "icono_$i"; @endphp
                    <div class="flex flex-col items-center text-center gap-3 p-4 border border-slate-200 rounded-lg">
                        <p class="text-sm font-medium text-slate-700">{{ $iconTitles[$i] }}</p>
                        <div class="w-36 h-24 border border-slate-200 rounded-lg bg-slate-50 flex items-center justify-center overflow-hidden">
                            <img id="preview_icono_{{ $i }}"
                                 src="{{ $contact?->$icon ? Storage::url($contact->$icon) : '' }}"
                                 alt="{{ $iconTitles[$i] }}"
                                 class="max-w-full max-h-full object-contain {{ $contact?->$icon ? '' : 'hidden' }}">
                            @if(!$contact?->$icon)
                                <span class="text-xs text-slate-400">Sin imagen</span>
                            @endif
                        </div>
                        <input type="file" id="icono_{{ $i }}_temp" name="icono_{{ $i }}_temp" class="hidden" accept="image/*">
                        <div class="flex gap-2">
                            <button type="button" onclick="document.getElementById('icono_{{ $i }}_temp').click()" class="btn-primary text-xs px-3 py-1.5">
                                Subir imagen
                            </button>
                            @if($contact?->$icon)
                                <button type="button" onclick="removeIcono({{ $i }})" class="btn-danger text-xs px-3 py-1.5">Eliminar</button>
                            @endif
                        </div>
                        <div class="upload-hint text-left w-full">
                            <strong>Fondo:</strong> transparente (PNG/SVG) ·
                            <strong>Tamaño:</strong> 400×200px ·
                            <strong>Peso:</strong> máx. 2 MB
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary px-8">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar cambios
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('request_text') && window.CKEDITOR) CKEDITOR.replace('request_text');

    document.querySelectorAll('input[type=file][name^="icono_"]').forEach(input => {
        input.addEventListener('change', e => {
            if (!e.target.files.length) return;
            const preview = document.getElementById('preview_' + input.name.replace('_temp', ''));
            preview.src = URL.createObjectURL(e.target.files[0]);
            preview.classList.remove('hidden');
        });
    });

    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        if (window.CKEDITOR && CKEDITOR.instances.request_text) CKEDITOR.instances.request_text.updateElement();
        const formData = new FormData(this);
        fetch('{{ route('admin.contacto.save') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(async res => {
            const out = await res.text();
            if (!res.ok) { console.error(out); showToast('Error al guardar', 'error'); return; }
            showToast('Datos guardados correctamente');
            setTimeout(() => location.reload(), 1000);
        })
        .catch(() => showToast('Error de conexión', 'error'));
    });
});

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-5 right-5 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium z-50 transition-opacity ${type === 'success' ? 'bg-[#52A028]' : 'bg-red-600'}`;
    toast.innerText = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2500);
}

function removeBanner() {
    if (!confirm('¿Eliminar imagen banner?')) return;
    const body = new FormData();
    body.append('remove_image_banner', '1');
    fetch('{{ route('admin.contacto.save') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body })
    .then(() => { showToast('Banner eliminado'); location.reload(); });
}

function removeIcono(i) {
    if (!confirm('¿Eliminar ícono ' + i + '?')) return;
    const body = new FormData();
    body.append('remove_icono_' + i, '1');
    fetch('{{ route('admin.contacto.save') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body })
    .then(() => { showToast('Ícono eliminado'); location.reload(); });
}
</script>
@endsection
