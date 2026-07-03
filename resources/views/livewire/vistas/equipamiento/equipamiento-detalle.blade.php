@php
    $images = $product->images;
    $mainImage = $images->firstWhere('is_main', true) ?: $images->first();
    $mainImageIndex = $mainImage ? $images->values()->search(fn($image) => $image->id === $mainImage->id) : 0;

    $description = str_replace('&nbsp;', ' ', (string) $product->description);
    $hasCustomList = stripos($description, '<li') !== false;

    if (!$hasCustomList) {
        $description = preg_replace('/<\/p>\s*<p[^>]*>/i', '<br>', $description);
        $description = preg_replace('/<\/?p[^>]*>/i', '', $description);
    }

    $descriptionLines = collect(preg_split('/\r\n|\r|\n|<br\s*\/?>/i', $description))
        ->map(fn($line) => trim($line))
        ->filter(fn($line) => trim(strip_tags($line)) !== '')
        ->filter()
        ->values();
@endphp

<div
    class="equipment-detail"
    x-data="{
        open: false,
        active: {{ $mainImageIndex === false ? 0 : $mainImageIndex }},
        images: @js($images->values()->map(fn($image) => [
            'src' => Storage::url($image->image),
            'alt' => $product->title,
        ])),
        setActive(index) {
            this.active = index;
        },
        show(index) {
            this.active = index;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.open = false;
            document.body.style.overflow = '';
        },
        next() {
            this.active = (this.active + 1) % this.images.length;
        },
        prev() {
            this.active = (this.active - 1 + this.images.length) % this.images.length;
        }
    }"
    @keydown.escape.window="open && close()"
    @keydown.arrow-right.window="open && next()"
    @keydown.arrow-left.window="open && prev()"
>
    <nav class="equipment-detail-breadcrumb">
        <a wire:navigate href="{{ url('/') }}">Inicio</a>
        <span>&rsaquo;</span>
        <a wire:navigate href="{{ route('productos') }}">Equipamiento industrial</a>
        <span>&rsaquo;</span>
        <span>{{ $product->title }}</span>
    </nav>

    <section class="equipment-detail-main">
        <div class="equipment-gallery">
            <div
                class="equipment-gallery-main"
                role="button"
                tabindex="0"
                @click="images.length && show(active)"
                @keydown.enter.prevent="images.length && show(active)"
                @keydown.space.prevent="images.length && show(active)"
                aria-label="Abrir imagen de {{ $product->title }}"
            >
                @if($mainImage)
                    <img :src="images[active]?.src" :alt="images[active]?.alt">
                @endif
            </div>

            @if($images->count() > 1)
                <div class="equipment-thumbs">
                    @foreach($images->values()->take(4) as $image)
                        @php $isOverflow = $loop->last && $images->count() > 4; @endphp
                        <button
                            type="button"
                            @click="setActive({{ $loop->index }})"
                            :class="{ 'is-active': active === {{ $loop->index }} }"
                            aria-label="Seleccionar imagen {{ $loop->iteration }} de {{ $images->count() }}"
                        >
                            <img src="{{ Storage::url($image->image) }}" alt="{{ $product->title }}">
                            @if($isOverflow)
                                <span class="equipment-thumb-more">+{{ $images->count() - 4 }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="equipment-info">
            <h1>{{ $product->title }}</h1>

            <div class="equipment-checks equipment-rich-content">
                @if($hasCustomList)
                    {!! $description !!}
                @else
                    @forelse($descriptionLines as $line)
                        <div class="equipment-check-item">
                            <span class="equipment-check-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                                    <path d="M9.33333 17.6667C13.9358 17.6667 17.6667 13.9358 17.6667 9.33333C17.6667 4.73083 13.9358 1 9.33333 1C4.73083 1 1 4.73083 1 9.33333C1 13.9358 4.73083 17.6667 9.33333 17.6667Z" stroke="#52A028" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6.3 9.2L8.2 11.1L12.5 6.9" stroke="#0B982C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <p>{!! $line !!}</p>
                        </div>
                    @empty
                        <div class="equipment-description">
                            {!! $description !!}
                        </div>
                    @endforelse
                @endif
            </div>

            <div class="equipment-actions mt-2">
                @if($product->technical_sheet)
                    <a href="{{ Storage::url($product->technical_sheet) }}" target="_blank" class="equipment-btn equipment-btn-outline">
                        Ficha t&eacute;cnica
                    </a>
                @endif
                <a wire:navigate href="{{ route('presupuesto.public', ['equipment' => $product->title]) }}" class="equipment-btn equipment-btn-fill">
                    Pedir presupuesto
                </a>
            </div>
        </div>
    </section>

    @if($relatedProducts->isNotEmpty())
        <section class="equipment-related {{ $relatedProducts->count() > 4 ? 'has-carousel' : '' }}"
                 @if($relatedProducts->count() > 4) data-equipment-related-carousel @endif>
            <h2>{{ $isAutoRelated ? 'Más de esta categoría' : 'Equipamiento relacionado' }}</h2>

            <div class="equipment-related-grid" data-related-track>
                @foreach($relatedProducts as $related)
                    @php $cover = $related->mainImage ?: $related->images->first(); @endphp
                    <a wire:navigate href="{{ route('productos.detalle', $related->slug) }}" class="equipment-related-card">
                        <span>
                            @if($cover)
                                <img src="{{ Storage::url($cover->image) }}" alt="{{ $related->title }}" loading="lazy">
                            @endif
                        </span>
                        <strong>{{ $related->title }}</strong>
                    </a>
                @endforeach
            </div>

            @if($relatedProducts->count() > 4)
                <div class="equipment-related-dots" data-related-dots aria-label="Navegaci&oacute;n de equipamiento relacionado"></div>
            @endif
        </section>
    @endif

    <div
        x-show="open"
        x-cloak
        x-transition.opacity.duration.200ms
        class="equipment-lightbox"
        role="dialog"
        aria-modal="true"
        aria-label="Galeria de imagenes de equipamiento"
    >
        <button type="button" class="equipment-lightbox-backdrop" @click="close()" aria-label="Cerrar galeria"></button>

        <div class="equipment-lightbox-panel">
            <div class="equipment-lightbox-topbar">
                <div class="equipment-lightbox-meta">
                    <span>{{ $product->title }}</span>
                    <small x-text="`${active + 1} / ${images.length}`"></small>
                </div>

                <button type="button" class="equipment-lightbox-close" @click="close()" aria-label="Cerrar">
                    <svg width="38" height="38" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <button
                type="button"
                class="equipment-lightbox-nav is-prev"
                @click="prev()"
                x-show="images.length > 1"
                aria-label="Imagen anterior"
            >
                <svg width="58" height="58" viewBox="0 0 24 24" fill="none">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="equipment-lightbox-image-wrap">
                <img :src="images[active]?.src" :alt="images[active]?.alt" class="equipment-lightbox-image">
            </div>

            <button
                type="button"
                class="equipment-lightbox-nav is-next"
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

        .equipment-detail {
            --equipment-main-height: 450px;
            max-width: 1224px;
            margin: 0 auto;
            padding: 24px 0 112px;
        }

        .equipment-detail-breadcrumb {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            color: #8a8a8a;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 54px;
        }

        .equipment-detail-breadcrumb a {
            color: #555;
            font-weight: 700;
            text-decoration: none;
        }

        .equipment-detail-main {
            display: grid;
            grid-template-columns: 600px 1fr;
            gap: 24px;
            align-items: start;
        }

        .equipment-gallery-main {
            width: 600px;
            max-width: 100%;
            height: var(--equipment-main-height);
            border: 1px solid #E5E5E5;
            position: relative;
            cursor: zoom-in;
            outline: none;
            overflow: hidden;
        }

        .equipment-gallery-main::after {
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

        .equipment-gallery-main:hover::after,
        .equipment-gallery-main:focus-visible::after {
            opacity: 1;
            transform: translateY(0);
        }

        .equipment-gallery-main:focus-visible {
            box-shadow: 0 0 0 3px rgba(82, 160, 40, .45);
        }

        .equipment-gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .equipment-thumbs {
            display: flex;
            gap: 18px;
            margin-top: 24px;
        }

        .equipment-thumbs button {
            position: relative;
            width: 82px;
            height: 82px;
            border: 1px solid #E5E5E5;
            background: #fff;
            padding: 0;
            cursor: pointer;
            overflow: hidden;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .equipment-thumbs button.is-active {
            border-color: #52A028;
            box-shadow: 0 0 0 1px #52A028;
        }

        .equipment-thumbs img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .equipment-thumb-more {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .52);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            pointer-events: none;
        }

        .equipment-info {
            padding-left: 0;
            min-height: var(--equipment-main-height);
            display: flex;
            flex-direction: column;
        }

        .equipment-info h1 {
            margin: 0 0 26px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
            color: #404041;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.15;
        }

        .equipment-checks {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 390px;
            overflow-y: auto;
            padding-right: 10px;
            margin-bottom: 12px;
            scrollbar-width: thin;
            scrollbar-color: #52A028 #f0f0f0;
        }

        .equipment-checks::-webkit-scrollbar {
            width: 4px;
        }

        .equipment-checks::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 4px;
        }

        .equipment-checks::-webkit-scrollbar-thumb {
            background: #52A028;
            border-radius: 4px;
        }

        .equipment-checks::-webkit-scrollbar-thumb:hover {
            background: #3d7a1e;
        }

        .equipment-check-item {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            color: #222;
            font-size: 16px;
            line-height: 1.25;
        }

        .equipment-check-item p {
            margin: 0;
        }

        .equipment-rich-content strong,
        .equipment-rich-content b {
            font-weight: 700;
        }

        .equipment-rich-content em,
        .equipment-rich-content i {
            font-style: italic;
        }

        .equipment-rich-content a {
            color: #404041;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .equipment-rich-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .equipment-rich-content li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 12px;
        }

        .equipment-rich-content li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 3px;
            width: 19px;
            height: 19px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='19' height='19' viewBox='0 0 19 19' fill='none'%3E%3Cpath d='M9.33333 17.6667C13.9358 17.6667 17.6667 13.9358 17.6667 9.33333C17.6667 4.73083 13.9358 1 9.33333 1C4.73083 1 1 4.73083 1 9.33333C1 13.9358 4.73083 17.6667 9.33333 17.6667Z' stroke='%2352A028' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3Cpath d='M6.3 9.2L8.2 11.1L12.5 6.9' stroke='%230B982C' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-size: contain;
        }

        .equipment-check-icon {
            flex: 0 0 19px;
            margin-top: 1px;
        }

        .equipment-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            margin-top: auto;

        }

        .equipment-btn {
            height: 41px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
        }

        .equipment-btn-outline {
            border: 1px solid #52A028;
            color: #52A028;
            background: #fff;
        }

        .equipment-btn-fill {
            border: 1px solid #52A028;
            color: #fff;
            background: #52A028;
        }

        .equipment-related {
            margin-top: 64px;
        }

        .equipment-related h2 {
            margin: 0 0 42px;
            color: #404041;
            font-size: 32px;
            font-weight: 700;
        }

        .equipment-related-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
        }

        .equipment-related.has-carousel .equipment-related-grid {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
        }

        .equipment-related.has-carousel .equipment-related-grid::-webkit-scrollbar {
            display: none;
        }

        .equipment-related-card {
            border: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            color: inherit;
            min-height: 300px;
            text-decoration: none;
            overflow: hidden;
            transition: border-color .2s ease, transform .2s ease;
        }

        .equipment-related-card:hover {
            border-color: #52A028;
        }

        .equipment-related.has-carousel .equipment-related-card {
            flex: 0 0 calc((100% - 72px) / 4);
        }

        .equipment-related-card span {
            height: 200px;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .equipment-related-card img {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
        }

        .equipment-related-card strong {
            padding-block: 8px;
            padding-inline: 24px;
            color: #737275;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.15;
        }

        .equipment-related-dots {
            display: none;
            justify-content: center;
            gap: 10px;
            margin-top: 36px;
        }

        .equipment-related.has-carousel .equipment-related-dots {
            display: flex;
        }

        .equipment-related-dots button {
            width: 43.912px;
            height: 6px;
            border: 0;
            border-radius: 0;
            background: #e5e5e5;
            padding: 0;
            cursor: pointer;
            transition: background .2s ease;
        }

        .equipment-related-dots button.is-active {
            background: #bfbfbf;
        }

        .equipment-lightbox {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
        }

        .equipment-lightbox-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(7, 12, 9, .86);
            backdrop-filter: blur(10px);
            cursor: zoom-out;
            border: 0;
        }

        .equipment-lightbox-panel {
            position: relative;
            z-index: 1;
            width: min(1180px, 100%);
            height: min(760px, calc(100vh - 56px));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .equipment-lightbox-topbar {
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

        .equipment-lightbox-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .equipment-lightbox-meta span {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: min(760px, calc(100vw - 160px));
        }

        .equipment-lightbox-meta small {
            color: rgba(255,255,255,.72);
            font-size: 13px;
        }

        .equipment-lightbox-close,
        .equipment-lightbox-nav {
            border: 0;
            background: transparent;
            color: #52A028;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color .2s ease, transform .2s ease, opacity .2s ease;
        }

        .equipment-lightbox-close {
            width: 48px;
            height: 48px;
        }

        .equipment-lightbox-close:hover,
        .equipment-lightbox-nav:hover {
            color: #6fc844;
        }

        .equipment-lightbox-image-wrap {
            width: 100%;
            height: calc(100% - 78px);
            margin-top: 78px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .equipment-lightbox-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            box-shadow: 0 28px 80px rgba(0,0,0,.45);
        }

        .equipment-lightbox-nav {
            position: absolute;
            top: 50%;
            z-index: 3;
            width: 72px;
            height: 72px;
            transform: translateY(-50%);
        }

        .equipment-lightbox-nav:hover {
            transform: translateY(-50%) scale(1.04);
        }

        .equipment-lightbox-nav.is-prev {
            left: -72px;
        }

        .equipment-lightbox-nav.is-next {
            right: -72px;
        }

        @media (max-width: 1199px) {
            .equipment-detail {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
                padding-top: 20px;
            }

            .equipment-detail-breadcrumb {
                margin-bottom: 36px;
            }

            .equipment-gallery-main {
                width: 100%;
                height: 380px;
            }

            .equipment-detail-main {
                grid-template-columns: 1fr;
            }

            .equipment-info {
                height: auto;
                min-height: 0;
            }

            .equipment-actions {
                margin-top: 42px;
            }

            .equipment-related {
                margin-top: 52px;
            }

            .equipment-related h2 {
                font-size: 26px;
                margin-bottom: 32px;
            }

            .equipment-related-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .equipment-related.has-carousel .equipment-related-card {
                flex-basis: calc((100% - 48px) / 3);
            }
        }

        @media (max-width: 767px) {
            .equipment-detail {
                margin-left: 20px;
                margin-right: 20px;
                padding-top: 16px;
                padding-bottom: 64px;
            }

            .equipment-detail-breadcrumb {
                margin-bottom: 22px;
                font-size: 11px;
            }

            .equipment-gallery-main {
                height: 260px;
            }

            .equipment-thumbs {
                gap: 10px;
                margin-top: 14px;
                flex-wrap: wrap;
            }

            .equipment-thumbs button {
                width: 62px;
                height: 62px;
                padding: 6px;
            }

            .equipment-info h1 {
                font-size: 22px;
                margin-bottom: 18px;
                padding-bottom: 14px;
            }

            .equipment-check-item {
                font-size: 15px;
            }

            .equipment-actions {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-top: 28px;
            }

            .equipment-btn {
                font-size: 15px;
            }

            .equipment-related {
                margin-top: 40px;
            }

            .equipment-related h2 {
                font-size: 22px;
                margin-bottom: 24px;
            }

            .equipment-related-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }

            .equipment-related.has-carousel .equipment-related-card {
                flex-basis: calc((100% - 16px) / 2);
            }

            .equipment-related-card strong {
                font-size: 15px;
                padding-inline: 12px;
                padding-block: 10px;
            }

            .equipment-lightbox {
                padding: 16px;
            }

            .equipment-lightbox-panel {
                height: calc(100vh - 32px);
            }

            .equipment-lightbox-nav {
                top: auto;
                bottom: 18px;
                transform: none;
                width: 46px;
                height: 46px;
            }

            .equipment-lightbox-nav:hover {
                transform: scale(1.04);
            }

            .equipment-lightbox-nav.is-prev {
                left: calc(50% - 58px);
            }

            .equipment-lightbox-nav.is-next {
                right: calc(50% - 58px);
            }

            .equipment-lightbox-image-wrap {
                height: calc(100% - 120px);
                margin-top: 70px;
                margin-bottom: 50px;
            }
        }

        @keyframes eq-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .equipment-detail-breadcrumb {
            animation: eq-fade-up 0.6s ease 0.05s both;
        }

        .equipment-gallery {
            animation: eq-fade-up 0.7s ease 0.12s both;
        }

        .equipment-info {
            animation: eq-fade-up 0.7s ease 0.24s both;
        }

        .equipment-related {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .equipment-related.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
    (function() {
        function initRelatedCarousels() {
            document.querySelectorAll('[data-equipment-related-carousel]').forEach(function(section) {
                if (section.dataset.relatedCarouselReady === 'true') return;
                section.dataset.relatedCarouselReady = 'true';

                var track = section.querySelector('[data-related-track]');
                var dotsWrap = section.querySelector('[data-related-dots]');
                var autoplayTimer = null;
                var currentPage = 0;
                var activeLockUntil = 0;

                if (!track || !dotsWrap) return;

                function getGap() {
                    var styles = window.getComputedStyle(track);
                    return parseFloat(styles.columnGap || styles.gap) || 0;
                }

                function getCards() {
                    return Array.prototype.slice.call(track.querySelectorAll('.equipment-related-card'));
                }

                function getVisibleCount(cards) {
                    if (!cards.length) return 1;

                    var cardWidth = cards[0].getBoundingClientRect().width;
                    return Math.max(1, Math.floor((track.clientWidth + getGap()) / (cardWidth + getGap())));
                }

                function getCardLeft(card) {
                    if (!card) return 0;

                    var cardRect = card.getBoundingClientRect();
                    var trackRect = track.getBoundingClientRect();

                    return Math.max(0, Math.round(cardRect.left - trackRect.left + track.scrollLeft));
                }

                function setActiveDot(page) {
                    Array.prototype.slice.call(dotsWrap.querySelectorAll('button')).forEach(function(dot, index) {
                        dot.classList.toggle('is-active', index === page);
                    });
                }

                function getData() {
                    var cards = getCards();
                    var visibleCount = getVisibleCount(cards);
                    var pages = Math.ceil(cards.length / visibleCount);

                    return { cards: cards, visibleCount: visibleCount, pages: pages };
                }

                function goToPage(page) {
                    var data = getData();

                    if (!data.pages || data.pages <= 1) return;

                    currentPage = (page + data.pages) % data.pages;
                    activeLockUntil = Date.now() + 650;
                    setActiveDot(currentPage);

                    var targetCard = data.cards[Math.min(currentPage * data.visibleCount, data.cards.length - 1)];

                    if (targetCard) {
                        track.scrollTo({ left: getCardLeft(targetCard), behavior: 'smooth' });
                    }
                }

                function stopAutoplay() {
                    if (autoplayTimer) {
                        clearInterval(autoplayTimer);
                        autoplayTimer = null;
                    }
                }

                function startAutoplay() {
                    stopAutoplay();

                    if (getData().pages <= 1) return;

                    autoplayTimer = setInterval(function() {
                        goToPage(currentPage + 1);
                    }, 5000);
                }

                function updateDots() {
                    if (Date.now() < activeLockUntil) return;

                    var data = getData();
                    var dots = Array.prototype.slice.call(dotsWrap.querySelectorAll('button'));

                    if (!dots.length) return;

                    var activePage = 0;
                    var smallestDistance = Infinity;

                    dots.forEach(function(dot, index) {
                        var targetCard = data.cards[Math.min(index * data.visibleCount, data.cards.length - 1)];
                        var targetLeft = targetCard ? getCardLeft(targetCard) : Infinity;
                        var distance = Math.abs(track.scrollLeft - targetLeft);

                        if (distance < smallestDistance) {
                            smallestDistance = distance;
                            activePage = index;
                        }
                    });

                    currentPage = activePage;
                    setActiveDot(activePage);
                }

                function buildDots() {
                    var data = getData();

                    stopAutoplay();
                    dotsWrap.innerHTML = '';
                    section.classList.toggle('has-carousel', data.pages > 1);

                    if (data.pages <= 1) return;

                    for (var i = 0; i < data.pages; i++) {
                        var dot = document.createElement('button');
                        dot.type = 'button';
                        dot.setAttribute('aria-label', 'Ir al grupo ' + (i + 1) + ' de equipamiento relacionado');
                        dot.dataset.page = i;
                        dot.addEventListener('click', function() {
                            stopAutoplay();
                            goToPage(parseInt(this.dataset.page, 10));
                            startAutoplay();
                        });
                        dotsWrap.appendChild(dot);
                    }

                    currentPage = Math.min(currentPage, data.pages - 1);
                    setActiveDot(currentPage);
                    startAutoplay();
                }

                buildDots();
                track.addEventListener('scroll', updateDots, { passive: true });
                track.addEventListener('mouseenter', stopAutoplay);
                track.addEventListener('mouseleave', startAutoplay);
                window.addEventListener('resize', buildDots);
            });
        }

        function initReveal() {
            var related = document.querySelector('.equipment-related');
            if (!related) return;
            related.classList.remove('is-visible');
            var ro = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        ro.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08 });
            ro.observe(related);
        }

        function equalizeRelated() {
            var grid = document.querySelector('.equipment-related-grid:not(.has-carousel *)');
            // Only equalize the static grid, not the horizontal scroll carousel
            var section = document.querySelector('.equipment-related');
            if (!section || section.classList.contains('has-carousel')) return;
            var g = section.querySelector('.equipment-related-grid');
            if (!g) return;
            g.style.gridAutoRows = '';
            g.offsetHeight;
            var max = 0;
            Array.from(g.querySelectorAll('.equipment-related-card')).forEach(function(c) {
                if (c.offsetHeight > max) max = c.offsetHeight;
            });
            if (max > 0) g.style.gridAutoRows = Math.max(max, 300) + 'px';
        }

        var resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(equalizeRelated, 150);
        });

        initRelatedCarousels();
        initReveal();
        equalizeRelated();

        document.addEventListener('livewire:navigated', function() {
            initRelatedCarousels();
            initReveal();
            equalizeRelated();
        });
    })();
    </script>
</div>
