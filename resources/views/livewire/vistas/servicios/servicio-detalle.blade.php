@php
    $galleryImages = $service->images->values();
    $lightboxImages = collect();

    if ($service->image) {
        $lightboxImages->push([
            'src' => Storage::url($service->image),
            'alt' => $service->title,
        ]);
    }

    foreach ($galleryImages as $image) {
        $lightboxImages->push([
            'src' => Storage::url($image->image),
            'alt' => $service->title,
        ]);
    }
@endphp

<div class="w-full service-detail-page"
     x-data="{
        show: false,
        open: false,
        active: 0,
        images: @js($lightboxImages),
        showImage(index) {
            if (!this.images.length) return;
            this.active = index;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.open = false;
            document.body.style.overflow = '';
        },
        next() {
            if (!this.images.length) return;
            this.active = (this.active + 1) % this.images.length;
        },
        prev() {
            if (!this.images.length) return;
            this.active = (this.active - 1 + this.images.length) % this.images.length;
        }
     }"
     x-init="setTimeout(() => show = true, 50)"
     x-show="show"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 transform -translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     @keydown.escape.window="open && close()"
     @keydown.arrow-right.window="open && next()"
     @keydown.arrow-left.window="open && prev()">

    <nav class="svc-breadcrumb max-w-[1224px] mx-auto text-black text-[12px] leading-[150%] flex items-center gap-1 mt-[24px] max-[1199px]:px-4 max-[1199px]:flex-wrap">
        <a wire:navigate href="{{ url('/') }}" class="font-bold">Inicio</a>
        <span>›</span>
        <a wire:navigate href="{{ route('servicios.public') }}" class="font-bold">Servicios</a>
        <span>›</span>
        <span class="text-slate-500 line-clamp-1">{{ $service->title }}</span>
    </nav>

    <section class="max-w-[1224px] mx-auto px-4 lg:px-0 pt-[48px] pb-[96px] max-[767px]:pt-8 max-[767px]:pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[40px] max-[767px]:gap-8 items-stretch">
            <figure
                class="svc-detail-img {{ $service->image ? 'service-gallery-item' : '' }} w-full overflow-hidden bg-slate-100"
                @if($service->image)
                    role="button"
                    tabindex="0"
                    @click="showImage(0)"
                    @keydown.enter.prevent="showImage(0)"
                    @keydown.space.prevent="showImage(0)"
                    aria-label="Abrir imagen del servicio"
                @endif
            >
                @if($service->image)
                    <img src="{{ Storage::url($service->image) }}"
                         alt="{{ $service->title }}"
                         class="w-full h-[500px] max-[1199px]:h-[420px] max-[767px]:h-[280px] object-cover">
                @endif
            </figure>

            <div class="svc-detail-info pt-2 flex flex-col">
                <h1 class="text-[#3f3f3f] text-[32px] max-[767px]:text-[26px] font-bold leading-tight">
                    {{ $service->title }}
                </h1>

                <div class="h-px bg-slate-200 my-[22px]"></div>

                <div class="service-content text-[#1f1f1f] text-[17px] leading-[1.85] max-[767px]:text-[15px]">
                    @if($service->description)
                        @php
                            $description = str_replace('&nbsp;', ' ', $service->description);
                            $hasCustomList = stripos($description, '<li') !== false;
                            $hasLineBreaks = preg_match('/<br\s*\/?>/i', $description);

                            if (!$hasCustomList && $hasLineBreaks) {
                                $description = preg_replace('/<\/p>\s*<p[^>]*>/i', '<br>', $description);
                                $description = preg_replace('/<\/?p[^>]*>/i', '', $description);
                                $items = array_filter(array_map('trim', preg_split('/<br\s*\/?>/i', $description)));
                            }
                        @endphp

                        @if(!$hasCustomList && $hasLineBreaks && !empty($items))
                            <ul>
                                @foreach($items as $item)
                                    <li>{!! $item !!}</li>
                                @endforeach
                            </ul>
                        @else
                            {!! $description !!}
                        @endif
                    @else
                        <p></p>
                    @endif
                </div>

                <div class="mt-auto pt-8 max-[767px]:pt-6">
                    <a href="{{ route('presupuesto.public', ['service' => $service->title]) }}"
                       class="inline-flex w-[265px] h-[38px] items-center justify-center bg-[#4c9f26] text-white text-[15px] font-bold hover:bg-[#3f8620] transition">
                        Pedir presupuesto
                    </a>
                </div>
            </div>
        </div>

        @if($galleryImages->isNotEmpty())
            <div class="service-masonry">
                @foreach($galleryImages->chunk(2) as $row)
                    <div class="service-masonry-row {{ $row->count() === 1 ? 'is-single' : 'is-pair' }}">
                        @foreach($row as $image)
                            @php
                                $galleryIndex = $galleryImages->search(fn($item) => $item->id === $image->id);
                                $imageIndex = ($service->image ? 1 : 0) + $galleryIndex;
                            @endphp
                            <figure
                                class="service-gallery-item"
                                role="button"
                                tabindex="0"
                                @click="showImage({{ $imageIndex }})"
                                @keydown.enter.prevent="showImage({{ $imageIndex }})"
                                @keydown.space.prevent="showImage({{ $imageIndex }})"
                                aria-label="Abrir imagen {{ $imageIndex + 1 }} de {{ $lightboxImages->count() }}"
                            >
                                <img src="{{ Storage::url($image->image) }}" alt="{{ $service->title }}" loading="lazy">
                            </figure>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <div
        x-show="open"
        x-cloak
        x-transition.opacity.duration.200ms
        class="service-lightbox"
        role="dialog"
        aria-modal="true"
        aria-label="Galeria de imagenes del servicio"
    >
        <button type="button" class="service-lightbox-backdrop" @click="close()" aria-label="Cerrar galeria"></button>

        <div class="service-lightbox-panel">
            <div class="service-lightbox-topbar">
                <div class="service-lightbox-meta">
                    <span>{{ $service->title }}</span>
                    <small x-text="`${active + 1} / ${images.length}`"></small>
                </div>

                <button type="button" class="service-lightbox-close" @click="close()" aria-label="Cerrar">
                    <svg width="38" height="38" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <button
                type="button"
                class="service-lightbox-nav is-prev"
                @click="prev()"
                x-show="images.length > 1"
                aria-label="Imagen anterior"
            >
                <svg width="58" height="58" viewBox="0 0 24 24" fill="none">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="service-lightbox-image-wrap">
                <img :src="images[active]?.src" :alt="images[active]?.alt" class="service-lightbox-image">
            </div>

            <button
                type="button"
                class="service-lightbox-nav is-next"
                @click="next()"
                x-show="images.length > 1"
                aria-label="Imagen siguiente"
            >
                <svg width="58" height="58" viewBox="0 0 24 24" fill="none">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }

        @keyframes svc-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .svc-breadcrumb {
            animation: svc-fade-up 0.6s ease 0.3s both;
        }

        .svc-detail-img {
            animation: svc-fade-up 0.7s ease 0.42s both;
            margin: 0;
        }

        .svc-detail-info {
            animation: svc-fade-up 0.7s ease 0.56s both;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .service-content {
            max-height: 340px;
            overflow-y: auto;
            padding-right: 10px;
            margin-bottom: 12px;
            scrollbar-width: thin;
            scrollbar-color: #52A028 #f0f0f0;
        }

        .service-content::-webkit-scrollbar {
            width: 4px;
        }

        .service-content::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 4px;
        }

        .service-content::-webkit-scrollbar-thumb {
            background: #52A028;
            border-radius: 4px;
        }

        .service-content::-webkit-scrollbar-thumb:hover {
            background: #3d7a1e;
        }

        .service-content p {
            margin-bottom: 1rem;
        }

        .service-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .service-content li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 14px;
        }

        .service-content li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 7px;
            width: 19px;
            height: 19px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='19' height='19' viewBox='0 0 19 19' fill='none'%3E%3Cpath d='M9.33333 17.6667C13.9358 17.6667 17.6667 13.9358 17.6667 9.33333C17.6667 4.73083 13.9358 1 9.33333 1C4.73083 1 1 4.73083 1 9.33333C1 13.9358 4.73083 17.6667 9.33333 17.6667Z' stroke='%2352A028' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3Cpath d='M6.33333 9.33333L8 11L11.3333 7.66667' stroke='%230B982C' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }

        .service-content li::after {
            content: none;
        }

        .service-content h2,
        .service-content h3 {
            font-weight: 700;
            margin-top: 1.4rem;
            margin-bottom: .75rem;
        }

        .service-masonry {
            margin-top: 64px;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .service-masonry-row {
            display: grid;
            gap: 28px;
        }

        .service-masonry-row.is-single {
            grid-template-columns: 1fr;
        }

        .service-masonry-row.is-pair {
            grid-template-columns: minmax(0, 1fr) minmax(0, 2fr);
        }

        .service-masonry-row:nth-child(even).is-pair {
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
        }

        .service-masonry figure {
            margin: 0;
            height: 475px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .service-gallery-item {
            position: relative;
            cursor: zoom-in;
            outline: none;
        }

        .service-gallery-item::after {
            content: "Ver imagen";
            position: absolute;
            right: 16px;
            bottom: 16px;
            z-index: 2;
            padding: 8px 12px;
            background: rgba(0, 0, 0, .58);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            opacity: 0;
            transform: translateY(6px);
            transition: opacity .2s ease, transform .2s ease;
        }

        .service-gallery-item:hover::after,
        .service-gallery-item:focus-visible::after {
            opacity: 1;
            transform: translateY(0);
        }

        .service-gallery-item:focus-visible {
            box-shadow: 0 0 0 3px rgba(82, 160, 40, .45);
        }

        .service-masonry img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .service-lightbox {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
        }

        .service-lightbox-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(7, 12, 9, .86);
            backdrop-filter: blur(10px);
            cursor: zoom-out;
        }

        .service-lightbox-panel {
            position: relative;
            z-index: 1;
            width: min(1180px, 100%);
            height: min(760px, calc(100vh - 56px));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .service-lightbox-topbar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            color: #fff;
        }

        .service-lightbox-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .service-lightbox-meta span {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: min(760px, calc(100vw - 160px));
        }

        .service-lightbox-meta small {
            color: rgba(255,255,255,.72);
            font-size: 13px;
        }

        .service-lightbox-close,
        .service-lightbox-nav {
            border: 0;
            background: transparent;
            color: #52A028;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color .2s ease, transform .2s ease, opacity .2s ease;
        }

        .service-lightbox-close {
            width: 48px;
            height: 48px;
        }

        .service-lightbox-close:hover,
        .service-lightbox-nav:hover {
            color: #6fc844;
        }

        .service-lightbox-image-wrap {
            width: 100%;
            height: calc(100% - 78px);
            margin-top: 78px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .service-lightbox-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            box-shadow: 0 28px 80px rgba(0,0,0,.45);
        }

        .service-lightbox-nav {
            position: absolute;
            top: 50%;
            z-index: 3;
            width: 72px;
            height: 72px;
            transform: translateY(-50%);
        }

        .service-lightbox-nav:hover {
            transform: translateY(-50%) scale(1.04);
        }

        .service-lightbox-nav.is-prev {
            left: -72px;
        }

        .service-lightbox-nav.is-next {
            right: -72px;
        }

        .service-masonry-row {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .service-masonry-row.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 767px) {
            .service-masonry-row,
            .service-masonry-row.is-pair {
                grid-template-columns: 1fr;
            }

            .service-masonry figure {
                height: 260px;
            }

            .service-lightbox {
                padding: 16px;
            }

            .service-lightbox-panel {
                height: calc(100vh - 32px);
            }

            .service-lightbox-nav {
                top: auto;
                bottom: 18px;
                transform: none;
                width: 46px;
                height: 46px;
            }

            .service-lightbox-nav:hover {
                transform: scale(1.04);
            }

            .service-lightbox-nav.is-prev {
                left: calc(50% - 58px);
            }

            .service-lightbox-nav.is-next {
                right: calc(50% - 58px);
            }

            .service-lightbox-image-wrap {
                height: calc(100% - 120px);
                margin-top: 70px;
                margin-bottom: 50px;
            }
        }
    </style>

    <script>
    (function() {
        function initServiceMasonryReveal() {
            var rows = Array.prototype.slice.call(document.querySelectorAll('.service-masonry-row'));
            if (!rows.length) return;

            rows.forEach(function(row) { row.classList.remove('is-visible'); });

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.05 });

            rows.forEach(function(row) { observer.observe(row); });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initServiceMasonryReveal);
        } else {
            initServiceMasonryReveal();
        }

        document.addEventListener('livewire:navigated', initServiceMasonryReveal);
    })();
    </script>
</div>
