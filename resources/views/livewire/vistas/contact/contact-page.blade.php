@php
    $requestText = str_replace('&nbsp;', ' ', (string) ($contact?->request_text ?? ''));
    $hasRequestText = trim(strip_tags($requestText)) !== '';
@endphp

<div
    x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 50)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 transform -translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
>
    <section class="contact-banner">
        @if($contact?->image_banner)
            <img src="{{ Storage::url($contact->image_banner) }}" alt="Contacto" class="contact-banner-image">
        @else
            <div class="contact-banner-fallback"></div>
        @endif

        <div class="contact-banner-overlay"></div>

        <div class="contact-banner-inner">
            <nav class="contact-breadcrumb">
                <a wire:navigate href="{{ url('/') }}">Inicio</a>
                <span>&rsaquo;</span>
                <span>Contacto</span>
            </nav>

            <h1>Contacto</h1>
        </div>
    </section>

    <section class="contact-section">
        <div class="contact-shell">
            <h2>Contactate con nosotros</h2>

            <div class="contact-grid">
                <aside class="contact-info">
                    @if($hasRequestText)
                        <div class="contact-request-text">{!! $requestText !!}</div>
                    @endif

                    <div class="contact-details">
                        @if($contact?->direction_adm)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($contact->direction_adm) }}"
                               target="_blank"
                               class="contact-detail">
                                <span class="contact-detail-icon" aria-hidden="true">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" class="mt-2">
                                        <path d="M20 10C20 16 12 22 12 22C12 22 4 16 4 10C4 7.878 4.843 5.843 6.343 4.343C7.843 2.843 9.878 2 12 2C14.122 2 16.157 2.843 17.657 4.343C19.157 5.843 20 7.878 20 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 13C13.657 13 15 11.657 15 10C15 8.343 13.657 7 12 7C10.343 7 9 8.343 9 10C9 11.657 10.343 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span>{{ $contact->direction_adm }}</span>
                            </a>
                        @endif

                        @php
                            $contactWhatsapps = collect([
                                $contact?->phone_amd,
                                $contact?->phone_sale,
                                $contact?->maps_adm,
                            ])->filter(fn ($value) => trim((string) $value) !== '');
                        @endphp

                        @foreach($contactWhatsapps as $whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" class="contact-detail">
                                <span class="contact-detail-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5823 11.985C14.3328 11.8608 13.1095 11.2625 12.8817 11.1792C12.6539 11.0967 12.4881 11.0558 12.3215 11.3042C12.1557 11.5508 11.6793 12.1092 11.5344 12.2742C11.3887 12.44 11.2438 12.46 10.9952 12.3367C10.7465 12.2117 9.94429 11.9508 8.99391 11.1075C8.25453 10.4509 7.75464 9.64002 7.60978 9.39169C7.46492 9.14419 7.59387 9.01002 7.71863 8.88669C7.83084 8.77585 7.96732 8.59752 8.09209 8.45335C8.21685 8.30835 8.25788 8.20502 8.34078 8.03919C8.42451 7.87419 8.38265 7.73002 8.31985 7.60585C8.25788 7.48169 7.7605 6.26252 7.55284 5.76669C7.35104 5.28419 7.14589 5.35003 6.99349 5.34169C6.8478 5.33503 6.682 5.33336 6.51621 5.33336C6.35041 5.33336 6.08079 5.39503 5.85303 5.64336C5.62444 5.89086 4.98219 6.49002 4.98219 7.70919C4.98219 8.92752 5.87313 10.105 5.99789 10.2709C6.12266 10.4359 7.75213 12.9375 10.2482 14.01C10.8428 14.265 11.3058 14.4175 11.6667 14.5308C12.2629 14.72 12.8055 14.6933 13.2342 14.6292C13.7115 14.5583 14.7063 14.03 14.9139 13.4517C15.1208 12.8733 15.1207 12.3775 15.0588 12.2742C14.9968 12.1708 14.831 12.1092 14.5815 11.985H14.5823ZM10.0423 18.1542H10.0389C8.55634 18.1544 7.10099 17.7578 5.8254 17.0058L5.52396 16.8275L2.39062 17.6458L3.22712 14.6058L3.03035 14.2942C2.20149 12.9811 1.76286 11.4615 1.76512 9.91085C1.76679 5.36919 5.47958 1.6742 10.0456 1.6742C12.2562 1.6742 14.3345 2.53253 15.897 4.08919C16.6676 4.85301 17.2785 5.76133 17.6941 6.7616C18.1098 7.76188 18.322 8.83425 18.3186 9.91668C18.3169 14.4583 14.6041 18.1542 10.0423 18.1542ZM17.086 2.9067C16.1634 1.98247 15.0657 1.24965 13.8564 0.7507C12.6472 0.251754 11.3505 -0.00339687 10.0414 3.41479e-05C4.55347 3.41479e-05 0.085409 4.44586 0.0837344 9.91002C0.0811914 11.649 0.539563 13.3578 1.4126 14.8642L0 20L5.27861 18.6217C6.73884 19.4134 8.37519 19.8283 10.0381 19.8283H10.0423C15.5302 19.8283 19.9983 15.3825 20 9.91752C20.004 8.61525 19.7485 7.32511 19.2484 6.12172C18.7482 4.91833 18.0132 3.82559 17.086 2.9067Z" fill="#0B982C"/>
                                    </svg>
                                </span>
                                <span>{{ $whatsapp }}</span>
                            </a>
                        @endforeach

                        @if(false && $contact?->phone_amd)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->phone_amd) }}" target="_blank" class="contact-detail">
                                <span class="contact-detail-icon" aria-hidden="true">
                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5823 11.985C14.3328 11.8608 13.1095 11.2625 12.8817 11.1792C12.6539 11.0967 12.4881 11.0558 12.3215 11.3042C12.1557 11.5508 11.6793 12.1092 11.5344 12.2742C11.3887 12.44 11.2438 12.46 10.9952 12.3367C10.7465 12.2117 9.94429 11.9508 8.99391 11.1075C8.25453 10.4509 7.75464 9.64002 7.60978 9.39169C7.46492 9.14419 7.59387 9.01002 7.71863 8.88669C7.83084 8.77585 7.96732 8.59752 8.09209 8.45335C8.21685 8.30835 8.25788 8.20502 8.34078 8.03919C8.42451 7.87419 8.38265 7.73002 8.31985 7.60585C8.25788 7.48169 7.7605 6.26252 7.55284 5.76669C7.35104 5.28419 7.14589 5.35003 6.99349 5.34169C6.8478 5.33503 6.682 5.33336 6.51621 5.33336C6.35041 5.33336 6.08079 5.39503 5.85303 5.64336C5.62444 5.89086 4.98219 6.49002 4.98219 7.70919C4.98219 8.92752 5.87313 10.105 5.99789 10.2709C6.12266 10.4359 7.75213 12.9375 10.2482 14.01C10.8428 14.265 11.3058 14.4175 11.6667 14.5308C12.2629 14.72 12.8055 14.6933 13.2342 14.6292C13.7115 14.5583 14.7063 14.03 14.9139 13.4517C15.1208 12.8733 15.1207 12.3775 15.0588 12.2742C14.9968 12.1708 14.831 12.1092 14.5815 11.985H14.5823ZM10.0423 18.1542H10.0389C8.55634 18.1544 7.10099 17.7578 5.8254 17.0058L5.52396 16.8275L2.39062 17.6458L3.22712 14.6058L3.03035 14.2942C2.20149 12.9811 1.76286 11.4615 1.76512 9.91085C1.76679 5.36919 5.47958 1.6742 10.0456 1.6742C12.2562 1.6742 14.3345 2.53253 15.897 4.08919C16.6676 4.85301 17.2785 5.76133 17.6941 6.7616C18.1098 7.76188 18.322 8.83425 18.3186 9.91668C18.3169 14.4583 14.6041 18.1542 10.0423 18.1542ZM17.086 2.9067C16.1634 1.98247 15.0657 1.24965 13.8564 0.7507C12.6472 0.251754 11.3505 -0.00339687 10.0414 3.41479e-05C4.55347 3.41479e-05 0.085409 4.44586 0.0837344 9.91002C0.0811914 11.649 0.539563 13.3578 1.4126 14.8642L0 20L5.27861 18.6217C6.73884 19.4134 8.37519 19.8283 10.0381 19.8283H10.0423C15.5302 19.8283 19.9983 15.3825 20 9.91752C20.004 8.61525 19.7485 7.32511 19.2484 6.12172C18.7482 4.91833 18.0132 3.82559 17.086 2.9067Z" fill="#0B982C"/>
</svg>
                                </span>
                                <span>{{ $contact->phone_amd }}</span>
                            </a>
                        @endif

                        @if(false && $contact?->phone_sale)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->phone_sale) }}" target="_blank" class="contact-detail">
                                <span class="contact-detail-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
  <path d="M16.666 3.33333H3.33268C2.41221 3.33333 1.66602 4.07952 1.66602 4.99999V15C1.66602 15.9205 2.41221 16.6667 3.33268 16.6667H16.666C17.5865 16.6667 18.3327 15.9205 18.3327 15V4.99999C18.3327 4.07952 17.5865 3.33333 16.666 3.33333Z" stroke="#0B982C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M18.3327 5.83333L10.8577 10.5833C10.6004 10.7445 10.3029 10.83 9.99935 10.83C9.69575 10.83 9.39829 10.7445 9.14102 10.5833L1.66602 5.83333" stroke="#0B982C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                                </span>
                                <span>{{ $contact->phone_sale }}</span>
                            </a>
                        @endif

                        @if($contact?->mail_adm)
                            <a href="mailto:{{ $contact->mail_adm }}" class="contact-detail">
                                <span class="contact-detail-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span>{{ $contact->mail_adm }}</span>
                            </a>
                        @endif
                    </div>
                </aside>

                <form wire:submit.prevent="submit" class="contact-form">
                    <div class="contact-fields">
                        <label class="contact-field">
                            <span>Nombre*</span>
                            <input type="text" wire:model.blur="name">
                            @error('name') <small>{{ $message }}</small> @enderror
                        </label>

                        <label class="contact-field">
                            <span>Apellido*</span>
                            <input type="text" wire:model.blur="company">
                            @error('company') <small>{{ $message }}</small> @enderror
                        </label>

                        <label class="contact-field">
                            <span>E-mail*</span>
                            <input type="email" wire:model.blur="email">
                            @error('email') <small>{{ $message }}</small> @enderror
                        </label>

                        <label class="contact-field">
                            <span>Celular*</span>
                            <input type="text" wire:model.blur="phone">
                            @error('phone') <small>{{ $message }}</small> @enderror
                        </label>

                        <label class="contact-field contact-message">
                            <span>Mensaje</span>
                            <textarea wire:model.blur="message"></textarea>
                            @error('message') <small>{{ $message }}</small> @enderror
                        </label>

                        <p class="contact-required">*Datos obligatorios</p>
                    </div>

                    <div class="contact-submit-row">
                        <button type="submit" class="contact-submit">Enviar consulta</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @if($contact?->frame_adm)
        <section class="contact-map-section">
            <div class="contact-map-shell">
                <div wire:ignore class="contact-map-frame">
                    {!! $contact->frame_adm !!}
                </div>
            </div>
        </section>
    @endif

    <style>
        .contact-banner {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background: #143a1a;
        }

        .contact-banner-image,
        .contact-banner-fallback {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .contact-banner-fallback {
            background: #143a1a;
        }

        .contact-banner-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%),
                linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%),
                linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%),
                linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);
        }

        .contact-banner-inner {
            position: relative;
            z-index: 2;
            max-width: 1224px;
            height: 100%;
            margin: 0 auto;
            padding-top: 94px;
            display: flex;
            flex-direction: column;
        }

        .contact-breadcrumb {
            display: flex;
            gap: 5px;
            color: #fff;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 148px;
        }

        .contact-breadcrumb a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .contact-banner h1 {
            margin: 0;
            color: #fff;
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
            text-shadow: 0 6px 20px rgba(0,0,0,.55);
        }

        .contact-section {
            background: #fff;
            padding: 92px 0 118px;
        }

        .contact-shell {
            max-width: 1224px;
            margin: 0 auto;
        }

        .contact-shell h2 {
            margin: 0 0 34px;
            color: #404041;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.15;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 360px minmax(0, 1fr);
            gap: 76px;
            align-items: start;
        }

        .contact-request-text {
            margin: 0 0 42px;
            color: #111;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.35;
        }

        .contact-request-text p {
            margin: 0 0 10px;
        }

        .contact-request-text p:last-child {
            margin-bottom: 0;
        }

        .contact-request-text strong,
        .contact-request-text b {
            font-weight: 700;
        }

        .contact-request-text ul,
        .contact-request-text ol {
            margin: 0 0 10px 18px;
            padding: 0;
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .contact-detail {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            color: #1f1f1f;
            font-size: 16px;
            line-height: 1.45;
            text-decoration: none;
        }

        .contact-detail-icon {
            flex: 0 0 auto;
            color: #0B982C;
            margin-top: 1px;
        }

        .contact-form {
            padding-top: 5px;
        }

        .contact-fields {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            column-gap: 56px;
            row-gap: 34px;
        }

        .contact-field {
            display: flex;
            flex-direction: column;
            gap: 10px;
            color: #404041;
            font-size: 16px;
            line-height: 1;
        }

        .contact-field input,
        .contact-field textarea {
            width: 100%;
            border: 0;
            border-bottom: 1px dotted #a8a8a8;
            border-radius: 0;
            padding: 0 0 12px;
            color: #1f1f1f;
            font: inherit;
            outline: none;
            background: transparent;
            box-shadow: none;
        }

        .contact-field input:focus,
        .contact-field textarea:focus {
            border-bottom-color: #52A028;
            box-shadow: none;
        }

        .contact-field textarea {
            min-height: 148px;
            resize: vertical;
        }

        .contact-message {
            grid-column: 1 / 2;
        }

        .contact-required {
            margin: 0;
            color: #1f1f1f;
            font-size: 16px;
            line-height: 1.3;
        }

        .contact-field small {
            color: #dc2626;
            font-size: 12px;
            line-height: 1.2;
        }

        .contact-submit-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            column-gap: 56px;
            margin-top: -42px;
            align-items: start;
        }

        .contact-submit {
            grid-column: 2;
            width: 100%;
            min-height: 42px;
            border: 0;
            border-radius: 0;
            background: #52A028;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
            transition: background .2s ease;
        }

        .contact-submit:hover {
            background: #439e33;
        }

        .contact-map-section {
            background: #fff;
            padding: 0 0 96px;
        }

        .contact-map-shell {
            max-width: 1224px;
            margin: 0 auto;
        }

        .contact-map-frame {
            width: 100%;
            height: 430px;
            overflow: hidden;
            background: #f3f4f6;
        }

        .contact-map-frame iframe {
            width: 100% !important;
            height: 100% !important;
            border: 0 !important;
            display: block;
            filter: grayscale(100%);
        }

        @media (max-width: 1199px) {
            .contact-banner {
                height: 340px;
            }

            .contact-banner-inner,
            .contact-shell,
            .contact-map-shell {
                padding-left: 24px;
                padding-right: 24px;
            }

            .contact-map-frame {
                height: 380px;
            }

            .contact-breadcrumb {
                margin-bottom: 85px;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .contact-info {
                max-width: 520px;
                min-width: 390px;
            }
        }

        @media (max-width: 767px) {
            .contact-banner {
                height: 290px;
            }

            .contact-banner-inner {
                padding-top: 74px;
            }

            .contact-breadcrumb {
                margin-bottom: 76px;
            }

            .contact-banner h1 {
                font-size: 32px;
            }

            .contact-section {
                padding: 56px 0 72px;
            }

            .contact-map-section {
                padding-bottom: 72px;
            }

            .contact-map-frame {
                height: 330px;
            }

            .contact-shell h2 {
                font-size: 28px;
            }

            .contact-fields {
                grid-template-columns: 1fr;
                gap: 26px;
            }

            .contact-message {
                grid-column: auto;
            }

            .contact-submit {
                grid-column: auto;
            }

            .contact-submit-row {
                grid-template-columns: 1fr;
                margin-top: 28px;
            }
        }

        @media (max-width: 639px) {
            .contact-banner {
                height: 250px;
            }

            .contact-banner-inner,
            .contact-shell,
            .contact-map-shell {
                padding-left: 16px;
                padding-right: 16px;
            }

            .contact-map-frame {
                height: 290px;
            }

            .contact-banner-inner {
                padding-top: 64px;
            }

            .contact-breadcrumb {
                margin-bottom: 60px;
            }

            .contact-shell h2 {
                font-size: 25px;
            }

            .contact-request-text,
            .contact-detail,
            .contact-field,
            .contact-required,
            .contact-submit {
                font-size: 15px;
            }
        }

        @keyframes con-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .contact-breadcrumb {
            animation: con-fade-up 0.65s ease 0.38s both;
        }

        .contact-banner h1 {
            animation: con-fade-up 0.65s ease 0.52s both;
        }

        .contact-shell h2 {
            animation: con-fade-up 0.65s ease 0.58s both;
        }

        .contact-info {
            animation: con-fade-up 0.7s ease 0.62s both;
        }

        .contact-form {
            animation: con-fade-up 0.7s ease 0.74s both;
        }

        .contact-map-section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .contact-map-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
    (function() {
        function initReveal() {
            var map = document.querySelector('.contact-map-section');
            if (!map) return;
            map.classList.remove('is-visible');
            var ro = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        ro.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08 });
            ro.observe(map);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initReveal);
        } else {
            initReveal();
        }

        document.addEventListener('livewire:navigated', initReveal);
    })();
    </script>
</div>
