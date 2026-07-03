<div
    class="project-detail-page"
    x-data="{
        open: false,
        active: 0,
        images: @js($project->images->values()->map(fn($image) => [
            'src' => Storage::url($image->image),
            'alt' => $project->title,
        ])),
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
    <nav class="project-detail-breadcrumb">
        <a wire:navigate href="{{ url('/') }}">Inicio</a>
        <span>›</span>
        <a wire:navigate href="{{ route('proyectos.public') }}">Proyectos</a>
        <span>›</span>
        <span>{{ $project->title }}</span>
    </nav>

    <section class="project-detail">
        <h1>{{ $project->title }}</h1>

        <div class="project-description">
            {!! str_replace('&nbsp;', ' ', $project->description) !!}
        </div>

        <a href="{{ route('presupuesto.public') }}" class="project-budget-button">Pedir presupuesto</a>

        <div class="project-masonry">
            @foreach($project->images->chunk(2) as $row)
                <div class="project-masonry-row {{ $row->count() === 1 ? 'is-single' : 'is-pair' }}">
                    @foreach($row as $image)
                        @php $imageIndex = $project->images->values()->search(fn($item) => $item->id === $image->id); @endphp
                        <figure
                            class="project-gallery-item"
                            role="button"
                            tabindex="0"
                            @click="show({{ $imageIndex }})"
                            @keydown.enter.prevent="show({{ $imageIndex }})"
                            @keydown.space.prevent="show({{ $imageIndex }})"
                            aria-label="Abrir imagen {{ $imageIndex + 1 }} de {{ $project->images->count() }}"
                        >
                            <img src="{{ Storage::url($image->image) }}" alt="{{ $project->title }}" loading="lazy">
                        </figure>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <div
        x-show="open"
        x-cloak
        x-transition.opacity.duration.200ms
        class="project-lightbox"
        role="dialog"
        aria-modal="true"
        aria-label="Galeria de imagenes del proyecto"
    >
        <button type="button" class="project-lightbox-backdrop" @click="close()" aria-label="Cerrar galeria"></button>

        <div class="project-lightbox-panel">
            <div class="project-lightbox-topbar">
                <div class="project-lightbox-meta">
                    <span>{{ $project->title }}</span>
                    <small x-text="`${active + 1} / ${images.length}`"></small>
                </div>

                <button type="button" class="project-lightbox-close" @click="close()" aria-label="Cerrar">
                    <svg width="38" height="38" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <button
                type="button"
                class="project-lightbox-nav is-prev"
                @click="prev()"
                x-show="images.length > 1"
                aria-label="Imagen anterior"
            >
                <svg width="58" height="58" viewBox="0 0 24 24" fill="none">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="project-lightbox-image-wrap">
                <img :src="images[active]?.src" :alt="images[active]?.alt" class="project-lightbox-image">
            </div>

            <button
                type="button"
                class="project-lightbox-nav is-next"
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

        .project-detail-page {
            background: #fff;
            padding-bottom: 96px;
        }

        .project-detail-breadcrumb {
            max-width: 1224px;
            margin: 24px auto 0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 5px;
            color: #808080;
            font-size: 12px;
            line-height: 1.5;
        }

        .project-detail-breadcrumb a {
            color: #333;
            font-weight: 700;
            text-decoration: none;
        }

        .project-detail {
            max-width: 1224px;
            margin: 56px auto 0;
        }

        .project-detail h1 {
            margin: 0 0 18px;
            color: #404041;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.15;
        }

        .project-detail h1::after {
            content: "";
            display: block;
            height: 1px;
            background: #353C471A;
            margin-top: 18px;
        }

        .project-description {
            color: #1f1f1f;
            font-size: 16px;
            line-height: 1.7;
            max-width: 1224px;
        }

        .project-description p {
            margin: 0 0 12px;
        }

        .project-budget-button {
            width: 287px;
            height: 41px;
            margin-top: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #52A028;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color .2s ease;
        }

        .project-budget-button:hover {
            background: #3f8620;
        }

        .project-masonry {
            margin-top: 64px;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .project-masonry-row {
            display: grid;
            gap: 28px;
        }

        .project-masonry-row.is-single {
            grid-template-columns: 1fr;
        }

        .project-masonry-row.is-pair {
            grid-template-columns: minmax(0, 1fr) minmax(0, 2fr);
        }

        .project-masonry-row:nth-child(even).is-pair {
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
        }

        .project-masonry figure {
            margin: 0;
            height: 475px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .project-gallery-item {
            position: relative;
            cursor: zoom-in;
            outline: none;
        }

        .project-gallery-item::after {
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

        .project-gallery-item:hover::after,
        .project-gallery-item:focus-visible::after {
            opacity: 1;
            transform: translateY(0);
        }

        .project-gallery-item:focus-visible {
            box-shadow: 0 0 0 3px rgba(82, 160, 40, .45);
        }

        .project-masonry img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .project-lightbox {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
        }

        .project-lightbox-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(7, 12, 9, .86);
            backdrop-filter: blur(10px);
            cursor: zoom-out;
        }

        .project-lightbox-panel {
            position: relative;
            z-index: 1;
            width: min(1180px, 100%);
            height: min(760px, calc(100vh - 56px));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-lightbox-topbar {
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

        .project-lightbox-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .project-lightbox-meta span {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: min(760px, calc(100vw - 160px));
        }

        .project-lightbox-meta small {
            color: rgba(255,255,255,.72);
            font-size: 13px;
        }

        .project-lightbox-close,
        .project-lightbox-nav {
            border: 0;
            background: transparent;
            color: #52A028;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color .2s ease, transform .2s ease, opacity .2s ease;
        }

        .project-lightbox-close {
            width: 48px;
            height: 48px;
        }

        .project-lightbox-close:hover,
        .project-lightbox-nav:hover {
            color: #6fc844;
        }

        .project-lightbox-image-wrap {
            width: 100%;
            height: calc(100% - 78px);
            margin-top: 78px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-lightbox-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            box-shadow: 0 28px 80px rgba(0,0,0,.45);
        }

        .project-lightbox-nav {
            position: absolute;
            top: 50%;
            z-index: 3;
            width: 72px;
            height: 72px;
            transform: translateY(-50%);
        }

        .project-lightbox-nav:hover {
            transform: translateY(-50%) scale(1.04);
        }

        .project-lightbox-nav.is-prev {
            left: -72px;
        }

        .project-lightbox-nav.is-next {
            right: -72px;
        }

        @media (max-width: 1199px) {
            .project-detail-breadcrumb,
            .project-detail {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }
        }

        @media (max-width: 767px) {
            .project-detail-breadcrumb,
            .project-detail {
                margin-left: 20px;
                margin-right: 20px;
            }

            .project-detail {
                margin-top: 36px;
            }

            .project-detail h1 {
                font-size: 26px;
            }

            .project-masonry-row,
            .project-masonry-row.is-pair {
                grid-template-columns: 1fr;
            }

            .project-masonry figure {
                min-height: 260px;
            }

            .project-lightbox {
                padding: 16px;
            }

            .project-lightbox-panel {
                height: calc(100vh - 32px);
            }

            .project-lightbox-nav {
                top: auto;
                bottom: 18px;
                transform: none;
                width: 46px;
                height: 46px;
            }

            .project-lightbox-nav:hover {
                transform: scale(1.04);
            }

            .project-lightbox-nav.is-prev {
                left: calc(50% - 58px);
            }

            .project-lightbox-nav.is-next {
                right: calc(50% - 58px);
            }

            .project-lightbox-image-wrap {
                height: calc(100% - 120px);
                margin-top: 70px;
                margin-bottom: 50px;
            }
        }

        @keyframes proj-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .project-detail-breadcrumb {
            animation: proj-fade-up 0.6s ease 0.1s both;
        }

        .project-detail {
            animation: proj-fade-up 0.7s ease 0.22s both;
        }

        .project-masonry-row {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .project-masonry-row.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
    (function() {
        function initReveal() {
            var rows = Array.prototype.slice.call(document.querySelectorAll('.project-masonry-row'));
            if (!rows.length) return;

            rows.forEach(function(r) { r.classList.remove('is-visible'); });

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
            document.addEventListener('DOMContentLoaded', initReveal);
        } else {
            initReveal();
        }

        document.addEventListener('livewire:navigated', initReveal);
    })();
    </script>
</div>
